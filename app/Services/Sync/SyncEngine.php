<?php

namespace App\Services\Sync;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class SyncEngine
{
    /**
     * Récupère les opérations locales en attente.
     */
    public function getPendingOperations(?int $limit = null): Collection
    {
        $limit ??= config('sync.batch_size', 100);

        return DB::table('sync_operations')
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    /**
     * Nombre d'opérations locales en attente.
     */
    public function pendingCount(): int
    {
        return DB::table('sync_operations')
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Prépare un lot d'opérations pour l'envoi vers le Cloud.
     */
    public function buildBatch(?int $limit = null): array
    {
        $operations = $this->getPendingOperations($limit);

        return [
            'device' => [
                'id' => app(DeviceIdentity::class)->get(),
            ],

            'operations' => $operations->map(function ($operation) {
                return [
                    'uuid_operation' => $operation->uuid_operation,
                    'table_name' => $operation->table_name,
                    'record_uuid' => $operation->record_uuid,
                    'operation' => $operation->operation,

                    'payload' => $operation->payload
                        ? json_decode($operation->payload, true)
                        : null,

                    'created_at' => $operation->created_at,
                ];
            })->values()->all(),
        ];
    }

    /**
     * Envoie les opérations locales en attente vers le Cloud.
     */
    public function pushPending(?int $limit = null): array
    {
        /*
        * 1. Bootstrap des établissements.
        *
        * Le Cloud nous indique quels UUID doivent devenir
        * les identités globales des établissements locaux.
        */
        $establishments = DB::table('etablissements')
            ->orderBy('id_etablissement')
            ->get([
                'id_etablissement',
                'nom',
                'code',
                'type',
                'province',
                'ville',
                'commune',
                'adresse',
                'telephone',
                'email',
                'directeur',
                'logo',
                'statut',
                'date_creation',
                'date_modification',
                'uuid_sync',
            ])
            ->toArray();

        $bootstrapPayload = [
            'device' => [
                'id' => app(DeviceIdentity::class)->get(),
            ],
            'establishments' => $establishments,
        ];

        $bootstrapResponse = app(SyncApiService::class)
            ->bootstrap($bootstrapPayload);

        if (!($bootstrapResponse['success'] ?? false)) {
            throw new \RuntimeException(
                'Le bootstrap de synchronisation a échoué.'
            );
        }

        /*
        * 2. Application des UUID Cloud côté Local.
        */
        $bootstrapResults = app(SyncBootstrapService::class)
            ->applyEstablishmentMappings(
                $bootstrapResponse['establishments'] ?? []
            );

        /*
        * 3. IMPORTANT :
        * On relit les opérations APRÈS le bootstrap.
        *
        * Les payloads pending peuvent avoir été modifiés
        * avec les nouveaux UUID des établissements.
        */
        $operations = $this->getPendingOperations($limit);

        /*
        * Rien à envoyer.
        */
        if ($operations->isEmpty()) {
            return [
                'success' => true,
                'status' => 'nothing_to_sync',
                'sent_count' => 0,
                'accepted_count' => 0,
                'rejected_count' => 0,
                'bootstrap' => $bootstrapResults,
            ];
        }

        /*
        * 4. Construction du lot APRÈS harmonisation.
        */
        $batch = $this->buildBatch($limit);

        $logId = DB::table('sync_logs')->insertGetId([
            'direction' => 'local_to_cloud',
            'table_name' => null,
            'record_uuid' => null,
            'operations_count' => $operations->count(),
            'status' => 'started',
            'message' => 'Envoi des opérations locales vers le Cloud.',
            'started_at' => now(),
            'finished_at' => null,
        ]);

        try {
            /*
            * 5. Envoi vers le Cloud.
            */
            $response = app(SyncApiService::class)->push($batch);

            $accepted = $response['accepted'] ?? [];
            $rejected = $response['rejected'] ?? [];

            /*
            * 6. Marquer uniquement les opérations acceptées
            * comme synchronisées.
            */
            foreach ($accepted as $operation) {
                if (!empty($operation['uuid_operation'])) {
                    DB::table('sync_operations')
                        ->where(
                            'uuid_operation',
                            $operation['uuid_operation']
                        )
                        ->where('status', 'pending')
                        ->update([
                            'status' => 'synced',
                            'synced_at' => now(),
                            'error_message' => null,
                        ]);
                }
            }

            /*
            * 7. Les opérations rejetées restent pending.
            */
            foreach ($rejected as $operation) {
                if (!empty($operation['uuid_operation'])) {
                    DB::table('sync_operations')
                        ->where(
                            'uuid_operation',
                            $operation['uuid_operation']
                        )
                        ->where('status', 'pending')
                        ->update([
                            'status' => 'pending',
                            'error_message' => $operation['reason'] ?? null,
                        ]);
                }
            }

            $acceptedCount = count($accepted);
            $rejectedCount = count($rejected);

            $logStatus = $rejectedCount === 0
                ? 'success'
                : ($acceptedCount > 0 ? 'partial' : 'failed');

            DB::table('sync_logs')
                ->where('id', $logId)
                ->update([
                    'status' => $logStatus,
                    'message' => $rejectedCount === 0
                        ? 'Synchronisation Local → Cloud réussie.'
                        : 'Synchronisation terminée avec des opérations rejetées.',
                    'finished_at' => now(),
                ]);

            return [
                'success' => $rejectedCount === 0,
                'status' => $logStatus,
                'sent_count' => $operations->count(),
                'accepted_count' => $acceptedCount,
                'rejected_count' => $rejectedCount,
                'bootstrap' => $bootstrapResults,
                'response' => $response,
            ];
        } catch (Throwable $e) {
            DB::table('sync_logs')
                ->where('id', $logId)
                ->update([
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                    'finished_at' => now(),
                ]);

            throw $e;
        }
    }

    /**
     * Récupère les opérations du Cloud et les applique dans la base locale.
     */
    public function pullPending(?int $limit = null): array
    {
        $limit ??= config('sync.batch_size', 100);

        $deviceId = app(DeviceIdentity::class)->get();

        $cursor = (int) (
            DB::table('sync_device_cursors')
                ->where('device_id', $deviceId)
                ->value('last_operation_id') ?? 0
        );

        $response = app(SyncApiService::class)->pull(
            $deviceId,
            $cursor,
            $limit
        );

        if (!($response['success'] ?? false)) {
            throw new \RuntimeException(
                'La récupération des opérations Cloud a échoué.'
            );
        }

        $operations = $response['operations'] ?? [];

        if (empty($operations)) {
            return [
                'success' => true,
                'status' => 'nothing_to_pull',
                'received_count' => 0,
                'accepted_count' => 0,
                'rejected_count' => 0,
                'cursor' => $cursor,
                'next_cursor' => $cursor,
            ];
        }

        $logId = DB::table('sync_logs')->insertGetId([
            'direction' => 'cloud_to_local',
            'table_name' => null,
            'record_uuid' => null,
            'operations_count' => count($operations),
            'status' => 'started',
            'message' => 'Récupération des opérations Cloud vers le Local.',
            'started_at' => now(),
            'finished_at' => null,
        ]);

        try {
            $result = app(SyncReceiverService::class)
                ->receivePulled($operations);

            $acceptedCount = $result['accepted_count'] ?? 0;
            $rejectedCount = $result['rejected_count'] ?? 0;

            /*
             * On n'avance le curseur que si toutes les opérations
             * du lot ont été correctement traitées.
             */
            if ($rejectedCount === 0) {
                $nextCursor = (int) (
                    $response['next_cursor'] ?? $cursor
                );

                DB::table('sync_device_cursors')
                    ->updateOrInsert(
                        ['device_id' => $deviceId],
                        [
                            'last_operation_id' => $nextCursor,
                            'updated_at' => now(),
                        ]
                    );
            } else {
                $nextCursor = $cursor;
            }

            $logStatus = $rejectedCount === 0
                ? 'success'
                : ($acceptedCount > 0 ? 'partial' : 'failed');

            DB::table('sync_logs')
                ->where('id', $logId)
                ->update([
                    'status' => $logStatus,
                    'message' => $rejectedCount === 0
                        ? 'Synchronisation Cloud → Local réussie.'
                        : 'Synchronisation Cloud → Local terminée avec des opérations rejetées.',
                    'finished_at' => now(),
                ]);

            return [
                'success' => $rejectedCount === 0,
                'status' => $logStatus,
                'received_count' => count($operations),
                'accepted_count' => $acceptedCount,
                'rejected_count' => $rejectedCount,
                'cursor' => $cursor,
                'next_cursor' => $nextCursor,
                'response' => $response,
                'result' => $result,
            ];
        } catch (Throwable $e) {
            DB::table('sync_logs')
                ->where('id', $logId)
                ->update([
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                    'finished_at' => now(),
                ]);

            throw $e;
        }
    }

        /**
     * Synchronisation complète Local ↔ Cloud.
     *
     * Ordre :
     * 1. Local → Cloud
     * 2. Cloud → Local
     */
    public function sync(?int $limit = null): array
    {
        $push = $this->pushPending($limit);

        $pull = $this->pullPending($limit);

        return [
            'success' => ($push['success'] ?? false)
                && ($pull['success'] ?? false),

            'push' => $push,
            'pull' => $pull,
        ];
    }
}
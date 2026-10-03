<?php

namespace App\Services\Sync;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class SyncBootstrapService
{
    /**
     * Applique au Local les correspondances d'établissements
     * retournées par le Cloud lors du bootstrap.
     */
    public function applyEstablishmentMappings(array $mappings): array
    {
        $results = [];

        foreach ($mappings as $mapping) {
            $code = $mapping['code'] ?? null;
            $localUuid = $mapping['local_uuid'] ?? null;
            $cloudUuid = $mapping['cloud_uuid'] ?? null;

            if (!$code || !$localUuid || !$cloudUuid) {
                throw new RuntimeException(
                    'Mapping établissement incomplet reçu du Cloud.'
                );
            }

            $local = DB::table('etablissements')
                ->where('code', $code)
                ->first();

            if (!$local) {
                throw new RuntimeException(
                    "Établissement Local introuvable pour le code {$code}."
                );
            }

            /*
             * Protection contre une réponse bootstrap devenue obsolète.
             */
            if ($local->uuid_sync !== $localUuid) {
                throw new RuntimeException(
                    "UUID Local inattendu pour {$code} : "
                    . "attendu={$localUuid}, "
                    . "actuel={$local->uuid_sync}."
                );
            }

            /*
             * Rien à modifier.
             */
            if ($localUuid === $cloudUuid) {
                $results[] = [
                    'code' => $code,
                    'action' => 'already_harmonized',
                    'uuid_sync' => $cloudUuid,
                ];

                continue;
            }

            /*
             * Le UUID Cloud devient l'identité globale de
             * l'établissement.
             */
            DB::table('etablissements')
                ->where('id_etablissement', $local->id_etablissement)
                ->update([
                    'uuid_sync' => $cloudUuid,
                ]);

            /*
             * Les opérations Local encore pending peuvent contenir
             * l'ancien UUID de l'établissement.
             */
            $updatedOperations = $this->updatePendingPayloads(
                $localUuid,
                $cloudUuid
            );

            $results[] = [
                'code' => $code,
                'action' => 'uuid_harmonized',
                'old_uuid' => $localUuid,
                'new_uuid' => $cloudUuid,
                'updated_operations' => $updatedOperations,
            ];
        }

        return $results;
    }

    /**
     * Remplace l'ancien UUID d'établissement dans les payloads
     * des opérations encore en attente.
     */
    protected function updatePendingPayloads(
        string $oldUuid,
        string $newUuid
    ): int {
        $updated = 0;

        DB::table('sync_operations')
            ->where('status', 'pending')
            ->whereIn('table_name', [
                'annees_scolaires',
                'classes',
                'matieres',
                'personnel',
                'eleves',
                'categories_frais',
                'frais_eleves',
                'paiements',
                'recettes',
                'depenses',
                'infrastructures',
            ])
            ->orderBy('id')
            ->get()
            ->each(function ($operation) use (
                $oldUuid,
                $newUuid,
                &$updated
            ) {
                if (!$operation->payload) {
                    return;
                }

                $payload = json_decode($operation->payload, true);

                if (!is_array($payload)) {
                    return;
                }

                if (
                    !isset($payload['etablissement_uuid']) ||
                    $payload['etablissement_uuid'] !== $oldUuid
                ) {
                    return;
                }

                $payload['etablissement_uuid'] = $newUuid;

                DB::table('sync_operations')
                    ->where('id', $operation->id)
                    ->where('status', 'pending')
                    ->update([
                        'payload' => json_encode(
                            $payload,
                            JSON_UNESCAPED_UNICODE |
                            JSON_UNESCAPED_SLASHES
                        ),
                    ]);

                $updated++;
            });

        return $updated;
    }
}
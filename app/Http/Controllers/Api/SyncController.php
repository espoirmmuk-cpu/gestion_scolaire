<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Sync\SyncReceiverService;
use Throwable;

class SyncController extends Controller
{
    public function ping(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token || !hash_equals((string) config('sync.token'), $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token de synchronisation invalide.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'API de synchronisation GESCO opérationnelle.',
            'device' => $request->input('device'),
        ]);
    }

    /**
     * Bootstrap / harmonisation des établissements.
     *
     * Le Local envoie ses établissements.
     * Le Cloud les rapproche par leur code et retourne
     * l'identité UUID officielle à utiliser.
     */
    public function bootstrap(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token || !hash_equals((string) config('sync.token'), $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token de synchronisation invalide.',
            ], 401);
        }

        $validated = $request->validate([
            'device.id' => ['required', 'string', 'max:100'],

            'establishments' => ['required', 'array'],

            'establishments.*.id_etablissement' => [
                'required',
                'integer',
            ],

            'establishments.*.nom' => [
                'required',
                'string',
                'max:255',
            ],

            'establishments.*.code' => [
                'required',
                'string',
                'max:100',
            ],

            'establishments.*.type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'establishments.*.province' => [
                'nullable',
                'string',
                'max:255',
            ],

            'establishments.*.ville' => [
                'nullable',
                'string',
                'max:255',
            ],

            'establishments.*.commune' => [
                'nullable',
                'string',
                'max:255',
            ],

            'establishments.*.adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'establishments.*.telephone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'establishments.*.email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'establishments.*.directeur' => [
                'nullable',
                'string',
                'max:255',
            ],

            'establishments.*.logo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'establishments.*.statut' => [
                'nullable',
                'string',
                'max:50',
            ],

            'establishments.*.date_creation' => [
                'nullable',
                'date',
            ],

            'establishments.*.date_modification' => [
                'nullable',
                'date',
            ],

            'establishments.*.uuid_sync' => [
                'required',
                'uuid',
            ],
        ]);

        try {

        $cloudConnection = DB::connection('gesco_cloud');

            $results = [];

            /*
            * Une seule requête vers le Cloud.
            * Le rapprochement UUID/code est ensuite effectué en mémoire.
            */
            $cloudEstablishments = $cloudConnection
                ->table('etablissements')
                ->get([
                    'id_etablissement',
                    'nom',
                    'code',
                    'uuid_sync',
                ]);

            $cloudByCode = $cloudEstablishments->keyBy('code');

            $cloudByUuid = $cloudEstablishments
                ->filter(fn ($etablissement) => !empty($etablissement->uuid_sync))
                ->keyBy('uuid_sync');

            foreach ($validated['establishments'] as $local) {

                $cloudUuid = $cloudByUuid->get($local['uuid_sync']);

                $cloud = $cloudByCode->get($local['code']);

                /*
                * L’UUID local existe déjà dans le Cloud
                * mais correspond à un autre établissement.
                */
                if (
                    $cloudUuid &&
                    $cloudUuid->code !== $local['code']
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => sprintf(
                            'Conflit UUID pour l’établissement %s : '
                            . 'l’UUID %s est déjà associé au code %s dans le Cloud.',
                            $local['code'],
                            $local['uuid_sync'],
                            $cloudUuid->code
                        ),
                    ], 409);
                }

                /*
                * L’établissement n’existe pas encore dans le Cloud.
                */
                if (!$cloud) {

                    $data = $local;

                    unset($data['id_etablissement']);

                    $data['uuid_sync'] = $local['uuid_sync'];

                    $cloudId = $cloudConnection
                        ->table('etablissements')
                        ->insertGetId($data);

                    /*
                    * On met aussi à jour les collections locales
                    * pour éviter une incohérence pendant la même boucle.
                    */
                    $created = (object) [
                        'id_etablissement' => $cloudId,
                        'nom' => $local['nom'],
                        'code' => $local['code'],
                        'uuid_sync' => $local['uuid_sync'],
                    ];

                    $cloudByCode->put($local['code'], $created);
                    $cloudByUuid->put($local['uuid_sync'], $created);

                    $results[] = [
                        'code' => $local['code'],
                        'action' => 'created_cloud',
                        'local_uuid' => $local['uuid_sync'],
                        'cloud_uuid' => $local['uuid_sync'],
                        'cloud_id_etablissement' => $cloudId,
                    ];

                    continue;
                }

                /*
                * Le code existe mais le nom est différent.
                */
                if ($cloud->nom !== $local['nom']) {
                    return response()->json([
                        'success' => false,
                        'message' => sprintf(
                            'Conflit pour l’établissement %s : Local="%s", Cloud="%s".',
                            $local['code'],
                            $local['nom'],
                            $cloud->nom
                        ),
                    ], 409);
                }

                /*
                * Le Cloud possède l’établissement mais pas encore son UUID.
                */
                if (empty($cloud->uuid_sync)) {

                    $cloudConnection
                        ->table('etablissements')
                        ->where('id_etablissement', $cloud->id_etablissement)
                        ->update([
                            'uuid_sync' => $local['uuid_sync'],
                        ]);

                    $cloud->uuid_sync = $local['uuid_sync'];

                    $cloudByUuid->put(
                        $local['uuid_sync'],
                        $cloud
                    );

                    $results[] = [
                        'code' => $local['code'],
                        'action' => 'adopted_local_uuid',
                        'local_uuid' => $local['uuid_sync'],
                        'cloud_uuid' => $cloud->uuid_sync,
                        'cloud_id_etablissement' => $cloud->id_etablissement,
                    ];

                    continue;
                }

                /*
                * Les deux établissements existent déjà.
                */
                $results[] = [
                    'code' => $local['code'],
                    'action' => $local['uuid_sync'] === $cloud->uuid_sync
                        ? 'already_harmonized'
                        : 'use_cloud_uuid',
                    'local_uuid' => $local['uuid_sync'],
                    'cloud_uuid' => $cloud->uuid_sync,
                    'cloud_id_etablissement' => $cloud->id_etablissement,
                ];
            }

            return response()->json([
                'success' => true,
                'device' => $validated['device'],
                'establishments' => $results,
            ]);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Exception pendant le bootstrap Cloud.',
                'exception' => get_class($e),
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function push(
        Request $request,
        SyncReceiverService $receiver
    ): JsonResponse {
        $token = $request->bearerToken();

        if (!$token || !hash_equals((string) config('sync.token'), $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token de synchronisation invalide.',
            ], 401);
        }

        $validated = $request->validate([
            'device.id' => ['required', 'string', 'max:100'],
            'operations' => ['required', 'array'],
        ]);

        $result = $receiver->receive($validated);

        return response()->json([
            'success' => true,
            'device' => $validated['device'],
            ...$result,
        ]);
    }

        public function pull(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token || !hash_equals((string) config('sync.token'), $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token de synchronisation invalide.',
            ], 401);
        }

        $validated = $request->validate([
            'device_id' => ['required', 'string', 'max:100'],
            'last_operation_id' => ['nullable', 'integer', 'min:0'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $deviceId = $validated['device_id'];
        $cursor = (int) ($validated['last_operation_id'] ?? 0);
        $limit = (int) ($validated['limit'] ?? 100);

        $operations = DB::connection('gesco_cloud')
            ->table('sync_operations')
            ->where('id', '>', $cursor)
            ->where(function ($query) use ($deviceId) {
                $query
                    ->whereNull('source_device_id')
                    ->orWhere('source_device_id', '!=', $deviceId);
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $nextCursor = $cursor;

        if ($operations->isNotEmpty()) {
            $nextCursor = (int) $operations->max('id');
        }

        return response()->json([
            'success' => true,
            'device_id' => $deviceId,
            'cursor' => $cursor,
            'next_cursor' => $nextCursor,
            'operations' => $operations->map(function ($operation) {
                return [
                    'id' => $operation->id,
                    'uuid_operation' => $operation->uuid_operation,
                    'source_device_id' => $operation->source_device_id,
                    'table_name' => $operation->table_name,
                    'record_uuid' => $operation->record_uuid,
                    'operation' => $operation->operation,
                    'payload' => $operation->payload
                        ? json_decode($operation->payload, true)
                        : null,
                    'created_at' => $operation->created_at,
                ];
            })->values()->all(),
        ]);
    }
}
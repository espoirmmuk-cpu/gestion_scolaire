<?php

namespace App\Services\Sync;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RelationSyncService
{
    protected array $relations = [
        'utilisateurs_roles' => [
            'columns' => [
                'id_utilisateur',
                'id_role',
            ],
        ],

        'eleves_responsables' => [
            'columns' => [
                'id_eleve',
                'id_responsable',
            ],
        ],
    ];

    /**
     * Génère les UUID manquants sur les tables de relation.
     */
    public function generateMissingUuids(): array
    {
        $results = [];

        foreach ($this->relations as $table => $config) {
            $count = 0;

            DB::table($table)
                ->whereNull('uuid_sync')
                ->get()
                ->each(function ($relation) use ($table, $config, &$count) {
                    $query = DB::table($table);

                    foreach ($config['columns'] as $column) {
                        $query->where($column, $relation->{$column});
                    }

                    $query->update([
                        'uuid_sync' => (string) Str::uuid(),
                    ]);

                    $count++;
                });

            $results[$table] = $count;
        }

        return $results;
    }

    /**
     * Retourne les tables de relation synchronisables.
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * Retourne toutes les relations d'une table avec leurs UUID.
     */
    public function getRelationsWithUuids(string $table): array
    {
        if (!isset($this->relations[$table])) {
            throw new \InvalidArgumentException(
                "Table de relation non synchronisable : {$table}"
            );
        }

        $config = $this->relations[$table];

        return DB::table($table)
            ->get()
            ->map(function ($relation) use ($table, $config) {
                return $this->buildRelationPayload(
                    $table,
                    $relation,
                    $config
                );
            })
            ->toArray();
    }

    /**
     * Enregistre une opération de création ou de suppression
     * sur une relation existante.
     */
    public function recordRelationOperation(
        string $table,
        array $keys,
        string $operation
    ): int {
        if (!isset($this->relations[$table])) {
            throw new \InvalidArgumentException(
                "Table de relation non synchronisable : {$table}"
            );
        }

        if (!in_array($operation, ['create', 'delete'], true)) {
            throw new \InvalidArgumentException(
                "Opération invalide : {$operation}"
            );
        }

        $config = $this->relations[$table];

        $query = DB::table($table);

        foreach ($config['columns'] as $column) {
            if (!array_key_exists($column, $keys)) {
                throw new \InvalidArgumentException(
                    "Clé manquante : {$column}"
                );
            }

            $query->where($column, $keys[$column]);
        }

        $relation = $query->first();

        if (!$relation) {
            throw new \RuntimeException(
                "Relation introuvable dans {$table}."
            );
        }

        if (empty($relation->uuid_sync)) {
            $uuid = (string) Str::uuid();

            $updateQuery = DB::table($table);

            foreach ($config['columns'] as $column) {
                $updateQuery->where($column, $keys[$column]);
            }

            $updateQuery->update([
                'uuid_sync' => $uuid,
            ]);

            $relation->uuid_sync = $uuid;
        }

        $payload = $this->buildRelationPayload(
            $table,
            $relation,
            $config
        );

        return $this->insertOperation(
            $table,
            $relation->uuid_sync,
            $operation,
            $payload
        );
    }

    /**
     * Enregistre une suppression à partir d'une relation
     * capturée avant detach().
     */
    public function recordRelationDelete(
        string $table,
        array $relation
    ): int {
        if (!isset($this->relations[$table])) {
            throw new \InvalidArgumentException(
                "Table de relation non synchronisable : {$table}"
            );
        }

        $config = $this->relations[$table];

        foreach ($config['columns'] as $column) {
            if (!array_key_exists($column, $relation)) {
                throw new \InvalidArgumentException(
                    "Clé manquante : {$column}"
                );
            }
        }

        $uuid = $relation['uuid_sync'] ?? null;

        /**
         * Une relation existante sans UUID doit recevoir son UUID
         * avant sa suppression afin que le Cloud puisse identifier
         * précisément la relation à supprimer.
         */
        if (empty($uuid)) {
            $uuid = (string) Str::uuid();

            $updateQuery = DB::table($table);

            foreach ($config['columns'] as $column) {
                $updateQuery->where($column, $relation[$column]);
            }

            $updateQuery->update([
                'uuid_sync' => $uuid,
            ]);
        }

        $payload = [
            'uuid_sync' => $uuid,
        ];

        if ($table === 'utilisateurs_roles') {
            $payload['utilisateur_uuid'] = DB::table('utilisateurs')
                ->where(
                    'id_utilisateur',
                    $relation['id_utilisateur']
                )
                ->value('uuid_sync');

            $payload['role_uuid'] = DB::table('roles')
                ->where(
                    'id_role',
                    $relation['id_role']
                )
                ->value('uuid_sync');
        }

        if ($table === 'eleves_responsables') {
            $payload['eleve_uuid'] = DB::table('eleves')
                ->where(
                    'id_eleve',
                    $relation['id_eleve']
                )
                ->value('uuid_sync');

            $payload['responsable_uuid'] = DB::table('responsables')
                ->where(
                    'id_responsable',
                    $relation['id_responsable']
                )
                ->value('uuid_sync');
        }

        return $this->insertOperation(
            $table,
            $uuid,
            'delete',
            $payload
        );
    }

    /**
     * Construit le payload synchronisable d'une relation.
     */
    protected function buildRelationPayload(
        string $table,
        object $relation,
        array $config
    ): array {
        $result = [
            'table_name' => $table,
            'uuid_sync' => $relation->uuid_sync,
        ];

        foreach ($config['columns'] as $column) {
            $result[$column] = $relation->{$column};
        }

        if ($table === 'utilisateurs_roles') {
            $result['utilisateur_uuid'] = DB::table('utilisateurs')
                ->where(
                    'id_utilisateur',
                    $relation->id_utilisateur
                )
                ->value('uuid_sync');

            $result['role_uuid'] = DB::table('roles')
                ->where(
                    'id_role',
                    $relation->id_role
                )
                ->value('uuid_sync');
        }

        if ($table === 'eleves_responsables') {
            $result['eleve_uuid'] = DB::table('eleves')
                ->where(
                    'id_eleve',
                    $relation->id_eleve
                )
                ->value('uuid_sync');

            $result['responsable_uuid'] = DB::table('responsables')
                ->where(
                    'id_responsable',
                    $relation->id_responsable
                )
                ->value('uuid_sync');
        }

        return $result;
    }

    /**
     * Insère une opération dans la file locale de synchronisation.
     */
    protected function insertOperation(
        string $table,
        string $recordUuid,
        string $operation,
        array $payload
    ): int {
        return DB::table('sync_operations')->insertGetId([
            'uuid_operation' => (string) Str::uuid(),
            'table_name' => $table,
            'record_uuid' => $recordUuid,
            'operation' => $operation,
            'payload' => json_encode($payload),
            'created_at' => now(),
            'synced_at' => null,
            'status' => 'pending',
            'error_message' => null,
        ]);
    }
}
<?php

namespace App\Services\Sync;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;

class SyncReceiverService
{
    /**
     * Tables métier autorisées à être synchronisées.
     */
    protected array $allowedTables = [
        'etablissements',
        'annees_scolaires',
        'niveaux',
        'periodes_scolaires',
        'classes',
        'matieres',
        'personnel',
        'responsables',
        'categories_frais',
        'categories_inventaire',
        'eleves',
        'inscriptions',
        'tarifs_scolaires',
        'frais_eleves',
        'paiements',
        'details_paiements',
        'evaluations',
        'notes',
        'bulletins',
        'details_bulletins',
        'presences',
        'activites',
        'depenses',
        'recettes',
        'infrastructures',
        'inventaire',
        'classes_matieres',
        'affectations_enseignants',
        'utilisateurs',
        'journaux_activites',
        'permissions',
    ];

    /**
     * Tables de relation possédant une clé primaire composite.
     *
     * Elles ne peuvent donc pas utiliser directement la logique
     * générique basée sur $primaryKeys.
     */
    protected array $relationTables = [
        'utilisateurs_roles',
        'eleves_responsables',
    ];

    /**
     * Clés primaires simples des tables métier.
     */
    protected array $primaryKeys = [
        'etablissements' => 'id_etablissement',
        'annees_scolaires' => 'id_annee_scolaire',
        'niveaux' => 'id_niveau',
        'periodes_scolaires' => 'id_periode',
        'classes' => 'id_classe',
        'matieres' => 'id_matiere',
        'personnel' => 'id_personnel',
        'responsables' => 'id_responsable',
        'categories_frais' => 'id_categorie_frais',
        'categories_inventaire' => 'id_categorie',
        'eleves' => 'id_eleve',
        'inscriptions' => 'id_inscription',
        'tarifs_scolaires' => 'id_tarif',
        'frais_eleves' => 'id_frais_eleve',
        'paiements' => 'id_paiement',
        'details_paiements' => 'id_detail_paiement',
        'evaluations' => 'id_evaluation',
        'notes' => 'id_note',
        'bulletins' => 'id_bulletin',
        'details_bulletins' => 'id_detail',
        'presences' => 'id_presence',
        'activites' => 'id_activite',
        'depenses' => 'id_depense',
        'recettes' => 'id_recette',
        'infrastructures' => 'id_infrastructure',
        'inventaire' => 'id_inventaire',
        'classes_matieres' => 'id_classe_matiere',
        'affectations_enseignants' => 'id_affectation',
        'utilisateurs' => 'id_utilisateur',
        'journaux_activites' => 'id_journal',
        'permissions' => 'id_permission',
    ];

    /**
     * Relations entre clés locales et UUID synchronisés.
     */
    protected array $foreignKeys = [
        'annees_scolaires' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'classes' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
            'id_niveau' => [
                'table' => 'niveaux',
                'uuid_field' => 'niveau_uuid',
            ],
        ],

        'matieres' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'personnel' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'eleves' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'inscriptions' => [
            'id_eleve' => [
                'table' => 'eleves',
                'uuid_field' => 'eleve_uuid',
            ],
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
            'id_classe' => [
                'table' => 'classes',
                'uuid_field' => 'classe_uuid',
            ],
        ],

        'evaluations' => [
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
            'id_classe' => [
                'table' => 'classes',
                'uuid_field' => 'classe_uuid',
            ],
            'id_matiere' => [
                'table' => 'matieres',
                'uuid_field' => 'matiere_uuid',
            ],
            'id_periode' => [
                'table' => 'periodes_scolaires',
                'uuid_field' => 'periode_uuid',
            ],
        ],

        'notes' => [
            'id_evaluation' => [
                'table' => 'evaluations',
                'uuid_field' => 'evaluation_uuid',
            ],
            'id_eleve' => [
                'table' => 'eleves',
                'uuid_field' => 'eleve_uuid',
            ],
        ],

        'bulletins' => [
            'id_eleve' => [
                'table' => 'eleves',
                'uuid_field' => 'eleve_uuid',
            ],
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
            'id_periode' => [
                'table' => 'periodes_scolaires',
                'uuid_field' => 'periode_uuid',
            ],
            'id_classe' => [
                'table' => 'classes',
                'uuid_field' => 'classe_uuid',
            ],
        ],

        'details_bulletins' => [
            'id_bulletin' => [
                'table' => 'bulletins',
                'uuid_field' => 'bulletin_uuid',
            ],
            'id_matiere' => [
                'table' => 'matieres',
                'uuid_field' => 'matiere_uuid',
            ],
        ],

        'presences' => [
            'id_eleve' => [
                'table' => 'eleves',
                'uuid_field' => 'eleve_uuid',
            ],
            'id_classe' => [
                'table' => 'classes',
                'uuid_field' => 'classe_uuid',
            ],
        ],

        'tarifs_scolaires' => [
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
            'id_classe' => [
                'table' => 'classes',
                'uuid_field' => 'classe_uuid',
            ],
            'id_categorie_frais' => [
                'table' => 'categories_frais',
                'uuid_field' => 'categorie_frais_uuid',
            ],
        ],

        'categories_frais' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'frais_eleves' => [
            'id_eleve' => [
                'table' => 'eleves',
                'uuid_field' => 'eleve_uuid',
            ],
            'id_inscription' => [
                'table' => 'inscriptions',
                'uuid_field' => 'inscription_uuid',
            ],
            'id_tarif' => [
                'table' => 'tarifs_scolaires',
                'uuid_field' => 'tarif_uuid',
            ],
        ],

        'paiements' => [
            'id_eleve' => [
                'table' => 'eleves',
                'uuid_field' => 'eleve_uuid',
            ],
            'id_utilisateur' => [
                'table' => 'utilisateurs',
                'uuid_field' => 'utilisateur_uuid',
            ],
        ],

        'details_paiements' => [
            'id_paiement' => [
                'table' => 'paiements',
                'uuid_field' => 'paiement_uuid',
            ],
            'id_frais_eleve' => [
                'table' => 'frais_eleves',
                'uuid_field' => 'frais_eleve_uuid',
            ],
        ],

        'recettes' => [
            'id_paiement' => [
                'table' => 'paiements',
                'uuid_field' => 'paiement_uuid',
            ],
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
            'id_utilisateur' => [
                'table' => 'utilisateurs',
                'uuid_field' => 'utilisateur_uuid',
            ],
        ],

        'depenses' => [
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
            'id_utilisateur' => [
                'table' => 'utilisateurs',
                'uuid_field' => 'utilisateur_uuid',
            ],
        ],

        'periodes_scolaires' => [
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
        ],

        'activites' => [
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
        ],

        'infrastructures' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'classes_matieres' => [
            'id_classe' => [
                'table' => 'classes',
                'uuid_field' => 'classe_uuid',
            ],
            'id_matiere' => [
                'table' => 'matieres',
                'uuid_field' => 'matiere_uuid',
            ],
            'id_enseignant' => [
                'table' => 'personnel',
                'uuid_field' => 'enseignant_uuid',
            ],
        ],

        'affectations_enseignants' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
            'id_enseignant' => [
                'table' => 'personnel',
                'uuid_field' => 'enseignant_uuid',
            ],
            'id_classe' => [
                'table' => 'classes',
                'uuid_field' => 'classe_uuid',
            ],
            'id_matiere' => [
                'table' => 'matieres',
                'uuid_field' => 'matiere_uuid',
            ],
            'id_annee_scolaire' => [
                'table' => 'annees_scolaires',
                'uuid_field' => 'annee_scolaire_uuid',
            ],
        ],

        'inventaire' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
            'id_categorie' => [
                'table' => 'categories_inventaire',
                'uuid_field' => 'categorie_inventaire_uuid',
            ],
        ],

        'journaux_activites' => [
            'id_utilisateur' => [
                'table' => 'utilisateurs',
                'uuid_field' => 'utilisateur_uuid',
            ],
        ],
    ];

    protected function cloud(): Connection
    {
        return DB::connection('gesco_cloud');
    }


        /**
     * Reçoit des opérations provenant du Cloud et les applique
     * dans la base locale.
     *
     * Les opérations reçues sont enregistrées localement comme
     * déjà synchronisées afin qu'elles ne repartent pas vers le Cloud.
     */
    public function receivePulled(array $operations): array
    {
        if (!is_array($operations)) {
            throw new RuntimeException(
                'Les opérations reçues doivent être un tableau.'
            );
        }

        $local = DB::connection();

        $accepted = [];
        $rejected = [];

        foreach ($operations as $operation) {
            try {
                $accepted[] = $this->applyPulledOperation(
                    $local,
                    $operation
                );
            } catch (\Throwable $e) {
                $rejected[] = [
                    'uuid_operation' => $operation['uuid_operation'] ?? null,
                    'record_uuid' => $operation['record_uuid'] ?? null,
                    'reason' => $e->getMessage(),
                ];
            }
        }

        return [
            'accepted' => $accepted,
            'rejected' => $rejected,
            'accepted_count' => count($accepted),
            'rejected_count' => count($rejected),
        ];
    }

    /**
     * Applique une opération reçue du Cloud dans la base locale.
     */
    protected function applyPulledOperation(
        Connection $local,
        array $operation
    ): array {
        $uuidOperation = $operation['uuid_operation'] ?? null;
        $table = $operation['table_name'] ?? null;
        $recordUuid = $operation['record_uuid'] ?? null;
        $type = $operation['operation'] ?? null;

        if (!$uuidOperation || !Str::isUuid($uuidOperation)) {
            throw new RuntimeException(
                'uuid_operation invalide.'
            );
        }

        if (!$recordUuid || !Str::isUuid($recordUuid)) {
            throw new RuntimeException(
                'record_uuid invalide.'
            );
        }

        $isAllowedTable =
            in_array($table, $this->allowedTables, true) ||
            in_array($table, $this->relationTables, true);

        if (!$isAllowedTable) {
            throw new RuntimeException(
                "Table non autorisée : {$table}"
            );
        }

        if (!in_array($type, ['create', 'update', 'delete'], true)) {
            throw new RuntimeException(
                'Type d’opération invalide.'
            );
        }

        /*
         * Idempotence locale :
         * si cette opération Cloud a déjà été reçue,
         * on ne la rejoue pas.
         */
        $alreadyProcessed = $local
            ->table('sync_operations')
            ->where('uuid_operation', $uuidOperation)
            ->exists();

        if ($alreadyProcessed) {
            return [
                'uuid_operation' => $uuidOperation,
                'table_name' => $table,
                'record_uuid' => $recordUuid,
                'operation' => $type,
                'status' => 'already_processed',
            ];
        }

        return $local->transaction(function () use (
            $local,
            $uuidOperation,
            $table,
            $recordUuid,
            $type,
            $operation
        ) {
            $payload = $operation['payload'] ?? [];

            if (!is_array($payload)) {
                throw new RuntimeException(
                    'Payload invalide.'
                );
            }

            if (($payload['uuid_sync'] ?? $recordUuid) !== $recordUuid) {
                throw new RuntimeException(
                    'Le uuid_sync du payload ne correspond pas au record_uuid.'
                );
            }

            if (in_array($table, $this->relationTables, true)) {
                $result = $this->applyRelationOperation(
                    $local,
                    $table,
                    $recordUuid,
                    $type,
                    $payload
                );
            } else {
                $result = match ($type) {
                    'create' => $this->createRecord(
                        $local,
                        $table,
                        $recordUuid,
                        $payload
                    ),

                    'update' => $this->updateRecord(
                        $local,
                        $table,
                        $recordUuid,
                        $payload
                    ),

                    'delete' => $this->deleteRecord(
                        $local,
                        $table,
                        $recordUuid
                    ),
                };
            }

            /*
             * On conserve une trace de l'opération reçue,
             * mais avec status = synced.
             *
             * Elle ne sera donc PAS reprise par getPendingOperations().
             */
            $local->table('sync_operations')->insert([
                'uuid_operation' => $uuidOperation,
                'source_device_id' => $operation['source_device_id'] ?? null,
                'table_name' => $table,
                'record_uuid' => $recordUuid,
                'operation' => $type,
                'payload' => json_encode($payload),
                'created_at' => $operation['created_at'] ?? now(),
                'synced_at' => now(),
                'status' => 'synced',
                'error_message' => null,
            ]);

            return [
                'uuid_operation' => $uuidOperation,
                'table_name' => $table,
                'record_uuid' => $recordUuid,
                'operation' => $type,
                'status' => $result,
            ];
        });
    }

    /**
     * Reçoit et traite un lot d'opérations.
     */
    public function receive(array $batch): array
    {
        $operations = $batch['operations'] ?? [];

        if (!is_array($operations)) {
            throw new RuntimeException(
                'Le champ operations doit être un tableau.'
            );
        }

        $accepted = [];
        $rejected = [];

        foreach ($operations as $operation) {
            try {
                $accepted[] = $this->applyOperation($operation);
            } catch (\Throwable $e) {
                $rejected[] = [
                    'uuid_operation' => $operation['uuid_operation'] ?? null,
                    'record_uuid' => $operation['record_uuid'] ?? null,
                    'reason' => $e->getMessage(),
                ];
            }
        }

        return [
            'accepted' => $accepted,
            'rejected' => $rejected,
            'accepted_count' => count($accepted),
            'rejected_count' => count($rejected),
        ];
    }

    /**
     * Applique une opération individuelle.
     */
    protected function applyOperation(array $operation): array
    {
        $uuidOperation = $operation['uuid_operation'] ?? null;
        $table = $operation['table_name'] ?? null;
        $recordUuid = $operation['record_uuid'] ?? null;
        $type = $operation['operation'] ?? null;

        if (!$uuidOperation || !Str::isUuid($uuidOperation)) {
            throw new RuntimeException(
                'uuid_operation invalide.'
            );
        }

        if (!$recordUuid || !Str::isUuid($recordUuid)) {
            throw new RuntimeException(
                'record_uuid invalide.'
            );
        }

        $isAllowedTable =
            in_array($table, $this->allowedTables, true) ||
            in_array($table, $this->relationTables, true);

        if (!$isAllowedTable) {
            throw new RuntimeException(
                "Table non autorisée : {$table}"
            );
        }

        if (!in_array($type, ['create', 'update', 'delete'], true)) {
            throw new RuntimeException(
                'Type d’opération invalide.'
            );
        }

        $cloud = $this->cloud();

        /**
         * Idempotence :
         * une même opération ne doit jamais être appliquée deux fois.
         */
        $alreadyProcessed = $cloud
            ->table('sync_operations')
            ->where('uuid_operation', $uuidOperation)
            ->exists();

        if ($alreadyProcessed) {
            return [
                'uuid_operation' => $uuidOperation,
                'table_name' => $table,
                'record_uuid' => $recordUuid,
                'operation' => $type,
                'status' => 'already_processed',
            ];
        }

        return $cloud->transaction(function () use (
            $cloud,
            $uuidOperation,
            $table,
            $recordUuid,
            $type,
            $operation
        ) {
            $payload = $operation['payload'] ?? [];

            if (!is_array($payload)) {
                throw new RuntimeException(
                    'Payload invalide.'
                );
            }

            if (($payload['uuid_sync'] ?? $recordUuid) !== $recordUuid) {
                throw new RuntimeException(
                    'Le uuid_sync du payload ne correspond pas au record_uuid.'
                );
            }

            /*
             * Les tables de relation possèdent des clés primaires
             * composites. Elles utilisent donc une logique dédiée.
             */
            if (in_array($table, $this->relationTables, true)) {
                $result = $this->applyRelationOperation(
                    $cloud,
                    $table,
                    $recordUuid,
                    $type,
                    $payload
                );
            } else {
                $result = match ($type) {
                    'create' => $this->createRecord(
                        $cloud,
                        $table,
                        $recordUuid,
                        $payload
                    ),

                    'update' => $this->updateRecord(
                        $cloud,
                        $table,
                        $recordUuid,
                        $payload
                    ),

                    'delete' => $this->deleteRecord(
                        $cloud,
                        $table,
                        $recordUuid
                    ),
                };
            }

            /*
             * L'opération est enregistrée uniquement après
             * l'application réussie de la modification.
             */
            $cloud->table('sync_operations')->insert([
                'uuid_operation' => $uuidOperation,
                'table_name' => $table,
                'record_uuid' => $recordUuid,
                'operation' => $type,
                'payload' => json_encode($payload),
                'created_at' => now(),
                'synced_at' => now(),
                'status' => 'synced',
                'error_message' => null,
            ]);

            return [
                'uuid_operation' => $uuidOperation,
                'table_name' => $table,
                'record_uuid' => $recordUuid,
                'operation' => $type,
                'status' => $result,
            ];
        });
    }

    /**
     * Applique une opération sur une table de relation.
     */
    protected function applyRelationOperation(
        Connection $cloud,
        string $table,
        string $recordUuid,
        string $type,
        array $payload
    ): string {
        return match ($table) {
            'utilisateurs_roles' => $this->applyUtilisateurRoleOperation(
                $cloud,
                $recordUuid,
                $type,
                $payload
            ),

            'eleves_responsables' => $this->applyEleveResponsableOperation(
                $cloud,
                $recordUuid,
                $type,
                $payload
            ),

            default => throw new RuntimeException(
                "Table de relation non supportée : {$table}"
            ),
        };
    }

    /**
     * Synchronise la relation utilisateurs_roles.
     *
     * La clé primaire de cette table est :
     * (id_utilisateur, id_role)
     */
    protected function applyUtilisateurRoleOperation(
        Connection $cloud,
        string $recordUuid,
        string $type,
        array $payload
    ): string {
        $utilisateurUuid = $payload['utilisateur_uuid'] ?? null;
        $roleUuid = $payload['role_uuid'] ?? null;

        if (!$utilisateurUuid || !Str::isUuid($utilisateurUuid)) {
            throw new RuntimeException(
                'utilisateur_uuid invalide ou manquant.'
            );
        }

        if (!$roleUuid || !Str::isUuid($roleUuid)) {
            throw new RuntimeException(
                'role_uuid invalide ou manquant.'
            );
        }

        $utilisateurId = $cloud
            ->table('utilisateurs')
            ->where('uuid_sync', $utilisateurUuid)
            ->value('id_utilisateur');

        if ($utilisateurId === null) {
            throw new RuntimeException(
                "Utilisateur introuvable dans Cloud avec uuid_sync={$utilisateurUuid}."
            );
        }

        $roleId = $cloud
            ->table('roles')
            ->where('uuid_sync', $roleUuid)
            ->value('id_role');

        if ($roleId === null) {
            throw new RuntimeException(
                "Rôle introuvable dans Cloud avec uuid_sync={$roleUuid}."
            );
        }

        /*
         * Création de la relation.
         */
        if ($type === 'create') {
            /*
             * Cas normal : la relation possède déjà son UUID.
             */
            $existingByUuid = $cloud
                ->table('utilisateurs_roles')
                ->where('uuid_sync', $recordUuid)
                ->exists();

            if ($existingByUuid) {
                return 'already_exists';
            }

            /*
             * Cas de réconciliation :
             * la relation existe déjà dans Cloud mais son uuid_sync
             * est NULL. On lui attribue l'UUID venant du poste local.
             */
            $existingRelation = $cloud
                ->table('utilisateurs_roles')
                ->where('id_utilisateur', $utilisateurId)
                ->where('id_role', $roleId)
                ->first();

            if ($existingRelation) {
                if (
                    empty($existingRelation->uuid_sync) ||
                    $existingRelation->uuid_sync === $recordUuid
                ) {
                    $cloud
                        ->table('utilisateurs_roles')
                        ->where('id_utilisateur', $utilisateurId)
                        ->where('id_role', $roleId)
                        ->update([
                            'uuid_sync' => $recordUuid,
                        ]);

                    return 'reconciled';
                }

                throw new RuntimeException(
                    'La relation utilisateurs_roles existe déjà avec un autre uuid_sync.'
                );
            }

            $cloud
                ->table('utilisateurs_roles')
                ->insert([
                    'id_utilisateur' => $utilisateurId,
                    'id_role' => $roleId,
                    'uuid_sync' => $recordUuid,
                ]);

            return 'created';
        }

        /*
         * Suppression de la relation.
         */
        if ($type === 'delete') {
            $deleted = $cloud
                ->table('utilisateurs_roles')
                ->where('uuid_sync', $recordUuid)
                ->delete();

            if ($deleted > 0) {
                return 'deleted';
            }

            /*
             * Fallback : retrouver la relation avec les UUID
             * des deux côtés si le uuid_sync n'existe pas encore
             * dans Cloud.
             */
            $deleted = $cloud
                ->table('utilisateurs_roles')
                ->where('id_utilisateur', $utilisateurId)
                ->where('id_role', $roleId)
                ->delete();

            if ($deleted > 0) {
                return 'deleted';
            }

            return 'already_deleted';
        }

        /*
         * Les relations n'utilisent pas update().
         */
        throw new RuntimeException(
            "Opération {$type} non supportée pour utilisateurs_roles."
        );
    }

    /**
     * Synchronise la relation eleves_responsables.
     *
     * La clé primaire de cette table est :
     * (id_eleve, id_responsable)
     */
    protected function applyEleveResponsableOperation(
        Connection $cloud,
        string $recordUuid,
        string $type,
        array $payload
    ): string {
        $eleveUuid = $payload['eleve_uuid'] ?? null;
        $responsableUuid = $payload['responsable_uuid'] ?? null;

        if (!$eleveUuid || !Str::isUuid($eleveUuid)) {
            throw new RuntimeException(
                'eleve_uuid invalide ou manquant.'
            );
        }

        if (!$responsableUuid || !Str::isUuid($responsableUuid)) {
            throw new RuntimeException(
                'responsable_uuid invalide ou manquant.'
            );
        }

        $eleveId = $cloud
            ->table('eleves')
            ->where('uuid_sync', $eleveUuid)
            ->value('id_eleve');

        if ($eleveId === null) {
            throw new RuntimeException(
                "Élève introuvable dans Cloud avec uuid_sync={$eleveUuid}."
            );
        }

        $responsableId = $cloud
            ->table('responsables')
            ->where('uuid_sync', $responsableUuid)
            ->value('id_responsable');

        if ($responsableId === null) {
            throw new RuntimeException(
                "Responsable introuvable dans Cloud avec uuid_sync={$responsableUuid}."
            );
        }

        if ($type === 'create') {
            $existingByUuid = $cloud
                ->table('eleves_responsables')
                ->where('uuid_sync', $recordUuid)
                ->exists();

            if ($existingByUuid) {
                return 'already_exists';
            }

            $existingRelation = $cloud
                ->table('eleves_responsables')
                ->where('id_eleve', $eleveId)
                ->where('id_responsable', $responsableId)
                ->first();

            if ($existingRelation) {
                if (
                    empty($existingRelation->uuid_sync) ||
                    $existingRelation->uuid_sync === $recordUuid
                ) {
                    $cloud
                        ->table('eleves_responsables')
                        ->where('id_eleve', $eleveId)
                        ->where('id_responsable', $responsableId)
                        ->update([
                            'uuid_sync' => $recordUuid,
                        ]);

                    /*
                     * Les champs supplémentaires sont conservés
                     * lorsque la relation existait déjà.
                     */
                    $extraData = [];

                    if (array_key_exists('lien_parente', $payload)) {
                        $extraData['lien_parente'] =
                            $payload['lien_parente'];
                    }

                    if (array_key_exists('est_principal', $payload)) {
                        $extraData['est_principal'] =
                            $payload['est_principal'];
                    }

                    if (!empty($extraData)) {
                        $cloud
                            ->table('eleves_responsables')
                            ->where('id_eleve', $eleveId)
                            ->where('id_responsable', $responsableId)
                            ->update($extraData);
                    }

                    return 'reconciled';
                }

                throw new RuntimeException(
                    'La relation eleves_responsables existe déjà avec un autre uuid_sync.'
                );
            }

            $data = [
                'id_eleve' => $eleveId,
                'id_responsable' => $responsableId,
                'uuid_sync' => $recordUuid,
            ];

            if (array_key_exists('lien_parente', $payload)) {
                $data['lien_parente'] = $payload['lien_parente'];
            }

            if (array_key_exists('est_principal', $payload)) {
                $data['est_principal'] = $payload['est_principal'];
            }

            $cloud
                ->table('eleves_responsables')
                ->insert($data);

            return 'created';
        }

        if ($type === 'delete') {
            $deleted = $cloud
                ->table('eleves_responsables')
                ->where('uuid_sync', $recordUuid)
                ->delete();

            if ($deleted > 0) {
                return 'deleted';
            }

            $deleted = $cloud
                ->table('eleves_responsables')
                ->where('id_eleve', $eleveId)
                ->where('id_responsable', $responsableId)
                ->delete();

            if ($deleted > 0) {
                return 'deleted';
            }

            return 'already_deleted';
        }

        throw new RuntimeException(
            "Opération {$type} non supportée pour eleves_responsables."
        );
    }

    /**
     * Création d'un enregistrement métier classique.
     */
    protected function createRecord(
        Connection $cloud,
        string $table,
        string $recordUuid,
        array $payload
    ): string {
        if (
            $cloud->table('sync_tombstones')
                ->where('table_name', $table)
                ->where('record_uuid', $recordUuid)
                ->exists()
        ) {
            throw new RuntimeException(
                "Enregistrement supprimé : {$table} avec uuid_sync={$recordUuid}."
            );
        }

        $primaryKey = $this->primaryKeys[$table] ?? null;

        if (!$primaryKey) {
            throw new RuntimeException(
                "Clé primaire non configurée pour {$table}."
            );
        }

        $existingId = $cloud
            ->table($table)
            ->where('uuid_sync', $recordUuid)
            ->value($primaryKey);

        if ($existingId !== null) {
            return 'already_exists';
        }

        $data = $this->convertPayload(
            $cloud,
            $table,
            $payload
        );

        $data['uuid_sync'] = $recordUuid;

        unset($data[$primaryKey]);

        $cloud
            ->table($table)
            ->insert($data);

        return 'created';
    }

    /**
     * Mise à jour d'un enregistrement métier classique.
     */
    protected function updateRecord(
        Connection $cloud,
        string $table,
        string $recordUuid,
        array $payload
    ): string {
        $primaryKey = $this->primaryKeys[$table] ?? null;

        if (!$primaryKey) {
            throw new RuntimeException(
                "Clé primaire non configurée pour {$table}."
            );
        }

        $id = $cloud
            ->table($table)
            ->where('uuid_sync', $recordUuid)
            ->value($primaryKey);

        if ($id === null) {
            throw new RuntimeException(
                "Enregistrement introuvable dans {$table} avec uuid_sync={$recordUuid}."
            );
        }

        $data = $this->convertPayload(
            $cloud,
            $table,
            $payload
        );

        unset($data[$primaryKey]);
        unset($data['uuid_sync']);

        $cloud
            ->table($table)
            ->where($primaryKey, $id)
            ->update($data);

        return 'updated';
    }

    /**
     * Suppression d'un enregistrement métier classique.
     */
    protected function deleteRecord(
        Connection $cloud,
        string $table,
        string $recordUuid
    ): string {
        $primaryKey = $this->primaryKeys[$table] ?? null;

        if (!$primaryKey) {
            throw new RuntimeException(
                "Clé primaire non configurée pour {$table}."
            );
        }

        $id = $cloud
            ->table($table)
            ->where('uuid_sync', $recordUuid)
            ->value($primaryKey);

        /*
         * Même si l'enregistrement n'existe plus,
         * on conserve sa trace afin d'empêcher sa résurrection.
         */
        $cloud->table('sync_tombstones')->updateOrInsert(
            [
                'table_name' => $table,
                'record_uuid' => $recordUuid,
            ],
            [
                'device_id' => null,
                'deleted_at' => now(),
            ]
        );

        if ($id === null) {
            return 'already_deleted';
        }

        $cloud
            ->table($table)
            ->where($primaryKey, $id)
            ->delete();

        return 'deleted';
    }

    /**
     * Convertit les UUID des relations en identifiants
     * internes de la base Cloud.
     */
    protected function convertPayload(
        Connection $cloud,
        string $table,
        array $payload
    ): array {
        $data = $payload;

        unset($data['id']);

        if (isset($this->primaryKeys[$table])) {
            unset(
                $data[$this->primaryKeys[$table]]
            );
        }

        foreach ($this->foreignKeys[$table] ?? [] as $localField => $config) {
            $uuidField = $config['uuid_field'];

            if (!array_key_exists($uuidField, $data)) {
                continue;
            }

            $uuid = $data[$uuidField];

            unset($data[$uuidField]);

            if ($uuid === null || $uuid === '') {
                $data[$localField] = null;
                continue;
            }

            $primaryKey = $this->primaryKeys[$config['table']] ?? null;

            if (!$primaryKey) {
                throw new RuntimeException(
                    "Clé primaire non configurée pour {$config['table']}."
                );
            }

            $id = $cloud
                ->table($config['table'])
                ->where('uuid_sync', $uuid)
                ->value($primaryKey);

            if ($id === null) {
                throw new RuntimeException(
                    "Relation introuvable : {$config['table']} avec uuid_sync={$uuid}."
                );
            }

            $data[$localField] = $id;
        }

        /*
         * Sécurité supplémentaire :
         * seules les colonnes réellement présentes dans
         * la table cible sont acceptées.
         */
        $columns = $cloud->getSchemaBuilder()
            ->getColumnListing($table);

        return array_intersect_key(
            $data,
            array_flip($columns)
        );
    }
}

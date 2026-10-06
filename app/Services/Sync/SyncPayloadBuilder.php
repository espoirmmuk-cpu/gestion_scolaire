<?php

namespace App\Services\Sync;

use Illuminate\Support\Facades\DB;

class SyncPayloadBuilder
{
    /**
     * Colonnes numériques qui identifient l'enregistrement lui-même
     * ou une relation locale et qui ne doivent pas servir
     * d'identifiant entre Local et Cloud.
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
        'roles' => 'id_role',
        'permissions' => 'id_permission',
    ];

    /**
     * Correspondance entre les FK locales et leur UUID Cloud/Local.
     */
    protected array $foreignKeys = [
        'annees_scolaires' => [
            'id_etablissement' => ['table' => 'etablissements', 'uuid_field' => 'etablissement_uuid'],
        ],

        'classes' => [
            'id_etablissement' => ['table' => 'etablissements', 'uuid_field' => 'etablissement_uuid'],
            'id_annee_scolaire' => ['table' => 'annees_scolaires', 'uuid_field' => 'annee_scolaire_uuid'],
            'id_niveau' => ['table' => 'niveaux', 'uuid_field' => 'niveau_uuid'],
        ],

        'matieres' => [
            'id_etablissement' => ['table' => 'etablissements', 'uuid_field' => 'etablissement_uuid'],
        ],

        'personnel' => [
            'id_etablissement' => ['table' => 'etablissements', 'uuid_field' => 'etablissement_uuid'],
        ],

        'eleves' => [
            'id_etablissement' => ['table' => 'etablissements', 'uuid_field' => 'etablissement_uuid'],
        ],

        'utilisateurs' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'inscriptions' => [
            'id_eleve' => ['table' => 'eleves', 'uuid_field' => 'eleve_uuid'],
            'id_annee_scolaire' => ['table' => 'annees_scolaires', 'uuid_field' => 'annee_scolaire_uuid'],
            'id_classe' => ['table' => 'classes', 'uuid_field' => 'classe_uuid'],
        ],

        'evaluations' => [
            'id_annee_scolaire' => ['table' => 'annees_scolaires', 'uuid_field' => 'annee_scolaire_uuid'],
            'id_classe' => ['table' => 'classes', 'uuid_field' => 'classe_uuid'],
            'id_matiere' => ['table' => 'matieres', 'uuid_field' => 'matiere_uuid'],
            'id_periode' => ['table' => 'periodes_scolaires', 'uuid_field' => 'periode_uuid'],
        ],

        'notes' => [
            'id_evaluation' => ['table' => 'evaluations', 'uuid_field' => 'evaluation_uuid'],
            'id_eleve' => ['table' => 'eleves', 'uuid_field' => 'eleve_uuid'],
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
            'id_eleve' => ['table' => 'eleves', 'uuid_field' => 'eleve_uuid'],
            'id_classe' => ['table' => 'classes', 'uuid_field' => 'classe_uuid'],
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

        'utilisateurs' => [
            'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
        ],

        'journaux_activites' => [
                'id_etablissement' => [
                'table' => 'etablissements',
                'uuid_field' => 'etablissement_uuid',
            ],
            
            'id_utilisateur' => [
                'table' => 'utilisateurs',
                'uuid_field' => 'utilisateur_uuid',
            ],
        ],
    ];

    /**
     * Construit un payload indépendant des IDs locaux.
     */
    public function build(string $table, array $attributes): array
    {
        $payload = $attributes;

        $primaryKey = $this->primaryKeys[$table] ?? null;

        if ($primaryKey) {
            unset($payload[$primaryKey]);
        }

        foreach ($this->foreignKeys[$table] ?? [] as $localField => $config) {
            $value = $attributes[$localField] ?? null;

            unset($payload[$localField]);

            if ($value === null) {
                $payload[$config['uuid_field']] = null;
                continue;
            }

            $payload[$config['uuid_field']] = DB::table($config['table'])
                ->where($this->primaryKeys[$config['table']], $value)
                ->value('uuid_sync');
        }

        return $payload;
    }
}
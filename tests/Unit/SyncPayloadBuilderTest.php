<?php

namespace Tests\Unit;

use App\Services\Sync\SyncPayloadBuilder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SyncPayloadBuilderTest extends TestCase
{
    

    protected SyncPayloadBuilder $builder;

    protected array $relations = [
        'annees_scolaires' => [
            'id_etablissement' => 'etablissement_uuid',
        ],

        'classes' => [
            'id_etablissement' => 'etablissement_uuid',
            'id_annee_scolaire' => 'annee_scolaire_uuid',
            'id_niveau' => 'niveau_uuid',
        ],

        'matieres' => [
            'id_etablissement' => 'etablissement_uuid',
        ],

        'personnel' => [
            'id_etablissement' => 'etablissement_uuid',
        ],

        'eleves' => [
            'id_etablissement' => 'etablissement_uuid',
        ],

        'inscriptions' => [
            'id_eleve' => 'eleve_uuid',
            'id_annee_scolaire' => 'annee_scolaire_uuid',
            'id_classe' => 'classe_uuid',
        ],

        'evaluations' => [
            'id_annee_scolaire' => 'annee_scolaire_uuid',
            'id_classe' => 'classe_uuid',
            'id_matiere' => 'matiere_uuid',
            'id_periode' => 'periode_uuid',
        ],

        'notes' => [
            'id_evaluation' => 'evaluation_uuid',
            'id_eleve' => 'eleve_uuid',
        ],

        'bulletins' => [
            'id_eleve' => 'eleve_uuid',
            'id_annee_scolaire' => 'annee_scolaire_uuid',
            'id_periode' => 'periode_uuid',
            'id_classe' => 'classe_uuid',
        ],

        'details_bulletins' => [
            'id_bulletin' => 'bulletin_uuid',
            'id_matiere' => 'matiere_uuid',
        ],

        'presences' => [
            'id_eleve' => 'eleve_uuid',
            'id_classe' => 'classe_uuid',
        ],

        'tarifs_scolaires' => [
            'id_annee_scolaire' => 'annee_scolaire_uuid',
            'id_classe' => 'classe_uuid',
            'id_categorie_frais' => 'categorie_frais_uuid',
        ],

        'categories_frais' => [
            'id_etablissement' => 'etablissement_uuid',
        ],

        'frais_eleves' => [
            'id_eleve' => 'eleve_uuid',
            'id_inscription' => 'inscription_uuid',
            'id_tarif' => 'tarif_uuid',
        ],

        'paiements' => [
            'id_eleve' => 'eleve_uuid',
            'id_utilisateur' => 'utilisateur_uuid',
        ],

        'details_paiements' => [
            'id_paiement' => 'paiement_uuid',
            'id_frais_eleve' => 'frais_eleve_uuid',
        ],

        'recettes' => [
            'id_paiement' => 'paiement_uuid',
            'id_etablissement' => 'etablissement_uuid',
            'id_annee_scolaire' => 'annee_scolaire_uuid',
            'id_utilisateur' => 'utilisateur_uuid',
        ],

        'depenses' => [
            'id_etablissement' => 'etablissement_uuid',
            'id_annee_scolaire' => 'annee_scolaire_uuid',
            'id_utilisateur' => 'utilisateur_uuid',
        ],

        'periodes_scolaires' => [
            'id_annee_scolaire' => 'annee_scolaire_uuid',
        ],

        'activites' => [
            'id_annee_scolaire' => 'annee_scolaire_uuid',
        ],

        'infrastructures' => [
            'id_etablissement' => 'etablissement_uuid',
        ],

        'classes_matieres' => [
            'id_classe' => 'classe_uuid',
            'id_matiere' => 'matiere_uuid',
            'id_enseignant' => 'enseignant_uuid',
        ],

        'affectations_enseignants' => [
            'id_etablissement' => 'etablissement_uuid',
            'id_enseignant' => 'enseignant_uuid',
            'id_classe' => 'classe_uuid',
            'id_matiere' => 'matiere_uuid',
            'id_annee_scolaire' => 'annee_scolaire_uuid',
        ],

        'inventaire' => [
            'id_etablissement' => 'etablissement_uuid',
            'id_categorie' => 'categorie_inventaire_uuid',
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.port' => 3306,
            'database.connections.mysql.database' => 'gesco_local',
            'database.connections.mysql.username' => 'root',
            'database.connections.mysql.password' => '',
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');

        $this->builder = app(SyncPayloadBuilder::class);
    }

    public function test_toutes_les_relations_sont_converties_en_uuid(): void
    {
        foreach ($this->relations as $table => $foreignKeys) {
            $record = DB::table($table)->first();

            if (!$record) {
                $this->addToAssertionCount(1);

                continue;
            }

            $payload = $this->builder->build(
                $table,
                (array) $record
            );

            foreach ($foreignKeys as $localField => $uuidField) {
                $this->assertArrayNotHasKey(
                    $localField,
                    $payload,
                    "Le champ {$localField} ne doit pas être présent dans le payload de {$table}."
                );

                $this->assertArrayHasKey(
                    $uuidField,
                    $payload,
                    "Le champ {$uuidField} manque dans le payload de {$table}."
                );
            }
        }
    }

    public function test_les_ids_primaires_ne_sont_pas_envoyes(): void
    {
        $primaryKeys = [
            'annees_scolaires' => 'id_annee_scolaire',
            'classes' => 'id_classe',
            'matieres' => 'id_matiere',
            'personnel' => 'id_personnel',
            'eleves' => 'id_eleve',
            'inscriptions' => 'id_inscription',
            'evaluations' => 'id_evaluation',
            'notes' => 'id_note',
            'bulletins' => 'id_bulletin',
            'details_bulletins' => 'id_detail',
            'presences' => 'id_presence',
            'tarifs_scolaires' => 'id_tarif',
            'categories_frais' => 'id_categorie_frais',
            'frais_eleves' => 'id_frais_eleve',
            'paiements' => 'id_paiement',
            'details_paiements' => 'id_detail_paiement',
            'recettes' => 'id_recette',
            'depenses' => 'id_depense',
            'periodes_scolaires' => 'id_periode',
            'activites' => 'id_activite',
            'infrastructures' => 'id_infrastructure',
            'classes_matieres' => 'id_classe_matiere',
            'affectations_enseignants' => 'id_affectation',
            'inventaire' => 'id_inventaire',
        ];

        foreach ($primaryKeys as $table => $primaryKey) {
            $record = DB::table($table)->first();

            if (!$record) {
                $this->addToAssertionCount(1);

                continue;
            }

            $payload = $this->builder->build(
                $table,
                (array) $record
            );

            $this->assertArrayNotHasKey(
                $primaryKey,
                $payload,
                "La clé primaire {$primaryKey} ne doit pas être envoyée pour {$table}."
            );
        }
    }
}
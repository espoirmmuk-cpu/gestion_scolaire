<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateSyncUuids extends Command
{
    protected $signature = 'sync:generate-uuids';

    protected $description = 'Génère les UUID de synchronisation pour les enregistrements existants';

    public function handle(): int
    {
        $tables = [
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

        foreach ($tables as $table => $primaryKey) {
            $count = 0;

            $records = DB::table($table)
                ->whereNull('uuid_sync')
                ->get();

            foreach ($records as $record) {
                DB::table($table)
                    ->where($primaryKey, $record->{$primaryKey})
                    ->update([
                        'uuid_sync' => (string) Str::uuid(),
                    ]);

                $count++;
            }

            $this->info("{$table} : {$count} UUID généré(s).");
        }

        $this->newLine();
        $this->info('Génération des UUID terminée.');

        return self::SUCCESS;
    }
}
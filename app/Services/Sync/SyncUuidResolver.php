<?php

namespace App\Services\Sync;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class SyncUuidResolver
{
    /**
     * Recherche l'ID local d'un enregistrement à partir de son UUID de synchronisation.
     */
    public function resolveId(string $table, string $uuid): int
    {
        $allowedTables = [
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
        ];

        if (!in_array($table, $allowedTables, true)) {
            throw new RuntimeException(
                "Table non autorisée pour la résolution UUID : {$table}"
            );
        }

        $primaryKeys = [
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
            'details_bulletins' => 'id_detail_bulletin',
            'presences' => 'id_presence',
            'activites' => 'id_activite',
            'depenses' => 'id_depense',
            'recettes' => 'id_recette',
            'infrastructures' => 'id_infrastructure',
            'inventaire' => 'id_inventaire',
            'classes_matieres' => 'id_classe_matiere',
            'affectations_enseignants' => 'id_affectation',
            'utilisateurs' => 'id_utilisateur',
        ];

        $primaryKey = $primaryKeys[$table];

        $id = DB::table($table)
            ->where('uuid_sync', $uuid)
            ->value($primaryKey);

        if ($id === null) {
            throw new RuntimeException(
                "Aucun enregistrement trouvé dans {$table} avec uuid_sync={$uuid}"
            );
        }

        return (int) $id;
    }

    /**
     * Vérifie simplement si un UUID existe dans une table.
     */
    public function exists(string $table, string $uuid): bool
    {
        return $this->findId($table, $uuid) !== null;
    }

    /**
     * Retourne l'ID ou null si l'UUID n'existe pas.
     */
    public function findId(string $table, string $uuid): ?int
    {
        $allowedTables = [
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
        ];

        if (!in_array($table, $allowedTables, true)) {
            return null;
        }

        $primaryKeys = [
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
            'details_bulletins' => 'id_detail_bulletin',
            'presences' => 'id_presence',
            'activites' => 'id_activite',
            'depenses' => 'id_depense',
            'recettes' => 'id_recette',
            'infrastructures' => 'id_infrastructure',
            'inventaire' => 'id_inventaire',
            'classes_matieres' => 'id_classe_matiere',
            'affectations_enseignants' => 'id_affectation',
            'utilisateurs' => 'id_utilisateur',
        ];

        return DB::table($table)
            ->where('uuid_sync', $uuid)
            ->value($primaryKeys[$table]);
    }
}
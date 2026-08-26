<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RapportMensuelExport implements
    Export,
    FromCollection,
    WithHeadings,
    ShouldAutoSize
{
    protected string $mois;

    public function __construct(string $mois)
    {
        $this->mois = $mois;
    }

    /**
     * Retourne les données du rapport mensuel.
     */
    public function collection(): Collection
    {
        /*
        |--------------------------------------------------------------------------
        | Année scolaire active
        |--------------------------------------------------------------------------
        */

        $annee = DB::table('annees_scolaires')
            ->where('est_active', 1)
            ->first();

        if (!$annee) {
            return collect();
        }


        /*
        |--------------------------------------------------------------------------
        | Dates du mois
        |--------------------------------------------------------------------------
        */

        $dateDebut = $this->mois . '-01';

        $dateFin = date(
            'Y-m-t',
            strtotime($dateDebut)
        );


        /*
        |--------------------------------------------------------------------------
        | Effectifs par classe
        |--------------------------------------------------------------------------
        */

        $effectifs = DB::table('inscriptions')
            ->join(
                'eleves',
                'eleves.id_eleve',
                '=',
                'inscriptions.id_eleve'
            )
            ->join(
                'classes',
                'classes.id_classe',
                '=',
                'inscriptions.id_classe'
            )
            ->where(
                'inscriptions.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->select(
                'classes.libelle',
                'classes.option_classe',

                DB::raw("
                    SUM(
                        CASE
                            WHEN UPPER(TRIM(eleves.sexe)) = 'M'
                            THEN 1
                            ELSE 0
                        END
                    ) AS garcons
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN UPPER(TRIM(eleves.sexe)) = 'F'
                            THEN 1
                            ELSE 0
                        END
                    ) AS filles
                "),

                DB::raw("
                    COUNT(inscriptions.id_inscription) AS total
                ")
            )
            ->groupBy(
                'classes.id_classe',
                'classes.libelle',
                'classes.option_classe'
            )
            ->orderBy('classes.libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Ligne de synthèse
        |--------------------------------------------------------------------------
        */

        $resultats = collect();


        foreach ($effectifs as $classe) {

            $resultats->push([
                $classe->libelle,
                $classe->option_classe ?? '-',
                (int) $classe->garcons,
                (int) $classe->filles,
                (int) $classe->total,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Totaux
        |--------------------------------------------------------------------------
        */

        $totalGarcons = $effectifs->sum(
            fn ($item) => (int) $item->garcons
        );

        $totalFilles = $effectifs->sum(
            fn ($item) => (int) $item->filles
        );

        $totalEleves = $effectifs->sum(
            fn ($item) => (int) $item->total
        );


        /*
        |--------------------------------------------------------------------------
        | Total général
        |--------------------------------------------------------------------------
        */

        $resultats->push([
            'TOTAL GÉNÉRAL',
            '',
            $totalGarcons,
            $totalFilles,
            $totalEleves,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Ligne vide
        |--------------------------------------------------------------------------
        */

        $resultats->push([
            '',
            '',
            '',
            '',
            '',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Statistiques mensuelles
        |--------------------------------------------------------------------------
        */

        $nombreInscriptions = DB::table('inscriptions')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'date_inscription',
                [$dateDebut, $dateFin]
            )
            ->count();


        $nombrePresences = DB::table('presences')
            ->join(
                'inscriptions',
                'inscriptions.id_eleve',
                '=',
                'presences.id_eleve'
            )
            ->where(
                'inscriptions.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'presences.date_presence',
                [$dateDebut, $dateFin]
            )
            ->whereRaw(
                "UPPER(TRIM(presences.statut)) = 'PRESENT'"
            )
            ->count();


        $nombreAbsences = DB::table('presences')
            ->join(
                'inscriptions',
                'inscriptions.id_eleve',
                '=',
                'presences.id_eleve'
            )
            ->where(
                'inscriptions.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'presences.date_presence',
                [$dateDebut, $dateFin]
            )
            ->whereRaw(
                "UPPER(TRIM(presences.statut)) = 'ABSENT'"
            )
            ->count();


        $nombreEvaluations = DB::table('evaluations')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'date_evaluation',
                [$dateDebut, $dateFin]
            )
            ->count();


        $nombreNotes = DB::table('notes')
            ->join(
                'evaluations',
                'evaluations.id_evaluation',
                '=',
                'notes.id_evaluation'
            )
            ->where(
                'evaluations.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'evaluations.date_evaluation',
                [$dateDebut, $dateFin]
            )
            ->count();


        $moyenneNotes = DB::table('notes')
            ->join(
                'evaluations',
                'evaluations.id_evaluation',
                '=',
                'notes.id_evaluation'
            )
            ->where(
                'evaluations.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'evaluations.date_evaluation',
                [$dateDebut, $dateFin]
            )
            ->avg('notes.note');

        $moyenneNotes = $moyenneNotes !== null
            ? round($moyenneNotes, 2)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Paiements
        |--------------------------------------------------------------------------
        */

        $nombrePaiements = DB::table('paiements')
            ->join(
                'inscriptions',
                'inscriptions.id_eleve',
                '=',
                'paiements.id_eleve'
            )
            ->where(
                'inscriptions.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'paiements.date_paiement',
                [$dateDebut, $dateFin]
            )
            ->distinct()
            ->count('paiements.id_paiement');


        /*
        |--------------------------------------------------------------------------
        | Recettes
        |--------------------------------------------------------------------------
        */

        $totalRecettes = DB::table('recettes')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'date_recette',
                [$dateDebut, $dateFin]
            )
            ->sum('montant');


        /*
        |--------------------------------------------------------------------------
        | Dépenses
        |--------------------------------------------------------------------------
        */

        $totalDepenses = DB::table('depenses')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereBetween(
                'date_depense',
                [$dateDebut, $dateFin]
            )
            ->sum('montant');


        $solde = $totalRecettes - $totalDepenses;


        /*
        |--------------------------------------------------------------------------
        | Statistiques
        |--------------------------------------------------------------------------
        */

        $resultats->push([
            'STATISTIQUES DU MOIS',
            '',
            '',
            '',
            '',
        ]);

        $resultats->push([
            'Élèves inscrits',
            '',
            $totalEleves,
            '',
            '',
        ]);

        $resultats->push([
            'Nouvelles inscriptions',
            '',
            $nombreInscriptions,
            '',
            '',
        ]);

        $resultats->push([
            'Présences',
            '',
            $nombrePresences,
            '',
            '',
        ]);

        $resultats->push([
            'Absences',
            '',
            $nombreAbsences,
            '',
            '',
        ]);

        $resultats->push([
            'Évaluations',
            '',
            $nombreEvaluations,
            '',
            '',
        ]);

        $resultats->push([
            'Notes enregistrées',
            '',
            $nombreNotes,
            '',
            '',
        ]);

        $resultats->push([
            'Moyenne générale',
            '',
            $moyenneNotes,
            '',
            '',
        ]);

        $resultats->push([
            'Paiements',
            '',
            $nombrePaiements,
            '',
            '',
        ]);

        $resultats->push([
            'Total recettes',
            '',
            $totalRecettes,
            '',
            '',
        ]);

        $resultats->push([
            'Total dépenses',
            '',
            $totalDepenses,
            '',
            '',
        ]);

        $resultats->push([
            'Solde',
            '',
            $solde,
            '',
            '',
        ]);


        return $resultats;
    }


    /**
     * En-têtes Excel.
     */
    public function headings(): array
    {
        return [
            'Classe / Statistique',
            'Option',
            'Garçons / Valeur',
            'Filles',
            'Total',
        ];
    }
}
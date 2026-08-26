<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PalmaresExport;
use App\Exports\EnseignantsExport;

class RapportController extends Controller
{
    /**
     * Page principale des rapports.
     */
    public function index()
    {
        return view('rapports.index');
    }


    /**
     * Retourne l'établissement de l'utilisateur connecté.
     * NULL = administrateur global, comme dans les autres contrôleurs.
     */
    private function etablissementCourant()
    {
        return auth()->user()->id_etablissement;
    }

    /**
     * Retourne uniquement les années scolaires accessibles à l'utilisateur.
     */
    private function anneesAccessibles()
    {
        $query = DB::table('annees_scolaires');

        if ($this->etablissementCourant() !== null) {
            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        return $query
            ->orderByDesc('date_debut')
            ->get();
    }

    /**
     * Vérifie qu'une année scolaire appartient à l'établissement courant.
     */
    private function anneeScolaireAccessible($idAnnee = null, $active = false)
    {
        $query = DB::table('annees_scolaires');

        if ($idAnnee !== null) {
            $query->where(
                'id_annee_scolaire',
                $idAnnee
            );
        }

        if ($active) {
            $query->where('est_active', 1);
        }

        if ($this->etablissementCourant() !== null) {
            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $annee = $query->first();

        if (!$annee) {
            abort(403, 'Vous n’avez pas accès à cette année scolaire.');
        }

        return $annee;
    }

    /**
     * Vérifie qu'un élève appartient à l'établissement courant.
     */
    private function eleveAccessible($idEleve)
    {
        $query = DB::table('eleves')
            ->where('id_eleve', $idEleve);

        if ($this->etablissementCourant() !== null) {
            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $eleve = $query->first();

        if (!$eleve) {
            abort(403, 'Vous n’avez pas accès à cet élève.');
        }

        return $eleve;
    }

    /**
     * Vérifie qu'une classe appartient à l'établissement courant et à l'année.
     */
    private function classeAccessible($idClasse, $idAnnee = null)
    {
        $query = DB::table('classes')
            ->where('id_classe', $idClasse);

        if ($idAnnee !== null) {
            $query->where(
                'id_annee_scolaire',
                $idAnnee
            );
        }

        if ($this->etablissementCourant() !== null) {
            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $classe = $query->first();

        if (!$classe) {
            abort(403, 'Vous n’avez pas accès à cette classe.');
        }

        return $classe;
    }


    /**
     * ==========================================================================
     * DONNÉES COMMUNES DU RAPPORT ANNUEL
     * ==========================================================================
     *
     * Toutes les versions du rapport annuel :
     * - aperçu
     * - impression
     * - PDF
     *
     * utilisent exactement les mêmes données.
     */
    private function getRapportAnnuelData()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. ANNÉE SCOLAIRE ACTIVE
        |--------------------------------------------------------------------------
        */

        $annee = $this->anneeScolaireAccessible(null, true);

        if (!$annee) {
            abort(404, 'Aucune année scolaire active.');
        }


        /*
        |--------------------------------------------------------------------------
        | 2. INFORMATIONS DE L'ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        $etablissement = DB::table('etablissements')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | 3. EFFECTIF PAR CLASSE ET PAR SEXE
        |--------------------------------------------------------------------------
        */

        $effectifsClasses = DB::table('inscriptions')
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
                'classes.id_classe',
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
        | 4. EFFECTIF TOTAL DES ÉLÈVES
        |--------------------------------------------------------------------------
        */

        $nombreEleves = DB::table('inscriptions')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 5. TOTAL GARÇONS
        |--------------------------------------------------------------------------
        */

        $nombreGarcons = DB::table('inscriptions')
            ->join(
                'eleves',
                'eleves.id_eleve',
                '=',
                'inscriptions.id_eleve'
            )
            ->where(
                'inscriptions.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereRaw(
                "UPPER(TRIM(eleves.sexe)) = 'M'"
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 6. TOTAL FILLES
        |--------------------------------------------------------------------------
        */

        $nombreFilles = DB::table('inscriptions')
            ->join(
                'eleves',
                'eleves.id_eleve',
                '=',
                'inscriptions.id_eleve'
            )
            ->where(
                'inscriptions.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereRaw(
                "UPPER(TRIM(eleves.sexe)) = 'F'"
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 7. NOMBRE DE CLASSES
        |--------------------------------------------------------------------------
        */

        $nombreClasses = DB::table('classes')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 8. PERSONNEL
        |--------------------------------------------------------------------------
        */

        $personnel = DB::table('personnel')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->orderBy('fonction')
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 9. NOMBRE D'ENSEIGNANTS
        |--------------------------------------------------------------------------
        */

        $nombreEnseignants = DB::table('personnel')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->whereRaw(
                "UPPER(TRIM(fonction)) LIKE '%ENSEIGNANT%'"
            )
            ->whereRaw(
                "UPPER(TRIM(statut)) IN ('ACTIF', 'ACTIVE')"
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 10. AUTRES MEMBRES DU PERSONNEL
        |--------------------------------------------------------------------------
        */

        $nombreAutrePersonnel = DB::table('personnel')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->whereRaw(
                "UPPER(TRIM(fonction)) NOT LIKE '%ENSEIGNANT%'"
            )
            ->whereRaw(
                "UPPER(TRIM(statut)) IN ('ACTIF', 'ACTIVE')"
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 11. INSCRIPTIONS
        |--------------------------------------------------------------------------
        */

        $nombreInscriptions = DB::table('inscriptions')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->whereRaw(
                "UPPER(TRIM(statut)) = 'INSCRIT'"
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 12. FRÉQUENTATION
        |--------------------------------------------------------------------------
        */

        $frequentation = DB::table('presences')
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
            ->select(
                'presences.statut',
                DB::raw('COUNT(*) AS total')
            )
            ->groupBy('presences.statut')
            ->orderBy('presences.statut')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 13. TOTAL DES PRÉSENCES
        |--------------------------------------------------------------------------
        */

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
            ->whereRaw(
                "UPPER(TRIM(presences.statut)) = 'PRESENT'"
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 14. TOTAL DES ABSENCES
        |--------------------------------------------------------------------------
        */

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
            ->whereRaw(
                "UPPER(TRIM(presences.statut)) = 'ABSENT'"
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 15. ÉVALUATIONS
        |--------------------------------------------------------------------------
        */

        $nombreEvaluations = DB::table('evaluations')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 16. NOTES
        |--------------------------------------------------------------------------
        */

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
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 17. MOYENNE GÉNÉRALE
        |--------------------------------------------------------------------------
        */

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
            ->avg('notes.note');

        $moyenneNotes = $moyenneNotes !== null
            ? round($moyenneNotes, 2)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | 18. PAIEMENTS
        |--------------------------------------------------------------------------
        */

        $paiements = DB::table('paiements')
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
            ->select(
                'paiements.devise',
                DB::raw(
                    'COUNT(DISTINCT paiements.id_paiement) AS nombre'
                ),
                DB::raw(
                    'SUM(paiements.montant_total) AS total'
                )
            )
            ->groupBy('paiements.devise')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 19. NOMBRE DE PAIEMENTS
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
            ->distinct()
            ->count('paiements.id_paiement');


        /*
        |--------------------------------------------------------------------------
        | 20. RECETTES
        |--------------------------------------------------------------------------
        */

        $recettes = DB::table('recettes')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->select(
                'devise',
                DB::raw('SUM(montant) AS total')
            )
            ->groupBy('devise')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 21. DÉPENSES
        |--------------------------------------------------------------------------
        */

        $depenses = DB::table('depenses')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->select(
                'devise',
                DB::raw('SUM(montant) AS total')
            )
            ->groupBy('devise')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 22. TOTAUX FINANCIERS
        |--------------------------------------------------------------------------
        */

        $totalRecettes = $recettes->sum('total');

        $totalDepenses = $depenses->sum('total');

        /*
         * Attention :
         * si plusieurs devises sont utilisées, ces totaux correspondent
         * à la somme des montants sans conversion de devise.
         */
        $solde = $totalRecettes - $totalDepenses;


        /*
        |--------------------------------------------------------------------------
        | 23. INVENTAIRE
        |--------------------------------------------------------------------------
        */

        $inventaire = DB::table('inventaire')
            ->leftJoin(
                'categories_inventaire',
                'categories_inventaire.id_categorie',
                '=',
                'inventaire.id_categorie'
            )
            ->where(
                'inventaire.id_etablissement',
                $annee->id_etablissement
            )
            ->select(
                'categories_inventaire.libelle AS categorie',
                DB::raw(
                    'COUNT(inventaire.id_inventaire) AS nombre'
                ),
                DB::raw(
                    'SUM(inventaire.quantite) AS quantite'
                )
            )
            ->groupBy(
                'categories_inventaire.id_categorie',
                'categories_inventaire.libelle'
            )
            ->orderBy(
                'categories_inventaire.libelle'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 24. TOTAL DES BIENS
        |--------------------------------------------------------------------------
        */

        $nombreBiens = DB::table('inventaire')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | 25. QUANTITÉ TOTALE DES BIENS
        |--------------------------------------------------------------------------
        */

        $quantiteBiens = DB::table('inventaire')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->sum('quantite');


        /*
        |--------------------------------------------------------------------------
        | 26. ÉTATS DE L'INVENTAIRE
        |--------------------------------------------------------------------------
        */

        $etatsInventaire = DB::table('inventaire')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->select(
                'etat',
                DB::raw('COUNT(*) AS nombre')
            )
            ->groupBy('etat')
            ->orderBy('etat')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 27. RÉSULTATS PAR CLASSE
        |--------------------------------------------------------------------------
        */

        $resultatsClasses = DB::table('notes')
            ->join(
                'evaluations',
                'evaluations.id_evaluation',
                '=',
                'notes.id_evaluation'
            )
            ->join(
                'classes',
                'classes.id_classe',
                '=',
                'evaluations.id_classe'
            )
            ->where(
                'evaluations.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->select(
                'classes.id_classe',
                'classes.libelle',
                DB::raw(
                    'COUNT(notes.id_note) AS nombre_notes'
                ),
                DB::raw(
                    'ROUND(AVG(notes.note), 2) AS moyenne'
                )
            )
            ->groupBy(
                'classes.id_classe',
                'classes.libelle'
            )
            ->orderBy('classes.libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 28. ÉLÈVES AYANT DES NOTES
        |--------------------------------------------------------------------------
        */

        $elevesAvecNotes = DB::table('notes')
            ->join(
                'evaluations',
                'evaluations.id_evaluation',
                '=',
                'notes.id_evaluation'
            )
            ->join(
                'inscriptions',
                'inscriptions.id_eleve',
                '=',
                'notes.id_eleve'
            )
            ->where(
                'evaluations.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->where(
                'inscriptions.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->distinct()
            ->count('notes.id_eleve');


        /*
        |--------------------------------------------------------------------------
        | 29. RETOUR DE TOUTES LES DONNÉES
        |--------------------------------------------------------------------------
        */
$matieresQuery = DB::table('matieres');

        if ($this->etablissementCourant() !== null) {
            $matieresQuery->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $nombreMatieres = $matieresQuery->count();

        return compact(
            'etablissement',
            'annee',

            // Élèves
            'nombreEleves',
            'nombreGarcons',
            'nombreFilles',
            'nombreClasses',
            'effectifsClasses',
            'nombreMatieres',

            // Personnel
            'personnel',
            'nombreEnseignants',
            'nombreAutrePersonnel',

            // Inscriptions
            'nombreInscriptions',

            // Fréquentation
            'frequentation',
            'nombrePresences',
            'nombreAbsences',

            // Résultats scolaires
            'nombreEvaluations',
            'nombreNotes',
            'moyenneNotes',
            'resultatsClasses',
            'elevesAvecNotes',

            // Finances
            'paiements',
            'nombrePaiements',
            'recettes',
            'depenses',
            'totalRecettes',
            'totalDepenses',
            'solde',

            // Inventaire
            'inventaire',
            'nombreBiens',
            'quantiteBiens',
            'etatsInventaire'
        );
    }


    /**
     * ==========================================================================
     * RAPPORT ANNUEL
     * ==========================================================================
     */
    public function annuel()
    {
        $data = $this->getRapportAnnuelData();

        return view(
            'rapports.annuel',
            $data
        );
    }


    /**
     * ==========================================================================
     * RAPPORT ANNUEL PDF
     * ==========================================================================
     */
    public function annuelPdf()
    {
        $data = $this->getRapportAnnuelData();

        $pdf = Pdf::loadView(
            'rapports.pdf.annuel',
            $data
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->download(
            'rapport-annuel-' .
            $data['annee']->libelle .
            '.pdf'
        );
    }


    /**
     * ==========================================================================
     * RAPPORT ANNUEL À IMPRIMER
     * ==========================================================================
     */
    public function annuelImprimer()
    {
        $data = $this->getRapportAnnuelData();

        return view(
            'rapports.imprimer.annuel',
            $data
        );
    }


    /**
     * ==========================================================================
     * RAPPORT ANNUEL EXCEL
     * ==========================================================================
     */
    public function annuelExcel()
    {
        $annee = $this->anneeScolaireAccessible(null, true);

        if (!$annee) {
            abort(404, 'Aucune année scolaire active.');
        }

        return Excel::download(
            new \App\Exports\RapportAnnuelExport(
                $annee->id_annee_scolaire
            ),
            'rapport-annuel-' .
            $annee->libelle .
            '.xlsx'
        );
    }


    /**
     * ==========================================================================
     * RAPPORT MENSUEL
     * ==========================================================================
     */
/*
|--------------------------------------------------------------------------
| RAPPORT MENSUEL
|--------------------------------------------------------------------------
*/

/**
 * Récupère toutes les données du rapport mensuel.
 */
private function getRapportMensuelData($mois = null)
{
    /*
    |--------------------------------------------------------------------------
    | 1. ANNÉE SCOLAIRE ACTIVE
    |--------------------------------------------------------------------------
    */

    $annee = $this->anneeScolaireAccessible(null, true);

    if (!$annee) {
        abort(404, 'Aucune année scolaire active.');
    }


    /*
    |--------------------------------------------------------------------------
    | 2. MOIS
    |--------------------------------------------------------------------------
    |
    | Si aucun mois n'est fourni, on utilise le mois actuel.
    |
    */

    $mois = $mois ?: now()->format('Y-m');


    /*
    |--------------------------------------------------------------------------
    | 3. VALIDATION DU MOIS
    |--------------------------------------------------------------------------
    */

    if (!preg_match('/^\d{4}-\d{2}$/', $mois)) {
        abort(400, 'Format de mois invalide.');
    }


    /*
    |--------------------------------------------------------------------------
    | 4. DATES DU MOIS
    |--------------------------------------------------------------------------
    */

    $dateDebut = $mois . '-01';

    $dateFin = date(
        'Y-m-t',
        strtotime($dateDebut)
    );


    /*
    |--------------------------------------------------------------------------
    | 5. INFORMATIONS DE L'ÉTABLISSEMENT
    |--------------------------------------------------------------------------
    */

    $etablissement = DB::table('etablissements')
        ->where(
            'id_etablissement',
            $annee->id_etablissement
        )
        ->first();


    /*
    |--------------------------------------------------------------------------
    | 6. EFFECTIF GLOBAL
    |--------------------------------------------------------------------------
    */

    $nombreEleves = DB::table('inscriptions')
        ->where(
            'id_annee_scolaire',
            $annee->id_annee_scolaire
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 7. GARÇONS
    |--------------------------------------------------------------------------
    */

    $nombreGarcons = DB::table('inscriptions')
        ->join(
            'eleves',
            'eleves.id_eleve',
            '=',
            'inscriptions.id_eleve'
        )
        ->where(
            'inscriptions.id_annee_scolaire',
            $annee->id_annee_scolaire
        )
        ->whereRaw(
            "UPPER(TRIM(eleves.sexe)) = 'M'"
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 8. FILLES
    |--------------------------------------------------------------------------
    */

    $nombreFilles = DB::table('inscriptions')
        ->join(
            'eleves',
            'eleves.id_eleve',
            '=',
            'inscriptions.id_eleve'
        )
        ->where(
            'inscriptions.id_annee_scolaire',
            $annee->id_annee_scolaire
        )
        ->whereRaw(
            "UPPER(TRIM(eleves.sexe)) = 'F'"
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 9. EFFECTIF PAR CLASSE
    |--------------------------------------------------------------------------
    */

    $effectifsClasses = DB::table('inscriptions')
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
            'classes.id_classe',
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
    | 10. INSCRIPTIONS DU MOIS
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


    /*
    |--------------------------------------------------------------------------
    | 11. PRÉSENCES DU MOIS
    |--------------------------------------------------------------------------
    */

    $frequentation = DB::table('presences')
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
        ->select(
            'presences.statut',
            DB::raw('COUNT(*) AS total')
        )
        ->groupBy('presences.statut')
        ->orderBy('presences.statut')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | 12. TOTAL PRÉSENCES
    |--------------------------------------------------------------------------
    */

$nombrePresences = $frequentation
        ->filter(function ($ligne) {
            return strtoupper(trim($ligne->statut)) === 'PRESENT';
        })
        ->sum('total');

    $nombreAbsences = $frequentation
        ->filter(function ($ligne) {
            return strtoupper(trim($ligne->statut)) === 'ABSENT';
        })
        ->sum('total');


    /*
    |--------------------------------------------------------------------------
    | 14. TAUX DE FRÉQUENTATION
    |--------------------------------------------------------------------------
    */

    $totalPresenceAbsence =
        $nombrePresences + $nombreAbsences;

    $tauxFrequentation =
        $totalPresenceAbsence > 0
            ? round(
                ($nombrePresences / $totalPresenceAbsence) * 100,
                2
            )
            : 0;


    /*
    |--------------------------------------------------------------------------
    | 15. ÉVALUATIONS DU MOIS
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | 16. NOTES DU MOIS
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | 17. MOYENNE DES NOTES DU MOIS
    |--------------------------------------------------------------------------
    */

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
    | 18. PAIEMENTS DU MOIS
    |--------------------------------------------------------------------------
    */

    $paiements = DB::table('paiements')
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
        ->select(
            'paiements.devise',
            DB::raw(
                'COUNT(DISTINCT paiements.id_paiement) AS nombre'
            ),
            DB::raw(
                'SUM(paiements.montant_total) AS total'
            )
        )
        ->groupBy('paiements.devise')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | 19. NOMBRE DE PAIEMENTS
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
    | 20. RECETTES DU MOIS
    |--------------------------------------------------------------------------
    */

    $recettes = DB::table('recettes')
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
        ->select(
            'devise',
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('devise')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | 21. DÉPENSES DU MOIS
    |--------------------------------------------------------------------------
    */

    $depenses = DB::table('depenses')
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
        ->select(
            'devise',
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('devise')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | 22. TOTAUX FINANCIERS
    |--------------------------------------------------------------------------
    */

    $totalRecettes = $recettes->sum('total');

    $totalDepenses = $depenses->sum('total');

    $solde = $totalRecettes - $totalDepenses;


    /*
    |--------------------------------------------------------------------------
    | 23. PERSONNEL
    |--------------------------------------------------------------------------
    */

    $personnel = DB::table('personnel')
        ->where(
            'id_etablissement',
            $annee->id_etablissement
        )
        ->orderBy('fonction')
        ->orderBy('nom')
        ->orderBy('postnom')
        ->orderBy('prenom')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | 24. NOMBRE D'ENSEIGNANTS
    |--------------------------------------------------------------------------
    */

    $nombreEnseignants = DB::table('personnel')
        ->where(
            'id_etablissement',
            $annee->id_etablissement
        )
        ->whereRaw(
            "UPPER(TRIM(fonction)) LIKE '%ENSEIGNANT%'"
        )
        ->whereRaw(
            "UPPER(TRIM(statut)) IN ('ACTIF', 'ACTIVE')"
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 25. AUTRE PERSONNEL
    |--------------------------------------------------------------------------
    */

    $nombreAutrePersonnel = DB::table('personnel')
        ->where(
            'id_etablissement',
            $annee->id_etablissement
        )
        ->whereRaw(
            "UPPER(TRIM(fonction)) NOT LIKE '%ENSEIGNANT%'"
        )
        ->whereRaw(
            "UPPER(TRIM(statut)) IN ('ACTIF', 'ACTIVE')"
        )
        ->count();


    /*
    |--------------------------------------------------------------------------
    | 26. RÉSULTATS PAR CLASSE
    |--------------------------------------------------------------------------
    */

    $resultatsClasses = DB::table('notes')
        ->join(
            'evaluations',
            'evaluations.id_evaluation',
            '=',
            'notes.id_evaluation'
        )
        ->join(
            'classes',
            'classes.id_classe',
            '=',
            'evaluations.id_classe'
        )
        ->where(
            'evaluations.id_annee_scolaire',
            $annee->id_annee_scolaire
        )
        ->whereBetween(
            'evaluations.date_evaluation',
            [$dateDebut, $dateFin]
        )
        ->select(
            'classes.id_classe',
            'classes.libelle',
            DB::raw(
                'COUNT(notes.id_note) AS nombre_notes'
            ),
            DB::raw(
                'ROUND(AVG(notes.note), 2) AS moyenne'
            )
        )
        ->groupBy(
            'classes.id_classe',
            'classes.libelle'
        )
        ->orderBy('classes.libelle')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | 27. RETOUR DES DONNÉES
    |--------------------------------------------------------------------------
    */

    return compact(
        'etablissement',
        'annee',

        'mois',
        'dateDebut',
        'dateFin',

        'nombreEleves',
        'nombreGarcons',
        'nombreFilles',
        'effectifsClasses',

        'nombreInscriptions',

        'frequentation',
        'nombrePresences',
        'nombreAbsences',
        'tauxFrequentation',

        'nombreEvaluations',
        'nombreNotes',
        'moyenneNotes',

        'paiements',
        'nombrePaiements',

        'recettes',
        'depenses',
        'totalRecettes',
        'totalDepenses',
        'solde',

        'personnel',
        'nombreEnseignants',
        'nombreAutrePersonnel',

        'resultatsClasses'
    );
}


/*
|--------------------------------------------------------------------------
| AFFICHAGE DU RAPPORT MENSUEL
|--------------------------------------------------------------------------
*/

public function mensuel(Request $request)
{
    $mois = $request->input(
        'mois',
        now()->format('Y-m')
    );

    $data = $this->getRapportMensuelData($mois);

    return view(
        'rapports.mensuel',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| RAPPORT MENSUEL PDF
|--------------------------------------------------------------------------
*/

public function mensuelPdf(Request $request)
{
    $mois = $request->input(
        'mois',
        now()->format('Y-m')
    );

    $data = $this->getRapportMensuelData($mois);

    $pdf = Pdf::loadView(
        'rapports.pdf.mensuel',
        $data
    );

    $pdf->setPaper(
        'A4',
        'portrait'
    );

    return $pdf->download(
        'rapport-mensuel-' .
        $mois .
        '.pdf'
    );
}


/*
|--------------------------------------------------------------------------
| RAPPORT MENSUEL À IMPRIMER
|--------------------------------------------------------------------------
*/

public function mensuelImprimer(Request $request)
{
    $mois = $request->input(
        'mois',
        now()->format('Y-m')
    );

    $data = $this->getRapportMensuelData($mois);

    return view(
        'rapports.imprimer.mensuel',
        $data
    );
}


/*
|--------------------------------------------------------------------------
| RAPPORT MENSUEL EXCEL
|--------------------------------------------------------------------------
*/

public function mensuelExcel(Request $request)
{
    $this->anneeScolaireAccessible(null, true);

    $mois = $request->input(
        'mois',
        now()->format('Y-m')
    );

    return Excel::download(
        new \App\Exports\RapportMensuelExport($mois),
        'rapport-mensuel-' .
        $mois .
        '.xlsx'
    );
}

    /**
     * ==========================================================================
     * RAPPORT STATISTIQUE
     * ==========================================================================
     */
/**
 * Rapport statistique de l'établissement.
 */
public function statistique(Request $request)
{
    // Année scolaire active
    $anneeScolaire = $this->anneeScolaireAccessible(null, true);

    if (!$anneeScolaire) {
        return back()->with('error', 'Aucune année scolaire active n\'a été trouvée.');
    }

    // Établissement
    $etablissement = DB::table('etablissements')
        ->where('id_etablissement', $anneeScolaire->id_etablissement)
        ->first();

    /*
    |--------------------------------------------------------------------------
    | ÉLÈVES
    |--------------------------------------------------------------------------
    */

    $nombreEleves = DB::table('inscriptions')
        ->where('id_annee_scolaire', $anneeScolaire->id_annee_scolaire)
        ->distinct('id_eleve')
        ->count('id_eleve');

    // Répartition garçons / filles
    $repartitionSexe = DB::table('inscriptions')
        ->join(
            'eleves',
            'eleves.id_eleve',
            '=',
            'inscriptions.id_eleve'
        )
        ->where(
            'inscriptions.id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'eleves.sexe',
            DB::raw('COUNT(DISTINCT eleves.id_eleve) AS total')
        )
        ->groupBy('eleves.sexe')
        ->orderBy('eleves.sexe')
        ->get();

    $nombreGarcons = $repartitionSexe
        ->whereIn('sexe', ['M', 'Masculin', 'GARCON', 'GARÇON'])
        ->sum('total');

    $nombreFilles = $repartitionSexe
        ->whereIn('sexe', ['F', 'Féminin', 'FEMININ', 'FILLE'])
        ->sum('total');

    /*
    |--------------------------------------------------------------------------
    | RÉPARTITION PAR CLASSE
    |--------------------------------------------------------------------------
    */

    $effectifsClasses = DB::table('inscriptions')
    ->join(
        'classes',
        'classes.id_classe',
        '=',
        'inscriptions.id_classe'
    )
    ->where(
        'inscriptions.id_annee_scolaire',
        $anneeScolaire->id_annee_scolaire
    )
    ->select(
        'classes.id_classe',
        'classes.libelle',
        'classes.option_classe',
        DB::raw('COUNT(DISTINCT inscriptions.id_eleve) AS total')
    )
    ->groupBy(
        'classes.id_classe',
        'classes.libelle',
        'classes.option_classe'
    )
    ->orderBy('classes.libelle')
    ->get();

$nombreClasses = $effectifsClasses->count();

    /*
    |--------------------------------------------------------------------------
    | PERSONNEL
    |--------------------------------------------------------------------------
    */

    $nombreEnseignantsQuery = DB::table('personnel');

    if ($this->etablissementCourant() !== null) {
        $nombreEnseignantsQuery->where(
            'id_etablissement',
            $this->etablissementCourant()
        );
    }

    $nombreEnseignants = $nombreEnseignantsQuery
        ->whereIn('fonction', [
            'ENSEIGNANT',
            'Enseignant',
            'enseignant'
        ])
        ->whereIn('statut', [
            'ACTIF',
            'ACTIVE',
            'Actif',
            'Active'
        ])
        ->count();

    $nombrePersonnelQuery = DB::table('personnel');

    if ($this->etablissementCourant() !== null) {
        $nombrePersonnelQuery->where(
            'id_etablissement',
            $this->etablissementCourant()
        );
    }

    $nombrePersonnel = $nombrePersonnelQuery->count();

    $nombreAutrePersonnel = max(
        0,
        $nombrePersonnel - $nombreEnseignants
    );

    /*
    |--------------------------------------------------------------------------
    | INSCRIPTIONS
    |--------------------------------------------------------------------------
    */

    $nombreInscriptions = DB::table('inscriptions')
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->count();

    /*
    |--------------------------------------------------------------------------
    | FRÉQUENTATION
    |--------------------------------------------------------------------------
    */

    $frequentation = DB::table('presences')
        ->join(
            'inscriptions',
            'inscriptions.id_eleve',
            '=',
            'presences.id_eleve'
        )
        ->where(
            'inscriptions.id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'presences.statut',
            DB::raw('COUNT(*) AS total')
        )
        ->groupBy('presences.statut')
        ->orderBy('presences.statut')
        ->get();

    $nombrePresences = $frequentation
        ->where('statut', 'PRESENT')
        ->sum('total');

    $nombreAbsences = $frequentation
        ->where('statut', 'ABSENT')
        ->sum('total');

    $totalFrequentation = $nombrePresences + $nombreAbsences;

    $tauxPresence = $totalFrequentation > 0
        ? round(($nombrePresences / $totalFrequentation) * 100, 2)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | NOTES / ÉVALUATIONS
    |--------------------------------------------------------------------------
    */

    $nombreEvaluations = DB::table('evaluations')
        ->count();

    $nombreNotes = DB::table('notes')
        ->count();

    $moyenneNotes = DB::table('notes')
        ->avg('note');

    $moyenneNotes = $moyenneNotes !== null
        ? round($moyenneNotes, 2)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | RÉPARTITION DES NOTES
    |--------------------------------------------------------------------------
    */

    $resultatsNotes = DB::table('notes')
        ->join(
            'evaluations',
            'evaluations.id_evaluation',
            '=',
            'notes.id_evaluation'
        )
        ->where(
            'evaluations.id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            DB::raw("
                CASE
                    WHEN note >= 50 THEN 'Réussite'
                    ELSE 'Échec'
                END AS resultat
            "),
            DB::raw('COUNT(*) AS total')
        )
        ->groupBy('resultat')
        ->get();

    $nombreReussites = $resultatsNotes
        ->where('resultat', 'Réussite')
        ->sum('total');

    $nombreEchecs = $resultatsNotes
        ->where('resultat', 'Échec')
        ->sum('total');

    /*
    |--------------------------------------------------------------------------
    | FINANCES
    |--------------------------------------------------------------------------
    */

    $recettes = DB::table('recettes')
        ->where(
            'id_etablissement',
            $anneeScolaire->id_etablissement
        )
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'devise',
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('devise')
        ->get();

    $depenses = DB::table('depenses')
        ->where(
            'id_etablissement',
            $anneeScolaire->id_etablissement
        )
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'devise',
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('devise')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | VARIABLES FINANCIÈRES PAR DEVISE
    |--------------------------------------------------------------------------
    */

    $recettesUSD = $recettes
        ->where('devise', 'USD')
        ->sum('total');

    $recettesCDF = $recettes
        ->where('devise', 'CDF')
        ->sum('total');

    $depensesUSD = $depenses
        ->where('devise', 'USD')
        ->sum('total');

    $depensesCDF = $depenses
        ->where('devise', 'CDF')
        ->sum('total');

    $soldeUSD = $recettesUSD - $depensesUSD;
    $soldeCDF = $recettesCDF - $depensesCDF;

    /*
    |--------------------------------------------------------------------------
    | DONNÉES DU RAPPORT
    |--------------------------------------------------------------------------
    */

    return view('rapports.statistique', compact(
        'anneeScolaire',
        'etablissement',

        'nombreEleves',
        'nombreGarcons',
        'nombreFilles',

        'nombreClasses',
        'effectifsClasses',

        'nombreEnseignants',
        'nombrePersonnel',
        'nombreAutrePersonnel',

        'nombreInscriptions',

        'frequentation',
        'nombrePresences',
        'nombreAbsences',
        'totalFrequentation',
        'tauxPresence',

        'nombreEvaluations',
        'nombreNotes',
        'moyenneNotes',
        'nombreReussites',
        'nombreEchecs',

        'recettes',
        'depenses',
        'recettesUSD',
        'recettesCDF',
        'depensesUSD',
        'depensesCDF',
        'soldeUSD',
        'soldeCDF'
    ));
}

/**
 * Export PDF du rapport statistique.
 */
public function statistiquePdf(Request $request)
{
    // Année scolaire active
    $anneeScolaire = $this->anneeScolaireAccessible(null, true);

    if (!$anneeScolaire) {
        return back()->with(
            'error',
            'Aucune année scolaire active n\'a été trouvée.'
        );
    }

    // Établissement
    $etablissement = DB::table('etablissements')
        ->where(
            'id_etablissement',
            $anneeScolaire->id_etablissement
        )
        ->first();

    /*
    |--------------------------------------------------------------------------
    | ÉLÈVES
    |--------------------------------------------------------------------------
    */

    $nombreEleves = DB::table('inscriptions')
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->distinct('id_eleve')
        ->count('id_eleve');

    $repartitionSexe = DB::table('inscriptions')
        ->join(
            'eleves',
            'eleves.id_eleve',
            '=',
            'inscriptions.id_eleve'
        )
        ->where(
            'inscriptions.id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'eleves.sexe',
            DB::raw(
                'COUNT(DISTINCT eleves.id_eleve) AS total'
            )
        )
        ->groupBy('eleves.sexe')
        ->get();

    $nombreGarcons = $repartitionSexe
        ->whereIn('sexe', [
            'M',
            'Masculin',
            'GARCON',
            'GARÇON'
        ])
        ->sum('total');

    $nombreFilles = $repartitionSexe
        ->whereIn('sexe', [
            'F',
            'Féminin',
            'FEMININ',
            'FILLE'
        ])
        ->sum('total');

    /*
    |--------------------------------------------------------------------------
    | CLASSES
    |--------------------------------------------------------------------------
    */

    $effectifsClasses = DB::table('inscriptions')
        ->join(
            'classes',
            'classes.id_classe',
            '=',
            'inscriptions.id_classe'
        )
        ->where(
            'inscriptions.id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'classes.id_classe',
            'classes.libelle',
            'classes.option_classe',
            DB::raw(
                'COUNT(DISTINCT inscriptions.id_eleve) AS total'
            )
        )
        ->groupBy(
            'classes.id_classe',
            'classes.libelle',
            'classes.option_classe'
        )
        ->orderBy('classes.libelle')
        ->get();

    $nombreClasses = $effectifsClasses->count();

    /*
    |--------------------------------------------------------------------------
    | PERSONNEL
    |--------------------------------------------------------------------------
    */

    $nombreEnseignants = DB::table('personnel')
        ->whereIn('fonction', [
            'ENSEIGNANT',
            'Enseignant',
            'enseignant'
        ])
        ->whereIn('statut', [
            'ACTIF',
            'ACTIVE',
            'Actif',
            'Active'
        ])
        ->count();

    $nombrePersonnel = DB::table('personnel')->count();

    $nombreAutrePersonnel = max(
        0,
        $nombrePersonnel - $nombreEnseignants
    );

    /*
    |--------------------------------------------------------------------------
    | INSCRIPTIONS
    |--------------------------------------------------------------------------
    */

    $nombreInscriptions = DB::table('inscriptions')
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->count();

    /*
    |--------------------------------------------------------------------------
    | FRÉQUENTATION
    |--------------------------------------------------------------------------
    */

    $frequentation = DB::table('presences')
        ->join(
            'inscriptions',
            'inscriptions.id_eleve',
            '=',
            'presences.id_eleve'
        )
        ->where(
            'inscriptions.id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'presences.statut',
            DB::raw('COUNT(*) AS total')
        )
        ->groupBy('presences.statut')
        ->get();

    $nombrePresences = $frequentation
        ->where('statut', 'PRESENT')
        ->sum('total');

    $nombreAbsences = $frequentation
        ->where('statut', 'ABSENT')
        ->sum('total');

    $totalFrequentation =
        $nombrePresences + $nombreAbsences;

    $tauxPresence = $totalFrequentation > 0
        ? round(
            ($nombrePresences / $totalFrequentation) * 100,
            2
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | RÉSULTATS
    |--------------------------------------------------------------------------
    */

    $nombreEvaluations = DB::table('evaluations')
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
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
            $anneeScolaire->id_annee_scolaire
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
            $anneeScolaire->id_annee_scolaire
        )
        ->avg('notes.note');

    $moyenneNotes = $moyenneNotes !== null
        ? round($moyenneNotes, 2)
        : 0;

    $resultatsNotes = DB::table('notes')
        ->select(
            DB::raw("
                CASE
                    WHEN note >= 50 THEN 'Réussite'
                    ELSE 'Échec'
                END AS resultat
            "),
            DB::raw('COUNT(*) AS total')
        )
        ->groupBy('resultat')
        ->get();

    $nombreReussites = $resultatsNotes
        ->where('resultat', 'Réussite')
        ->sum('total');

    $nombreEchecs = $resultatsNotes
        ->where('resultat', 'Échec')
        ->sum('total');

    /*
    |--------------------------------------------------------------------------
    | FINANCES
    |--------------------------------------------------------------------------
    */

    $recettes = DB::table('recettes')
        ->where(
            'id_etablissement',
            $anneeScolaire->id_etablissement
        )
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'devise',
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('devise')
        ->get();

    $depenses = DB::table('depenses')
        ->where(
            'id_etablissement',
            $anneeScolaire->id_etablissement
        )
        ->where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
        ->select(
            'devise',
            DB::raw('SUM(montant) AS total')
        )
        ->groupBy('devise')
        ->get();

    $recettesUSD = $recettes
        ->where('devise', 'USD')
        ->sum('total');

    $recettesCDF = $recettes
        ->where('devise', 'CDF')
        ->sum('total');

    $depensesUSD = $depenses
        ->where('devise', 'USD')
        ->sum('total');

    $depensesCDF = $depenses
        ->where('devise', 'CDF')
        ->sum('total');

    $soldeUSD = $recettesUSD - $depensesUSD;

    $soldeCDF = $recettesCDF - $depensesCDF;

    /*
    |--------------------------------------------------------------------------
    | GÉNÉRATION PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'rapports.statistique-pdf',
        compact(
            'anneeScolaire',
            'etablissement',

            'nombreEleves',
            'nombreGarcons',
            'nombreFilles',

            'nombreClasses',
            'effectifsClasses',

            'nombreEnseignants',
            'nombrePersonnel',
            'nombreAutrePersonnel',

            'nombreInscriptions',

            'frequentation',
            'nombrePresences',
            'nombreAbsences',
            'totalFrequentation',
            'tauxPresence',

            'nombreEvaluations',
            'nombreNotes',
            'moyenneNotes',
            'nombreReussites',
            'nombreEchecs',

            'recettes',
            'depenses',

            'recettesUSD',
            'recettesCDF',
            'depensesUSD',
            'depensesCDF',

            'soldeUSD',
            'soldeCDF'
        )
    );

    $pdf->setPaper('A4', 'portrait');

    return $pdf->download(
        'rapport-statistique-' .
        $anneeScolaire->libelle .
        '.pdf'
    );
}
    /**
     * ==========================================================================
     * PALMARÈS DES ÉLÈVES
     * ==========================================================================
     */
    public function palmares(Request $request)
    {
        $annees = $this->anneesAccessibles();

        $periodes = collect();

        if ($request->filled('annee')) {
            $this->anneeScolaireAccessible($request->annee);

            $periodes = DB::table('periodes_scolaires')
                ->where(
                    'id_annee_scolaire',
                    $request->annee
                )
                ->orderBy('date_debut')
                ->get();
        }

        $classes = collect();

        if ($request->filled('annee')) {
            $classes = DB::table('classes')
                ->where(
                    'id_annee_scolaire',
                    $request->annee
                )
                ->orderBy('libelle')
                ->get();
        }

        $palmares = collect();

        if (
            $request->filled('annee') &&
            $request->filled('periode') &&
            $request->filled('classe')
        ) {
            $palmares = $this->getPalmaresData($request);
        }

        $anneeSelectionnee = null;
        $periodeSelectionnee = null;
        $classeSelectionnee = null;

        if ($request->filled('annee')) {
            $anneeSelectionnee = $annees
                ->firstWhere(
                    'id_annee_scolaire',
                    $request->annee
                );
        }

        if ($request->filled('periode')) {
            $periodeSelectionnee = $periodes
                ->firstWhere(
                    'id_periode',
                    $request->periode
                );
        }

        if ($request->filled('classe')) {
            $classeSelectionnee = $classes
                ->firstWhere(
                    'id_classe',
                    $request->classe
                );
        }

        return view(
            'rapports.palmares',
            compact(
                'annees',
                'periodes',
                'classes',
                'palmares',
                'anneeSelectionnee',
                'periodeSelectionnee',
                'classeSelectionnee'
            )
        );
    }


    /**
     * Génération du Palmarès en PDF.
     */
    public function palmaresPdf(Request $request)
    {
        $palmares = $this->getPalmaresData($request);

        $annee = $this->anneeScolaireAccessible($request->annee);

        $periode = DB::table('periodes_scolaires')
            ->where(
                'id_periode',
                $request->periode
            )
            ->first();

        $classe = $this->classeAccessible(
            $request->classe,
            $annee->id_annee_scolaire
        );

        $pdf = Pdf::loadView(
            'rapports.pdf.palmares',
            compact(
                'palmares',
                'annee',
                'periode',
                'classe'
            )
        );

        $pdf->setPaper(
            'A4',
            'landscape'
        );

        return $pdf->download(
            'palmares-' .
            ($classe->libelle ?? 'classe') .
            '.pdf'
        );
    }


    /**
     * Génération du Palmarès en Excel.
     */
    public function palmaresExcel(Request $request)
    {
        return Excel::download(
            new PalmaresExport($request),
            'palmares.xlsx'
        );
    }


    /**
     * Récupération des données du Palmarès.
     */
    private function getPalmaresData(Request $request)
    {
        $annee = $this->anneeScolaireAccessible($request->annee);
        $this->classeAccessible($request->classe, $annee->id_annee_scolaire);

        $query = DB::table('bulletins')
            ->join(
                'eleves',
                'bulletins.id_eleve',
                '=',
                'eleves.id_eleve'
            )
            ->where(
                'bulletins.id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->where(
                'bulletins.id_periode',
                $request->periode
            )
            ->where(
                'bulletins.id_classe',
                $request->classe
            );

        if ($this->etablissementCourant() !== null) {
            $query->where(
                'eleves.id_etablissement',
                $this->etablissementCourant()
            );
        }

        return $query
            ->select(
                'bulletins.id_bulletin',
                'bulletins.id_eleve',
                'bulletins.moyenne',
                'bulletins.pourcentage',
                'bulletins.rang',
                'bulletins.decision',
                'bulletins.observation',

                'eleves.matricule',
                'eleves.nom',
                'eleves.postnom',
                'eleves.prenom',
                'eleves.sexe'
            )
            ->orderByRaw(
                'CASE
                    WHEN bulletins.rang IS NULL
                    THEN 1
                    ELSE 0
                 END'
            )
            ->orderBy(
                'bulletins.rang'
            )
            ->orderByDesc(
                'bulletins.moyenne'
            )
            ->get();
    }


    /**
     * ==========================================================================
     * SÉLECTION DU BULLETIN
     * ==========================================================================
     */
    public function bulletinSelection(Request $request)
    {
        $elevesQuery = DB::table('eleves');

        if ($this->etablissementCourant() !== null) {
            $elevesQuery->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $eleves = $elevesQuery
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();

        $annees = $this->anneesAccessibles();

        $periodes = collect();

        if ($request->filled('annee')) {
            $periodes = DB::table('periodes_scolaires')
                ->where(
                    'id_annee_scolaire',
                    $request->annee
                )
                ->orderBy('date_debut')
                ->get();
        }

        return view(
            'rapports.bulletin-selection',
            compact(
                'eleves',
                'annees',
                'periodes'
            )
        );
    }


    /**
     * ==========================================================================
     * BULLETIN INDIVIDUEL
     * ==========================================================================
     */
    public function bulletin(Request $request, $eleve)
    {
        $eleveData = $this->eleveAccessible($eleve);

        if ($request->filled('annee')) {
            $this->anneeScolaireAccessible($request->annee);
        }

        if (!$eleveData) {
            abort(404, 'Élève introuvable.');
        }

        $query = DB::table('bulletins')
            ->where('id_eleve', $eleve);

        if ($request->filled('annee')) {
            $query->where(
                'id_annee_scolaire',
                $request->annee
            );
        }

        if ($request->filled('periode')) {
            $query->where(
                'id_periode',
                $request->periode
            );
        }

        $bulletin = $query
            ->orderByDesc('date_generation')
            ->first();

        if (!$bulletin) {
            return back()->with(
                'error',
                'Aucun bulletin trouvé pour cet élève.'
            );
        }

        $annee = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $bulletin->id_annee_scolaire
            )
            ->first();

        $periode = DB::table('periodes_scolaires')
            ->where(
                'id_periode',
                $bulletin->id_periode
            )
            ->first();

        $classe = DB::table('classes')
            ->where(
                'id_classe',
                $bulletin->id_classe
            )
            ->first();

        $details = DB::table('details_bulletins')
            ->join(
                'matieres',
                'details_bulletins.id_matiere',
                '=',
                'matieres.id_matiere'
            )
            ->where(
                'details_bulletins.id_bulletin',
                $bulletin->id_bulletin
            )
            ->select(
                'details_bulletins.id_detail',
                'details_bulletins.id_matiere',
                'details_bulletins.total',
                'details_bulletins.moyenne',
                'details_bulletins.coefficient',
                'details_bulletins.points',
                'details_bulletins.appreciation',

                'matieres.code',
                'matieres.libelle'
            )
            ->orderBy('matieres.libelle')
            ->get();

        $pdf = Pdf::loadView(
            'rapports.pdf.bulletin',
            compact(
                'eleveData',
                'bulletin',
                'details',
                'annee',
                'periode',
                'classe'
            )
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        $nomFichier =
            'bulletin-' .
            $eleveData->matricule .
            '-' .
            $periode->libelle .
            '.pdf';

        return $pdf->download($nomFichier);
    }


    /**
     * ==========================================================================
     * FICHE DE FRÉQUENTATION
     * ==========================================================================
     */
    public function frequentation(Request $request)
    {
        $annees = $this->anneesAccessibles();

        if ($request->filled('annee')) {
            $this->anneeScolaireAccessible($request->annee);
        }

        if ($request->filled('classe') && $request->filled('annee')) {
            $this->classeAccessible($request->classe, $request->annee);
        }

        $periodes = collect();

        if ($request->filled('annee')) {
            $periodes = DB::table('periodes_scolaires')
                ->where(
                    'id_annee_scolaire',
                    $request->annee
                )
                ->orderBy('date_debut')
                ->get();
        }

        $classes = collect();

        if ($request->filled('annee')) {
            $classes = DB::table('classes')
                ->where(
                    'id_annee_scolaire',
                    $request->annee
                )
                ->orderBy('libelle')
                ->get();
        }

        $frequentation = collect();

        if (
            $request->filled('annee') &&
            $request->filled('periode') &&
            $request->filled('classe')
        ) {
            $periode = DB::table('periodes_scolaires')
                ->where(
                    'id_periode',
                    $request->periode
                )
                ->where(
                    'id_annee_scolaire',
                    $request->annee
                )
                ->first();

            if ($periode) {
                $frequentation = DB::table('eleves')
                    ->join(
                        'presences',
                        'eleves.id_eleve',
                        '=',
                        'presences.id_eleve'
                    )
                    ->where(
                        'presences.id_classe',
                        $request->classe
                    )
                    ->whereBetween(
                        'presences.date_presence',
                        [
                            $periode->date_debut,
                            $periode->date_fin
                        ]
                    )
                    ->select(
                        'eleves.id_eleve',
                        'eleves.matricule',
                        'eleves.nom',
                        'eleves.postnom',
                        'eleves.prenom',

                        DB::raw(
                            'COUNT(presences.id_presence) as total_jours'
                        ),

                        DB::raw("
                            SUM(
                                CASE
                                    WHEN UPPER(presences.statut) = 'PRESENT'
                                    THEN 1
                                    ELSE 0
                                END
                            ) as presents
                        "),

                        DB::raw("
                            SUM(
                                CASE
                                    WHEN UPPER(presences.statut) = 'ABSENT'
                                    THEN 1
                                    ELSE 0
                                END
                            ) as absents
                        ")
                    )
                    ->groupBy(
                        'eleves.id_eleve',
                        'eleves.matricule',
                        'eleves.nom',
                        'eleves.postnom',
                        'eleves.prenom'
                    )
                    ->orderBy('eleves.nom')
                    ->orderBy('eleves.postnom')
                    ->orderBy('eleves.prenom')
                    ->get();

                $frequentation = $frequentation->map(
                    function ($eleve) {
                        $eleve->taux =
                            $eleve->total_jours > 0
                            ? round(
                                (
                                    $eleve->presents /
                                    $eleve->total_jours
                                ) * 100,
                                2
                            )
                            : 0;

                        return $eleve;
                    }
                );
            }
        }

        $anneeSelectionnee = null;
        $periodeSelectionnee = null;
        $classeSelectionnee = null;

        if ($request->filled('annee')) {
            $anneeSelectionnee = $annees
                ->firstWhere(
                    'id_annee_scolaire',
                    $request->annee
                );
        }

        if ($request->filled('periode')) {
            $periodeSelectionnee = $periodes
                ->firstWhere(
                    'id_periode',
                    $request->periode
                );
        }

        if ($request->filled('classe')) {
            $classeSelectionnee = $classes
                ->firstWhere(
                    'id_classe',
                    $request->classe
                );
        }

        return view(
            'rapports.frequentation',
            compact(
                'annees',
                'periodes',
                'classes',
                'frequentation',
                'anneeSelectionnee',
                'periodeSelectionnee',
                'classeSelectionnee'
            )
        );
    }


    /**
     * ==========================================================================
     * ENSEIGNANTS
     * ==========================================================================
     */
    public function enseignants(Request $request)
    {
        $enseignantsQuery = DB::table('personnel');

        if ($this->etablissementCourant() !== null) {
            $enseignantsQuery->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $enseignants = $enseignantsQuery
            ->whereIn('fonction', [
                'ENSEIGNANT',
                'Enseignant',
                'enseignant'
            ])
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();

        return view(
            'rapports.enseignants',
            compact('enseignants')
        );
    }


    public function enseignantsImprimer()
    {
        $enseignants = $this->getEnseignantsData();

        return view(
            'rapports.imprimer.enseignants',
            compact('enseignants')
        );
    }


    private function getEnseignantsData()
    {
        $query = DB::table('personnel');

        if ($this->etablissementCourant() !== null) {
            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        return $query
            ->whereIn('fonction', [
                'ENSEIGNANT',
                'Enseignant',
                'enseignant'
            ])
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();
    }


    public function enseignantsExcel()
    {
        return Excel::download(
            new EnseignantsExport(),
            'liste-enseignants.xlsx'
        );
    }


    /**
     * ==========================================================================
     * FINANCES
     * ==========================================================================
     */
    public function finances(Request $request)
    {
        $annees = $this->anneesAccessibles();

        $anneeSelectionnee = null;
        $recettes = collect();
        $depenses = collect();
        $paiements = collect();

        $totalRecettes = 0;
        $totalDepenses = 0;
        $solde = 0;
        $totalPaiements = 0;

        if ($request->filled('annee')) {
            $anneeSelectionnee = $this->anneeScolaireAccessible(
                $request->annee
            );

            if ($anneeSelectionnee) {
                $recettes = DB::table('recettes')
                    ->where(
                        'id_annee_scolaire',
                        $request->annee
                    )
                    ->when(
                        $this->etablissementCourant() !== null,
                        fn ($query) => $query->where(
                            'id_etablissement',
                            $this->etablissementCourant()
                        )
                    )
                    ->orderByDesc('date_recette')
                    ->get();

                $depenses = DB::table('depenses')
                    ->where(
                        'id_annee_scolaire',
                        $request->annee
                    )
                    ->when(
                        $this->etablissementCourant() !== null,
                        fn ($query) => $query->where(
                            'id_etablissement',
                            $this->etablissementCourant()
                        )
                    )
                    ->orderByDesc('date_depense')
                    ->get();

                $paiements = DB::table('paiements')
                    ->join(
                        'recettes',
                        'paiements.id_paiement',
                        '=',
                        'recettes.id_paiement'
                    )
                    ->where(
                        'recettes.id_annee_scolaire',
                        $request->annee
                    )
                    ->select(
                        'paiements.*'
                    )
                    ->orderByDesc(
                        'paiements.date_paiement'
                    )
                    ->get();

                $totalRecettes = $recettes->sum('montant');

                $totalDepenses = $depenses->sum('montant');

                $solde =
                    $totalRecettes -
                    $totalDepenses;

                $totalPaiements =
                    $paiements->sum('montant_total');
            }
        }

        return view(
            'rapports.finances',
            compact(
                'annees',
                'anneeSelectionnee',
                'recettes',
                'depenses',
                'paiements',
                'totalRecettes',
                'totalDepenses',
                'solde',
                'totalPaiements'
            )
        );
    }


    private function getFinancesData($annee)
    {
        $anneeSelectionnee = $this->anneeScolaireAccessible($annee);

        $recettes = DB::table('recettes')
            ->where('id_annee_scolaire', $anneeSelectionnee->id_annee_scolaire)
            ->when(
                $this->etablissementCourant() !== null,
                fn ($query) => $query->where(
                    'id_etablissement',
                    $this->etablissementCourant()
                )
            )
            ->orderByDesc('date_recette')
            ->get();

        $depenses = DB::table('depenses')
            ->where('id_annee_scolaire', $anneeSelectionnee->id_annee_scolaire)
            ->when(
                $this->etablissementCourant() !== null,
                fn ($query) => $query->where(
                    'id_etablissement',
                    $this->etablissementCourant()
                )
            )
            ->orderByDesc('date_depense')
            ->get();

        $paiements = DB::table('paiements')
            ->join(
                'recettes',
                'paiements.id_paiement',
                '=',
                'recettes.id_paiement'
            )
            ->where(
                'recettes.id_annee_scolaire',
                $anneeSelectionnee->id_annee_scolaire
            )
            ->when(
                $this->etablissementCourant() !== null,
                fn ($query) => $query->where(
                    'recettes.id_etablissement',
                    $this->etablissementCourant()
                )
            )
            ->select('paiements.*')
            ->orderByDesc('paiements.date_paiement')
            ->get();

        $totalRecettes = $recettes->sum('montant');
        $totalDepenses = $depenses->sum('montant');
        $solde = $totalRecettes - $totalDepenses;
        $totalPaiements = $paiements->sum('montant_total');

        return compact(
            'anneeSelectionnee',
            'recettes',
            'depenses',
            'paiements',
            'totalRecettes',
            'totalDepenses',
            'solde',
            'totalPaiements'
        );
    }


    public function financesPdf(Request $request)
    {
        abort_unless(
            $request->filled('annee'),
            404
        );

        $data = $this->getFinancesData(
            $request->annee
        );

        $pdf = Pdf::loadView(
            'rapports.pdf.finances',
            $data
        );

        $pdf->setPaper(
            'A4',
            'landscape'
        );

        return $pdf->download(
            'situation-financiere.pdf'
        );
    }


    public function financesImprimer(Request $request)
    {
        abort_unless(
            $request->filled('annee'),
            404
        );

        $data = $this->getFinancesData(
            $request->annee
        );

        return view(
            'rapports.imprimer.finances',
            $data
        );
    }


    /**
     * ==========================================================================
     * FRÉQUENTATION PDF / IMPRESSION
     * ==========================================================================
     */
    public function frequentationPdf(Request $request)
    {
        $data = $this->getFrequentationData(
            $request
        );

        $pdf = Pdf::loadView(
            'rapports.pdf.frequentation',
            $data
        );

        $pdf->setPaper(
            'A4',
            'landscape'
        );

        return $pdf->download(
            'fiche-frequentation.pdf'
        );
    }


    public function frequentationImprimer(Request $request)
    {
        $data = $this->getFrequentationData(
            $request
        );

        return view(
            'rapports.imprimer.frequentation',
            $data
        );
    }


    private function getFrequentationData(
        Request $request
    ) {
        $annee = $this->anneeScolaireAccessible($request->annee);

        $periode = DB::table('periodes_scolaires')
            ->where(
                'id_periode',
                $request->periode
            )
            ->first();

        $classe = $this->classeAccessible(
            $request->classe,
            $annee->id_annee_scolaire
        );

        $frequentation = collect();

        if ($periode) {
            $frequentation = DB::table('eleves')
                ->join(
                    'presences',
                    'eleves.id_eleve',
                    '=',
                    'presences.id_eleve'
                )
                ->where(
                    'presences.id_classe',
                    $request->classe
                )
                ->whereBetween(
                    'presences.date_presence',
                    [
                        $periode->date_debut,
                        $periode->date_fin
                    ]
                )
                ->select(
                    'eleves.id_eleve',
                    'eleves.matricule',
                    'eleves.nom',
                    'eleves.postnom',
                    'eleves.prenom',

                    DB::raw(
                        'COUNT(presences.id_presence) as total_jours'
                    ),

                    DB::raw("
                        SUM(
                            CASE
                                WHEN UPPER(presences.statut) = 'PRESENT'
                                THEN 1
                                ELSE 0
                            END
                        ) as presents
                    "),

                    DB::raw("
                        SUM(
                            CASE
                                WHEN UPPER(presences.statut) = 'ABSENT'
                                THEN 1
                                ELSE 0
                            END
                        ) as absents
                    ")
                )
                ->groupBy(
                    'eleves.id_eleve',
                    'eleves.matricule',
                    'eleves.nom',
                    'eleves.postnom',
                    'eleves.prenom'
                )
                ->orderBy('eleves.nom')
                ->orderBy('eleves.postnom')
                ->orderBy('eleves.prenom')
                ->get();

            $frequentation =
                $frequentation->map(
                    function ($eleve) {
                        $eleve->taux =
                            $eleve->total_jours > 0
                            ? round(
                                (
                                    $eleve->presents /
                                    $eleve->total_jours
                                ) * 100,
                                2
                            )
                            : 0;

                        return $eleve;
                    }
                );
        }

        return compact(
            'annee',
            'periode',
            'classe',
            'frequentation'
        );
    }


    /**
     * ==========================================================================
     * INVENTAIRE
     * ==========================================================================
     */
    public function inventaire(Request $request)
    {
        $categories = DB::table(
            'categories_inventaire'
        )
            ->orderBy('libelle')
            ->get();

        $biens = DB::table('inventaire')
            ->leftJoin(
                'categories_inventaire',
                'inventaire.id_categorie',
                '=',
                'categories_inventaire.id_categorie'
            )
            ->when(
                $this->etablissementCourant() !== null,
                function ($query) {
                    $query->where(
                        'inventaire.id_etablissement',
                        $this->etablissementCourant()
                    );
                }
            )
            ->select(
                'inventaire.*',
                'categories_inventaire.libelle as categorie'
            )
            ->when(
                $request->filled('categorie'),
                function ($query) use ($request) {
                    $query->where(
                        'inventaire.id_categorie',
                        $request->categorie
                    );
                }
            )
            ->when(
                $request->filled('etat'),
                function ($query) use ($request) {
                    $query->where(
                        'inventaire.etat',
                        $request->etat
                    );
                }
            )
            ->orderBy('designation')
            ->get();

        $totalBiens = $biens->count();

        $totalQuantite =
            $biens->sum('quantite');

        return view(
            'rapports.inventaire',
            compact(
                'categories',
                'biens',
                'totalBiens',
                'totalQuantite'
            )
        );
    }


    public function inventairePdf(Request $request)
    {
        $categories = DB::table(
            'categories_inventaire'
        )
            ->orderBy('libelle')
            ->get();

        $biens = DB::table('inventaire')
            ->leftJoin(
                'categories_inventaire',
                'inventaire.id_categorie',
                '=',
                'categories_inventaire.id_categorie'
            )
            ->when(
                $this->etablissementCourant() !== null,
                function ($query) {
                    $query->where(
                        'inventaire.id_etablissement',
                        $this->etablissementCourant()
                    );
                }
            )
            ->select(
                'inventaire.*',
                'categories_inventaire.libelle as categorie'
            )
            ->when(
                $request->filled('categorie'),
                function ($query) use ($request) {
                    $query->where(
                        'inventaire.id_categorie',
                        $request->categorie
                    );
                }
            )
            ->when(
                $request->filled('etat'),
                function ($query) use ($request) {
                    $query->where(
                        'inventaire.etat',
                        $request->etat
                    );
                }
            )
            ->orderBy('designation')
            ->get();

        $totalBiens = $biens->count();

        $totalQuantite =
            $biens->sum('quantite');

        $pdf = Pdf::loadView(
            'rapports.pdf.inventaire',
            compact(
                'biens',
                'totalBiens',
                'totalQuantite'
            )
        );

        $pdf->setPaper(
            'A4',
            'landscape'
        );

        return $pdf->download(
            'inventaire-des-biens.pdf'
        );
    }


    public function inventaireImprimer(
        Request $request
    ) {
        $biens = DB::table('inventaire')
            ->leftJoin(
                'categories_inventaire',
                'inventaire.id_categorie',
                '=',
                'categories_inventaire.id_categorie'
            )
            ->when(
                $this->etablissementCourant() !== null,
                function ($query) {
                    $query->where(
                        'inventaire.id_etablissement',
                        $this->etablissementCourant()
                    );
                }
            )
            ->select(
                'inventaire.*',
                'categories_inventaire.libelle as categorie'
            )
            ->when(
                $request->filled('categorie'),
                function ($query) use ($request) {
                    $query->where(
                        'inventaire.id_categorie',
                        $request->categorie
                    );
                }
            )
            ->when(
                $request->filled('etat'),
                function ($query) use ($request) {
                    $query->where(
                        'inventaire.etat',
                        $request->etat
                    );
                }
            )
            ->orderBy('designation')
            ->get();

        $totalBiens = $biens->count();

        $totalQuantite =
            $biens->sum('quantite');

        return view(
            'rapports.imprimer.inventaire',
            compact(
                'biens',
                'totalBiens',
                'totalQuantite'
            )
        );
    }


    public function inventaireExcel(
        Request $request
    ) {
        return Excel::download(
            new \App\Exports\InventaireExport(
                $request->categorie,
                $request->etat
            ),
            'inventaire-des-biens.xlsx'
        );
    }


    /**
     * ==========================================================================
     * STATISTIQUES DES EXAMENS NATIONAUX
     * ==========================================================================
     */
    public function examensNationaux(
        Request $request
    ) {
        $annees = $this->anneesAccessibles();

        $anneeSelectionnee = $this->anneeScolaireAccessible(
            $request->annee ?: null,
            $request->filled('annee') ? false : true
        );

        $anneeId = $anneeSelectionnee->id_annee_scolaire;

        $examens = DB::table('notes')
            ->join(
                'evaluations',
                'notes.id_evaluation',
                '=',
                'evaluations.id_evaluation'
            )
            ->join(
                'eleves',
                'notes.id_eleve',
                '=',
                'eleves.id_eleve'
            )
            ->join(
                'classes',
                'evaluations.id_classe',
                '=',
                'classes.id_classe'
            )
            ->join(
                'matieres',
                'evaluations.id_matiere',
                '=',
                'matieres.id_matiere'
            )
            ->where(
                'evaluations.type_evaluation',
                'Examen'
            )
            ->where(
                'evaluations.id_annee_scolaire',
                $anneeId
            )
            ->when(
                $this->etablissementCourant() !== null,
                function ($query) {
                    $query->where(
                        'eleves.id_etablissement',
                        $this->etablissementCourant()
                    );

                    $query->where(
                        'classes.id_etablissement',
                        $this->etablissementCourant()
                    );

                    $query->where(
                        'matieres.id_etablissement',
                        $this->etablissementCourant()
                    );
                }
            )
            ->select(
                'notes.id_note',
                'notes.id_eleve',
                'notes.note',
                'notes.appreciation',
                'eleves.matricule',
                'eleves.nom',
                'eleves.postnom',
                'eleves.prenom',
                'classes.libelle as classe',
                'matieres.libelle as matiere',
                'evaluations.libelle as examen',
                'evaluations.note_maximale',
                'evaluations.date_evaluation'
            )
            ->orderBy('classes.libelle')
            ->orderBy('eleves.nom')
            ->get();

        $nombreCandidats = $examens
            ->pluck('id_eleve')
            ->unique()
            ->count();

        $nombreNotes =
            $examens->count();

        $nombreAdmis = $examens
            ->filter(function ($examen) {
                if (!$examen->note_maximale) {
                    return false;
                }

                return (
                    (
                        $examen->note /
                        $examen->note_maximale
                    ) * 100
                ) >= 50;
            })
            ->pluck('id_eleve')
            ->unique()
            ->count();

        $nombreEchecs = max(
            0,
            $nombreCandidats -
            $nombreAdmis
        );

        $tauxReussite =
            $nombreCandidats > 0
            ? round(
                (
                    $nombreAdmis /
                    $nombreCandidats
                ) * 100,
                2
            )
            : 0;

        $tauxEchec =
            $nombreCandidats > 0
            ? round(
                (
                    $nombreEchecs /
                    $nombreCandidats
                ) * 100,
                2
            )
            : 0;

        $moyenneGenerale =
            $examens->count() > 0
            ? round(
                $examens->avg(
                    function ($examen) {
                        if (!$examen->note_maximale) {
                            return 0;
                        }

                        return (
                            $examen->note /
                            $examen->note_maximale
                        ) * 20;
                    }
                ),
                2
            )
            : 0;

        $statistiquesClasses =
            $examens
                ->groupBy('classe')
                ->map(
                    function (
                        $notes,
                        $classe
                    ) {
                        $candidats =
                            $notes
                                ->pluck('id_eleve')
                                ->unique();

                        $admis =
                            $notes
                                ->filter(
                                    function ($note) {
                                        if (
                                            !$note->note_maximale
                                        ) {
                                            return false;
                                        }

                                        return (
                                            (
                                                $note->note /
                                                $note->note_maximale
                                            ) * 100
                                        ) >= 50;
                                    }
                                )
                                ->pluck('id_eleve')
                                ->unique();

                        $nombreCandidats =
                            $candidats->count();

                        $nombreAdmis =
                            $admis->count();

                        return (object) [
                            'classe' => $classe,
                            'candidats' =>
                                $nombreCandidats,
                            'admis' =>
                                $nombreAdmis,
                            'echecs' => max(
                                0,
                                $nombreCandidats -
                                $nombreAdmis
                            ),
                            'taux' =>
                                $nombreCandidats > 0
                                ? round(
                                    (
                                        $nombreAdmis /
                                        $nombreCandidats
                                    ) * 100,
                                    2
                                )
                                : 0,
                        ];
                    }
                )
                ->values();

        $statistiquesMatieres =
            $examens
                ->groupBy('matiere')
                ->map(
                    function (
                        $notes,
                        $matiere
                    ) {
                        $moyenne =
                            $notes->count() > 0
                            ? round(
                                $notes->avg(
                                    function ($note) {
                                        if (
                                            !$note->note_maximale
                                        ) {
                                            return 0;
                                        }

                                        return (
                                            $note->note /
                                            $note->note_maximale
                                        ) * 20;
                                    }
                                ),
                                2
                            )
                            : 0;

                        $meilleure =
                            $notes->max(
                                function ($note) {
                                    if (
                                        !$note->note_maximale
                                    ) {
                                        return 0;
                                    }

                                    return (
                                        $note->note /
                                        $note->note_maximale
                                    ) * 20;
                                }
                            );

                        $minimale =
                            $notes->min(
                                function ($note) {
                                    if (
                                        !$note->note_maximale
                                    ) {
                                        return 0;
                                    }

                                    return (
                                        $note->note /
                                        $note->note_maximale
                                    ) * 20;
                                }
                            );

                        return (object) [
                            'matiere' =>
                                $matiere,

                            'candidats' =>
                                $notes
                                    ->pluck('id_eleve')
                                    ->unique()
                                    ->count(),

                            'moyenne' =>
                                $moyenne,

                            'meilleure' =>
                                round(
                                    $meilleure,
                                    2
                                ),

                            'minimale' =>
                                round(
                                    $minimale,
                                    2
                                ),
                        ];
                    }
                )
                ->values();

        return view(
            'rapports.examens-nationaux',
            compact(
                'annees',
                'anneeId',
                'examens',
                'nombreCandidats',
                'nombreNotes',
                'nombreAdmis',
                'nombreEchecs',
                'tauxReussite',
                'tauxEchec',
                'moyenneGenerale',
                'statistiquesClasses',
                'statistiquesMatieres'
            )
        );
    }


    /**
     * ==========================================================================
     * EXAMENS NATIONAUX PDF
     * ==========================================================================
     */
    public function examensNationauxPdf(
        Request $request
    ) {
        $anneeSelectionnee = $this->anneeScolaireAccessible(
            $request->annee ?: null,
            $request->filled('annee') ? false : true
        );

        $anneeId = $anneeSelectionnee->id_annee_scolaire;

        $examens = DB::table('notes')
            ->join(
                'evaluations',
                'notes.id_evaluation',
                '=',
                'evaluations.id_evaluation'
            )
            ->join(
                'eleves',
                'notes.id_eleve',
                '=',
                'eleves.id_eleve'
            )
            ->join(
                'classes',
                'evaluations.id_classe',
                '=',
                'classes.id_classe'
            )
            ->join(
                'matieres',
                'evaluations.id_matiere',
                '=',
                'matieres.id_matiere'
            )
            ->where(
                'evaluations.type_evaluation',
                'Examen'
            )
            ->where(
                'evaluations.id_annee_scolaire',
                $anneeId
            )
            ->when(
                $this->etablissementCourant() !== null,
                function ($query) {
                    $query->where(
                        'eleves.id_etablissement',
                        $this->etablissementCourant()
                    );

                    $query->where(
                        'classes.id_etablissement',
                        $this->etablissementCourant()
                    );

                    $query->where(
                        'matieres.id_etablissement',
                        $this->etablissementCourant()
                    );
                }
            )
            ->select(
                'notes.id_note',
                'notes.id_eleve',
                'notes.note',
                'notes.appreciation',
                'eleves.matricule',
                'eleves.nom',
                'eleves.postnom',
                'eleves.prenom',
                'classes.libelle as classe',
                'matieres.libelle as matiere',
                'evaluations.libelle as examen',
                'evaluations.note_maximale',
                'evaluations.date_evaluation'
            )
            ->orderBy('classes.libelle')
            ->orderBy('eleves.nom')
            ->get();

        $nombreCandidats =
            $examens
                ->pluck('id_eleve')
                ->unique()
                ->count();

        $nombreAdmis =
            $examens
                ->filter(
                    function ($examen) {
                        return
                            $examen->note_maximale > 0
                            &&
                            (
                                (
                                    $examen->note /
                                    $examen->note_maximale
                                ) * 100
                            ) >= 50;
                    }
                )
                ->pluck('id_eleve')
                ->unique()
                ->count();

        $nombreEchecs = max(
            0,
            $nombreCandidats -
            $nombreAdmis
        );

        $tauxReussite =
            $nombreCandidats > 0
            ? round(
                (
                    $nombreAdmis /
                    $nombreCandidats
                ) * 100,
                2
            )
            : 0;

        $tauxEchec =
            $nombreCandidats > 0
            ? round(
                (
                    $nombreEchecs /
                    $nombreCandidats
                ) * 100,
                2
            )
            : 0;

        $moyenneGenerale =
            $examens->count() > 0
            ? round(
                $examens->avg(
                    function ($examen) {
                        return
                            $examen->note_maximale > 0
                            ? (
                                $examen->note /
                                $examen->note_maximale
                            ) * 20
                            : 0;
                    }
                ),
                2
            )
            : 0;

        $statistiquesClasses =
            $examens
                ->groupBy('classe')
                ->map(
                    function (
                        $notes,
                        $classe
                    ) {
                        $candidats =
                            $notes
                                ->pluck('id_eleve')
                                ->unique();

                        $admis =
                            $notes
                                ->filter(
                                    function ($note) {
                                        return
                                            $note->note_maximale > 0
                                            &&
                                            (
                                                (
                                                    $note->note /
                                                    $note->note_maximale
                                                ) * 100
                                            ) >= 50;
                                    }
                                )
                                ->pluck('id_eleve')
                                ->unique();

                        $nombre =
                            $candidats->count();

                        $admisCount =
                            $admis->count();

                        return (object) [
                            'classe' =>
                                $classe,

                            'candidats' =>
                                $nombre,

                            'admis' =>
                                $admisCount,

                            'echecs' => max(
                                0,
                                $nombre -
                                $admisCount
                            ),

                            'taux' =>
                                $nombre > 0
                                ? round(
                                    (
                                        $admisCount /
                                        $nombre
                                    ) * 100,
                                    2
                                )
                                : 0,
                        ];
                    }
                )
                ->values();

        $statistiquesMatieres =
            $examens
                ->groupBy('matiere')
                ->map(
                    function (
                        $notes,
                        $matiere
                    ) {
                        $moyenne =
                            $notes->count() > 0
                            ? round(
                                $notes->avg(
                                    function ($note) {
                                        return
                                            $note->note_maximale > 0
                                            ? (
                                                $note->note /
                                                $note->note_maximale
                                            ) * 20
                                            : 0;
                                    }
                                ),
                                2
                            )
                            : 0;

                        $meilleure =
                            $notes->max(
                                function ($note) {
                                    return
                                        $note->note_maximale > 0
                                        ? (
                                            $note->note /
                                            $note->note_maximale
                                        ) * 20
                                        : 0;
                                }
                            );

                        $minimale =
                            $notes->min(
                                function ($note) {
                                    return
                                        $note->note_maximale > 0
                                        ? (
                                            $note->note /
                                            $note->note_maximale
                                        ) * 20
                                        : 0;
                                }
                            );

                        return (object) [
                            'matiere' =>
                                $matiere,

                            'candidats' =>
                                $notes
                                    ->pluck('id_eleve')
                                    ->unique()
                                    ->count(),

                            'moyenne' =>
                                $moyenne,

                            'meilleure' =>
                                round(
                                    $meilleure,
                                    2
                                ),

                            'minimale' =>
                                round(
                                    $minimale,
                                    2
                                ),
                        ];
                    }
                )
                ->values();

        $pdf = Pdf::loadView(
            'rapports.pdf.examens-nationaux',
            compact(
                'examens',
                'nombreCandidats',
                'nombreAdmis',
                'nombreEchecs',
                'tauxReussite',
                'tauxEchec',
                'moyenneGenerale',
                'statistiquesClasses',
                'statistiquesMatieres'
            )
        );

        $pdf->setPaper(
            'A4',
            'landscape'
        );

        return $pdf->download(
            'statistiques-examens-nationaux.pdf'
        );
    }


    /**
     * ==========================================================================
     * EXAMENS NATIONAUX À IMPRIMER
     * ==========================================================================
     */
    public function examensNationauxImprimer(
        Request $request
    ) {
        $anneeSelectionnee = $this->anneeScolaireAccessible(
            $request->annee ?: null,
            $request->filled('annee') ? false : true
        );

        $anneeId = $anneeSelectionnee->id_annee_scolaire;

        $examens = DB::table('notes')
            ->join(
                'evaluations',
                'notes.id_evaluation',
                '=',
                'evaluations.id_evaluation'
            )
            ->join(
                'eleves',
                'notes.id_eleve',
                '=',
                'eleves.id_eleve'
            )
            ->join(
                'classes',
                'evaluations.id_classe',
                '=',
                'classes.id_classe'
            )
            ->join(
                'matieres',
                'evaluations.id_matiere',
                '=',
                'matieres.id_matiere'
            )
            ->where(
                'evaluations.type_evaluation',
                'Examen'
            )
            ->where(
                'evaluations.id_annee_scolaire',
                $anneeId
            )
            ->when(
                $this->etablissementCourant() !== null,
                function ($query) {
                    $query->where(
                        'eleves.id_etablissement',
                        $this->etablissementCourant()
                    );

                    $query->where(
                        'classes.id_etablissement',
                        $this->etablissementCourant()
                    );

                    $query->where(
                        'matieres.id_etablissement',
                        $this->etablissementCourant()
                    );
                }
            )
            ->select(
                'notes.id_note',
                'notes.id_eleve',
                'notes.note',
                'notes.appreciation',
                'eleves.matricule',
                'eleves.nom',
                'eleves.postnom',
                'eleves.prenom',
                'classes.libelle as classe',
                'matieres.libelle as matiere',
                'evaluations.libelle as examen',
                'evaluations.note_maximale',
                'evaluations.date_evaluation'
            )
            ->orderBy('classes.libelle')
            ->orderBy('eleves.nom')
            ->get();

        $nombreCandidats =
            $examens
                ->pluck('id_eleve')
                ->unique()
                ->count();

        $nombreAdmis =
            $examens
                ->filter(
                    function ($examen) {
                        return
                            $examen->note_maximale > 0
                            &&
                            (
                                (
                                    $examen->note /
                                    $examen->note_maximale
                                ) * 100
                            ) >= 50;
                    }
                )
                ->pluck('id_eleve')
                ->unique()
                ->count();

        $nombreEchecs = max(
            0,
            $nombreCandidats -
            $nombreAdmis
        );

        $tauxReussite =
            $nombreCandidats > 0
            ? round(
                (
                    $nombreAdmis /
                    $nombreCandidats
                ) * 100,
                2
            )
            : 0;

        $moyenneGenerale =
            $examens->count() > 0
            ? round(
                $examens->avg(
                    function ($examen) {
                        return
                            $examen->note_maximale > 0
                            ? (
                                $examen->note /
                                $examen->note_maximale
                            ) * 20
                            : 0;
                    }
                ),
                2
            )
            : 0;

        $statistiquesClasses =
            $examens
                ->groupBy('classe')
                ->map(
                    function (
                        $notes,
                        $classe
                    ) {
                        $candidats =
                            $notes
                                ->pluck('id_eleve')
                                ->unique();

                        $admis =
                            $notes
                                ->filter(
                                    function ($note) {
                                        return
                                            $note->note_maximale > 0
                                            &&
                                            (
                                                (
                                                    $note->note /
                                                    $note->note_maximale
                                                ) * 100
                                            ) >= 50;
                                    }
                                )
                                ->pluck('id_eleve')
                                ->unique();

                        $nombre =
                            $candidats->count();

                        $admisCount =
                            $admis->count();

                        return (object) [
                            'classe' =>
                                $classe,

                            'candidats' =>
                                $nombre,

                            'admis' =>
                                $admisCount,

                            'echecs' => max(
                                0,
                                $nombre -
                                $admisCount
                            ),

                            'taux' =>
                                $nombre > 0
                                ? round(
                                    (
                                        $admisCount /
                                        $nombre
                                    ) * 100,
                                    2
                                )
                                : 0,
                        ];
                    }
                )
                ->values();

        $statistiquesMatieres =
            $examens
                ->groupBy('matiere')
                ->map(
                    function (
                        $notes,
                        $matiere
                    ) {
                        $moyenne =
                            $notes->count() > 0
                            ? round(
                                $notes->avg(
                                    function ($note) {
                                        return
                                            $note->note_maximale > 0
                                            ? (
                                                $note->note /
                                                $note->note_maximale
                                            ) * 20
                                            : 0;
                                    }
                                ),
                                2
                            )
                            : 0;

                        $meilleure =
                            $notes->max(
                                function ($note) {
                                    return
                                        $note->note_maximale > 0
                                        ? (
                                            $note->note /
                                            $note->note_maximale
                                        ) * 20
                                        : 0;
                                }
                            );

                        $minimale =
                            $notes->min(
                                function ($note) {
                                    return
                                        $note->note_maximale > 0
                                        ? (
                                            $note->note /
                                            $note->note_maximale
                                        ) * 20
                                        : 0;
                                }
                            );

                        return (object) [
                            'matiere' =>
                                $matiere,

                            'candidats' =>
                                $notes
                                    ->pluck('id_eleve')
                                    ->unique()
                                    ->count(),

                            'moyenne' =>
                                $moyenne,

                            'meilleure' =>
                                round(
                                    $meilleure,
                                    2
                                ),

                            'minimale' =>
                                round(
                                    $minimale,
                                    2
                                ),
                        ];
                    }
                )
                ->values();

        return view(
            'rapports.imprimer.examens-nationaux',
            compact(
                'examens',
                'nombreCandidats',
                'nombreAdmis',
                'nombreEchecs',
                'tauxReussite',
                'moyenneGenerale',
                'statistiquesClasses',
                'statistiquesMatieres'
            )
        );
    }


    /**
     * ==========================================================================
     * EXAMENS NATIONAUX EXCEL
     * ==========================================================================
     */
    public function examensNationauxExcel(
        Request $request
    ) {
        return Excel::download(
            new \App\Exports\ExamensNationauxExport(
                $request->annee
            ),
            'statistiques-examens-nationaux.xlsx'
        );
    }
}
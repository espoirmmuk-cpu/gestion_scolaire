<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use App\Models\Depense;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class CaisseController extends Controller
{
    /**
     * Situation de caisse et liste des mouvements.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR CONNECTÉ
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | REQUÊTE RECETTES
        |--------------------------------------------------------------------------
        */

        $recettesQuery = Recette::query();

        /*
        |--------------------------------------------------------------------------
        | REQUÊTE DÉPENSES
        |--------------------------------------------------------------------------
        */

        $depensesQuery = Depense::query();

        /*
        |--------------------------------------------------------------------------
        | FILTRE ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($user && $user->id_etablissement !== null) {

            $recettesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );

            $depensesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRE ANNÉE SCOLAIRE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_annee_scolaire')) {

            $recettesQuery->where(
                'id_annee_scolaire',
                $request->id_annee_scolaire
            );

            $depensesQuery->where(
                'id_annee_scolaire',
                $request->id_annee_scolaire
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRE DATE DÉBUT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_debut')) {

            $recettesQuery->whereDate(
                'date_recette',
                '>=',
                $request->date_debut
            );

            $depensesQuery->whereDate(
                'date_depense',
                '>=',
                $request->date_debut
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRE DATE FIN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_fin')) {

            $recettesQuery->whereDate(
                'date_recette',
                '<=',
                $request->date_fin
            );

            $depensesQuery->whereDate(
                'date_depense',
                '<=',
                $request->date_fin
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRE DEVISE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('devise')) {

            $recettesQuery->where(
                'devise',
                $request->devise
            );

            $depensesQuery->where(
                'devise',
                $request->devise
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRE TYPE DE MOUVEMENT
        |--------------------------------------------------------------------------
        |
        | Valeurs possibles :
        |
        | - tous
        | - recettes
        | - depenses
        |
        */

        $typeMouvement = $request->input(
            'type_mouvement',
            'tous'
        );

        /*
        |--------------------------------------------------------------------------
        | TOTAL RECETTES USD
        |--------------------------------------------------------------------------
        */

        $totalRecettesUSD = 0;

        if (
            $typeMouvement === 'tous'
            || $typeMouvement === 'recettes'
        ) {

            $totalRecettesUSD = (float) (
                (clone $recettesQuery)
                    ->where('devise', 'USD')
                    ->sum('montant')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL RECETTES CDF
        |--------------------------------------------------------------------------
        */

        $totalRecettesCDF = 0;

        if (
            $typeMouvement === 'tous'
            || $typeMouvement === 'recettes'
        ) {

            $totalRecettesCDF = (float) (
                (clone $recettesQuery)
                    ->where('devise', 'CDF')
                    ->sum('montant')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL DÉPENSES USD
        |--------------------------------------------------------------------------
        */

        $totalDepensesUSD = 0;

        if (
            $typeMouvement === 'tous'
            || $typeMouvement === 'depenses'
        ) {

            $totalDepensesUSD = (float) (
                (clone $depensesQuery)
                    ->where('devise', 'USD')
                    ->sum('montant')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL DÉPENSES CDF
        |--------------------------------------------------------------------------
        */

        $totalDepensesCDF = 0;

        if (
            $typeMouvement === 'tous'
            || $typeMouvement === 'depenses'
        ) {

            $totalDepensesCDF = (float) (
                (clone $depensesQuery)
                    ->where('devise', 'CDF')
                    ->sum('montant')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SOLDES
        |--------------------------------------------------------------------------
        */

        $soldeUSD =
            $totalRecettesUSD
            -
            $totalDepensesUSD;

        $soldeCDF =
            $totalRecettesCDF
            -
            $totalDepensesCDF;

        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRER LES RECETTES
        |--------------------------------------------------------------------------
        */

        $recettes = collect();

        if (
            $typeMouvement === 'tous'
            || $typeMouvement === 'recettes'
        ) {

            $recettes = $recettesQuery
                ->orderByDesc('date_recette')
                ->orderByDesc('id_recette')
                ->get()
                ->map(function ($recette) {

                    return [

                        'date' =>
                            $recette->date_recette,

                        'type' =>
                            'RECETTE',

                        'libelle' =>
                            $recette->source,

                        'description' =>
                            $recette->description,

                        'montant' =>
                            (float) $recette->montant,

                        'devise' =>
                            $recette->devise,

                        'id' =>
                            $recette->id_recette,

                    ];
                });
        }

        /*
        |--------------------------------------------------------------------------
        | RÉCUPÉRER LES DÉPENSES
        |--------------------------------------------------------------------------
        */

        $depenses = collect();

        if (
            $typeMouvement === 'tous'
            || $typeMouvement === 'depenses'
        ) {

            $depenses = $depensesQuery
                ->orderByDesc('date_depense')
                ->orderByDesc('id_depense')
                ->get()
                ->map(function ($depense) {

                    return [

                        'date' =>
                            $depense->date_depense,

                        'type' =>
                            'DÉPENSE',

                        /*
                        | La table depenses utilise
                        | "categorie" et non "motif".
                        */
                        'libelle' =>
                            $depense->categorie,

                        'description' =>
                            $depense->description,

                        'montant' =>
                            (float) $depense->montant,

                        'devise' =>
                            $depense->devise,

                        'id' =>
                            $depense->id_depense,

                    ];
                });
        }

        /*
        |--------------------------------------------------------------------------
        | FUSION DES MOUVEMENTS
        |--------------------------------------------------------------------------
        */

        $mouvements = $recettes
            ->concat($depenses)
            ->sortByDesc(function ($mouvement) {

                return $mouvement['date'];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | NOMBRE DE MOUVEMENTS
        |--------------------------------------------------------------------------
        */

        $nombreMouvements =
            $mouvements->count();

        /*
        |--------------------------------------------------------------------------
        | ANNÉES SCOLAIRES
        |--------------------------------------------------------------------------
        */

        $anneesScolaires =
            AnneeScolaire::orderByDesc(
                'id_annee_scolaire'
            )->get();

        /*
        |--------------------------------------------------------------------------
        | RETOURNER LA VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'caisse.index',
            compact(

                'mouvements',

                'nombreMouvements',

                'anneesScolaires',

                'totalRecettesUSD',

                'totalRecettesCDF',

                'totalDepensesUSD',

                'totalDepensesCDF',

                'soldeUSD',

                'soldeCDF',

                'typeMouvement'

            )
        );
    }
}
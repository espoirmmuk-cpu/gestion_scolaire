<?php

namespace App\Http\Controllers;

use App\Models\Recette;
use App\Models\Depense;
use App\Models\AnneeScolaire;
use App\Models\JournalActivite;
use Illuminate\Http\Request;

class RecetteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | RECETTES
        |--------------------------------------------------------------------------
        */

        $query = Recette::with([
            'anneeScolaire',
            'utilisateur',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($user->id_etablissement !== null) {

            $query->where(
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

            $query->where(
                'id_annee_scolaire',
                $request->id_annee_scolaire
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE DÉBUT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_debut')) {

            $query->whereDate(
                'date_recette',
                '>=',
                $request->date_debut
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FIN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_fin')) {

            $query->whereDate(
                'date_recette',
                '<=',
                $request->date_fin
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SOURCE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('source')) {

            $query->where(
                'source',
                'like',
                '%' . $request->source . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEVISE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('devise')) {

            $query->where(
                'devise',
                $request->devise
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TRI
        |--------------------------------------------------------------------------
        */

        $query
            ->orderByDesc('date_recette')
            ->orderByDesc('id_recette');

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $recettes = $query
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ANNÉES SCOLAIRES
        |--------------------------------------------------------------------------
        */

        $anneesScolaires = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | SOURCES
        |--------------------------------------------------------------------------
        */

        $sourcesQuery = Recette::query();

        if ($user->id_etablissement !== null) {

            $sourcesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $sources = $sourcesQuery
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');
/*
|--------------------------------------------------------------------------
| DEVISES
|--------------------------------------------------------------------------
*/

$devisesQuery = Recette::query();

if ($user->id_etablissement !== null) {

    $devisesQuery->where(
        'id_etablissement',
        $user->id_etablissement
    );
}

$devises = $devisesQuery
    ->whereNotNull('devise')
    ->where('devise', '!=', '')
    ->distinct()
    ->orderBy('devise')
    ->pluck('devise');

      /*
|--------------------------------------------------------------------------
| SITUATION DE CAISSE
|--------------------------------------------------------------------------
|
| Les calculs sont indépendants de la pagination.
|
*/

$recettesCaisseQuery = Recette::query();

$depensesCaisseQuery = Depense::query();


/*
|--------------------------------------------------------------------------
| FILTRE ÉTABLISSEMENT
|--------------------------------------------------------------------------
*/

if ($user->id_etablissement !== null) {

    $recettesCaisseQuery->where(
        'id_etablissement',
        $user->id_etablissement
    );

    $depensesCaisseQuery->where(
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

    $recettesCaisseQuery->where(
        'id_annee_scolaire',
        $request->id_annee_scolaire
    );

    $depensesCaisseQuery->where(
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

    $recettesCaisseQuery->whereDate(
        'date_recette',
        '>=',
        $request->date_debut
    );

    $depensesCaisseQuery->whereDate(
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

    $recettesCaisseQuery->whereDate(
        'date_recette',
        '<=',
        $request->date_fin
    );

    $depensesCaisseQuery->whereDate(
        'date_depense',
        '<=',
        $request->date_fin
    );
}


/*
|--------------------------------------------------------------------------
| FILTRE SOURCE POUR LES TOTAUX
|--------------------------------------------------------------------------
*/

if ($request->filled('source')) {

    $recettesCaisseQuery->where(
        'source',
        'like',
        '%' . $request->source . '%'
    );
}


/*
|--------------------------------------------------------------------------
| FILTRE DEVISE POUR LES TOTAUX
|--------------------------------------------------------------------------
|
| Le total de chaque devise reste calculé séparément.
|
*/


/*
|--------------------------------------------------------------------------
| TOTAL RECETTES USD
|--------------------------------------------------------------------------
*/

$totalRecettesUSD = (float) (
    (clone $recettesCaisseQuery)
        ->where('devise', 'USD')
        ->sum('montant')
);


/*
|--------------------------------------------------------------------------
| TOTAL RECETTES CDF
|--------------------------------------------------------------------------
*/

$totalRecettesCDF = (float) (
    (clone $recettesCaisseQuery)
        ->where('devise', 'CDF')
        ->sum('montant')
);


/*
|--------------------------------------------------------------------------
| TOTAL DÉPENSES USD
|--------------------------------------------------------------------------
*/

$totalDepensesUSD = (float) (
    (clone $depensesCaisseQuery)
        ->where('devise', 'USD')
        ->sum('montant')
);


/*
|--------------------------------------------------------------------------
| TOTAL DÉPENSES CDF
|--------------------------------------------------------------------------
*/

$totalDepensesCDF = (float) (
    (clone $depensesCaisseQuery)
        ->where('devise', 'CDF')
        ->sum('montant')
);


/*
|--------------------------------------------------------------------------
| SOLDES DE CAISSE
|--------------------------------------------------------------------------
*/

$soldeUSD =
    $totalRecettesUSD -
    $totalDepensesUSD;

$soldeCDF =
    $totalRecettesCDF -
    $totalDepensesCDF;


/*
|--------------------------------------------------------------------------
| TOTAUX DE LA LISTE DES RECETTES
|--------------------------------------------------------------------------
*/

$totalUsd = (float) (
    (clone $query)
        ->where('devise', 'USD')
        ->sum('montant')
);

$totalCdf = (float) (
    (clone $query)
        ->where('devise', 'CDF')
        ->sum('montant')
);


/*
|--------------------------------------------------------------------------
| NOM DE LA SECTION
|--------------------------------------------------------------------------
*/

$nomRecettes = 'Recettes';

$nombreRecettes = (clone $query)->count();

$totalUsd = (float) (
    (clone $query)
        ->where('devise', 'USD')
        ->sum('montant')
);

$totalCdf = (float) (
    (clone $query)
        ->where('devise', 'CDF')
        ->sum('montant')
);

/*
|--------------------------------------------------------------------------
| RETOUR VUE
|--------------------------------------------------------------------------
*/

return view(
    'recettes.index',
    compact(
        'recettes',
        'anneesScolaires',
        'sources',
        'devises',

        'nomRecettes',
        'nombreRecettes',

        'totalUsd',
        'totalCdf',

        'totalRecettesUSD',
        'totalRecettesCDF',

        'totalDepensesUSD',
        'totalDepensesCDF',

        'soldeUSD',
        'soldeCDF'
    )
);
    }
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $anneesScolaires = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();

        return view(
            'recettes.create',
            compact('anneesScolaires')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'id_annee_scolaire' => [
                'required',
                'integer',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'date_recette' => [
                'required',
                'date',
            ],

            'source' => [
                'required',
                'string',
                'max:255',
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0',
            ],

            'devise' => [
                'required',
                'string',
                'max:10',
                'in:USD,CDF',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT AUTOMATIQUE
        |--------------------------------------------------------------------------
        */

        $validated['id_etablissement'] =
            $user->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR
        |--------------------------------------------------------------------------
        */

        $validated['id_utilisateur'] =
            $user->id_utilisateur ?? auth()->id();


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        $recette = Recette::create($validated);


        /*
        |--------------------------------------------------------------------------
        | JOURNALISATION
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' =>
                $validated['id_utilisateur'],

            'action' =>
                'Ajout d’une recette',

            'table_concernee' =>
                'recettes',

            'id_enregistrement' =>
                $recette->id_recette,

            'anciennes_valeurs' =>
                null,

            'nouvelles_valeurs' =>
                json_encode(
                    $recette->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),

            'adresse_ip' =>
                $request->ip(),

            'navigateur' =>
                $request->userAgent(),

            'date_heure' =>
                now(),
        ]);


        return redirect()
            ->route('recettes.index')
            ->with(
                'success',
                'Recette enregistrée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Recette $recette)
    {
        $this->verifierEtablissement($recette);

        $recette->load([
            'anneeScolaire',
            'utilisateur',
        ]);

        return view(
            'recettes.show',
            compact('recette')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Recette $recette)
    {
        $this->verifierEtablissement($recette);

        $anneesScolaires = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();

        return view(
            'recettes.edit',
            compact(
                'recette',
                'anneesScolaires'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Recette $recette
    ) {
        $this->verifierEtablissement($recette);

        $validated = $request->validate([

            'id_annee_scolaire' => [
                'required',
                'integer',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'date_recette' => [
                'required',
                'date',
            ],

            'source' => [
                'required',
                'string',
                'max:255',
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0',
            ],

            'devise' => [
                'required',
                'string',
                'max:10',
                'in:USD,CDF',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $anciennesValeurs =
            $recette->getAttributes();


        $recette->update($validated);


        JournalActivite::create([

            'id_utilisateur' =>
                auth()->user()->id_utilisateur
                ?? auth()->id(),

            'action' =>
                'Modification d’une recette',

            'table_concernee' =>
                'recettes',

            'id_enregistrement' =>
                $recette->id_recette,

            'anciennes_valeurs' =>
                json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

            'nouvelles_valeurs' =>
                json_encode(
                    $recette->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),

            'adresse_ip' =>
                $request->ip(),

            'navigateur' =>
                $request->userAgent(),

            'date_heure' =>
                now(),
        ]);


        return redirect()
            ->route('recettes.index')
            ->with(
                'success',
                'Recette modifiée avec succès.'
            );
    }
/*
|--------------------------------------------------------------------------
| REÇU
|--------------------------------------------------------------------------
*/

public function recu(Recette $recette)
{
    $this->verifierEtablissement($recette);

    $recette->load([
        'anneeScolaire',
        'utilisateur',
        'etablissement',
    ]);

    return view(
        'recettes.recu',
        compact('recette')
    );
}

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Recette $recette)
    {
        $this->verifierEtablissement($recette);

        $anciennesValeurs =
            $recette->getAttributes();

        try {

            $recette->delete();


            JournalActivite::create([

                'id_utilisateur' =>
                    auth()->user()->id_utilisateur
                    ?? auth()->id(),

                'action' =>
                    'Suppression d’une recette',

                'table_concernee' =>
                    'recettes',

                'id_enregistrement' =>
                    $recette->id_recette,

                'anciennes_valeurs' =>
                    json_encode(
                        $anciennesValeurs,
                        JSON_UNESCAPED_UNICODE
                    ),

                'nouvelles_valeurs' =>
                    null,

                'adresse_ip' =>
                    request()->ip(),

                'navigateur' =>
                    request()->userAgent(),

                'date_heure' =>
                    now(),
            ]);


            return redirect()
                ->route('recettes.index')
                ->with(
                    'success',
                    'Recette supprimée avec succès.'
                );

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()
                ->route('recettes.index')
                ->with(
                    'error',
                    'Cette recette ne peut pas être supprimée.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VÉRIFICATION ÉTABLISSEMENT
    |--------------------------------------------------------------------------
    */

    private function verifierEtablissement(
        Recette $recette
    ): void {

        $user = auth()->user();


        if (
            $user->id_etablissement !== null &&
            (int) $recette->id_etablissement !==
            (int) $user->id_etablissement
        ) {

            abort(
                403,
                'Cette recette n’appartient pas à votre établissement.'
            );
        }
    }
}
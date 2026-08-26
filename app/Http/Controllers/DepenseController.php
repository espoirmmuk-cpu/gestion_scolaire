<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\AnneeScolaire;
use App\Models\JournalActivite;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Depense::with([
            'etablissement',
            'anneeScolaire',
            'utilisateur',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Filtre établissement
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
        | Filtre année scolaire
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
        | Filtre catégorie
        |--------------------------------------------------------------------------
        */

        if ($request->filled('categorie')) {

            $query->where(
                'categorie',
                'like',
                '%' . $request->categorie . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre devise
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
        | Filtre date début
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_debut')) {

            $query->whereDate(
                'date_depense',
                '>=',
                $request->date_debut
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre date fin
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_fin')) {

            $query->whereDate(
                'date_depense',
                '<=',
                $request->date_fin
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tri
        |--------------------------------------------------------------------------
        */

        $query
            ->orderByDesc('date_depense')
            ->orderByDesc('id_depense');

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $depenses = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */

        $anneesScolaires = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();

        /*
        |--------------------------------------------------------------------------
        | Catégories
        |--------------------------------------------------------------------------
        */

        $categoriesQuery = Depense::query();

        if ($user->id_etablissement !== null) {

            $categoriesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $categories = $categoriesQuery
            ->whereNotNull('categorie')
            ->where('categorie', '!=', '')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        /*
        |--------------------------------------------------------------------------
        | Devises
        |--------------------------------------------------------------------------
        */

        $devises = ['USD', 'CDF'];
        $nomDepenses = 'Dépenses';

        $nombreDepenses = (clone $query)->count();

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
| SITUATION DE CAISSE
|--------------------------------------------------------------------------
*/

$recettesCaisseQuery = \App\Models\Recette::query();
$depensesCaisseQuery = \App\Models\Depense::query();

$user = auth()->user();

/*
|--------------------------------------------------------------------------
| ÉTABLISSEMENT
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
| ANNÉE SCOLAIRE
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
| DATE DÉBUT
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
| DATE FIN
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
| RECETTES
|--------------------------------------------------------------------------
*/

$totalRecettesUSD = (float) (
    (clone $recettesCaisseQuery)
        ->where('devise', 'USD')
        ->sum('montant')
);

$totalRecettesCDF = (float) (
    (clone $recettesCaisseQuery)
        ->where('devise', 'CDF')
        ->sum('montant')
);

/*
|--------------------------------------------------------------------------
| DÉPENSES
|--------------------------------------------------------------------------
*/

$totalDepensesUSD = (float) (
    (clone $depensesCaisseQuery)
        ->where('devise', 'USD')
        ->sum('montant')
);

$totalDepensesCDF = (float) (
    (clone $depensesCaisseQuery)
        ->where('devise', 'CDF')
        ->sum('montant')
);

/*
|--------------------------------------------------------------------------
| SOLDES
|--------------------------------------------------------------------------
*/

$soldeUSD = $totalRecettesUSD - $totalDepensesUSD;

$soldeCDF = $totalRecettesCDF - $totalDepensesCDF;

        return view(
    'depenses.index',
    compact(
        'depenses',
        'anneesScolaires',
        'categories',
        'devises',

        'nomDepenses',
        'nombreDepenses',

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
            'depenses.create',
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
                'nullable',
                'integer',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'date_depense' => [
                'required',
                'date',
            ],

            'categorie' => [
                'required',
                'string',
                'max:150',
            ],

            'montant' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'devise' => [
                'required',
                'in:USD,CDF',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Établissement automatique
        |--------------------------------------------------------------------------
        */

        if ($user->id_etablissement === null) {

            abort(
                403,
                'Vous devez être rattaché à un établissement pour enregistrer une dépense.'
            );
        }

        $validated['id_etablissement'] =
            $user->id_etablissement;

        /*
        |--------------------------------------------------------------------------
        | Utilisateur
        |--------------------------------------------------------------------------
        */

        $validated['id_utilisateur'] =
            $user->id_utilisateur ?? auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        $depense = Depense::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' =>
                $validated['id_utilisateur'],

            'action' =>
                'Ajout d’une dépense',

            'table_concernee' =>
                'depenses',

            'id_enregistrement' =>
                $depense->id_depense,

            'anciennes_valeurs' =>
                null,

            'nouvelles_valeurs' =>
                json_encode(
                    $depense->getAttributes(),
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
            ->route('depenses.index')
            ->with(
                'success',
                'Dépense enregistrée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Depense $depense)
    {
        $this->verifierEtablissement($depense);

        $depense->load([
            'etablissement',
            'anneeScolaire',
            'utilisateur',
        ]);

        return view(
            'depenses.show',
            compact('depense')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Depense $depense)
    {
        $this->verifierEtablissement($depense);

        $anneesScolaires = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();

        return view(
            'depenses.edit',
            compact(
                'depense',
                'anneesScolaires'
            )
        );
    }
/*
|--------------------------------------------------------------------------
| BON DE SORTIE
|--------------------------------------------------------------------------
*/

public function bonSortie(Depense $depense)
{
    $this->verifierEtablissement($depense);

    $depense->load([
        'anneeScolaire',
        'utilisateur',
        'etablissement',
    ]);

    return view(
        'depenses.bon-sortie',
        compact('depense')
    );
}

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Depense $depense
    ) {
        $this->verifierEtablissement($depense);

        $validated = $request->validate([

            'id_annee_scolaire' => [
                'nullable',
                'integer',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'date_depense' => [
                'required',
                'date',
            ],

            'categorie' => [
                'required',
                'string',
                'max:150',
            ],

            'montant' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'devise' => [
                'required',
                'in:USD,CDF',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $depense->getAttributes();

        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */

        $depense->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' =>
                auth()->user()->id_utilisateur
                ?? auth()->id(),

            'action' =>
                'Modification d’une dépense',

            'table_concernee' =>
                'depenses',

            'id_enregistrement' =>
                $depense->id_depense,

            'anciennes_valeurs' =>
                json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

            'nouvelles_valeurs' =>
                json_encode(
                    $depense->getAttributes(),
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
            ->route('depenses.index')
            ->with(
                'success',
                'Dépense modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Depense $depense)
    {
        $this->verifierEtablissement($depense);

        $anciennesValeurs =
            $depense->getAttributes();

        try {

            $depense->delete();

            JournalActivite::create([

                'id_utilisateur' =>
                    auth()->user()->id_utilisateur
                    ?? auth()->id(),

                'action' =>
                    'Suppression d’une dépense',

                'table_concernee' =>
                    'depenses',

                'id_enregistrement' =>
                    $depense->id_depense,

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
                ->route('depenses.index')
                ->with(
                    'success',
                    'Dépense supprimée avec succès.'
                );

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()
                ->route('depenses.index')
                ->with(
                    'error',
                    'Cette dépense ne peut pas être supprimée.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Vérification établissement
    |--------------------------------------------------------------------------
    */

    private function verifierEtablissement(
        Depense $depense
    ): void {

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Administrateur global
        |--------------------------------------------------------------------------
        */

        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Utilisateur rattaché à un établissement
        |--------------------------------------------------------------------------
        */

        if (
            $user->id_etablissement === null ||
            (int) $depense->id_etablissement !==
            (int) $user->id_etablissement
        ) {

            abort(
                403,
                'Cette dépense n’appartient pas à votre établissement.'
            );
        }
    }

    /*
|--------------------------------------------------------------------------
| BON DE SORTIE
|--------------------------------------------------------------------------
*/

public function bon(Depense $depense)
{
    $this->verifierEtablissement($depense);

    $depense->load([
        'anneeScolaire',
        'utilisateur',
    ]);

    return view(
        'depenses.bon',
        compact('depense')
    );
}
}

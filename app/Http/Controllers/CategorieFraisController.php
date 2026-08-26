<?php

namespace App\Http\Controllers;

use App\Models\CategorieFrais;
use App\Models\JournalActivite;
use Illuminate\Http\Request;

class CategorieFraisController extends Controller
{
    /**
     * Liste des catégories de frais.
     */
    public function index()
    {
        $user = auth()->user();

        $query = CategorieFrais::query();

        /*
        |--------------------------------------------------------------------------
        | Filtrage par établissement
        |--------------------------------------------------------------------------
        |
        | Un administrateur sans établissement peut voir toutes les catégories.
        | Les autres utilisateurs voient uniquement les catégories de leur
        | établissement.
        |
        */

        if (
            $user->id_etablissement !== null
        ) {
            $query->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $categories = $query
            ->orderBy('libelle')
            ->get();

        return view(
            'categories_frais.index',
            compact('categories')
        );
    }


    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        return view('categories_frais.create');
    }


    /**
     * Enregistrement d'une catégorie.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'libelle' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'required',
                'in:ACTIVE,INACTIVE',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'établissement
        |--------------------------------------------------------------------------
        */

        if ($user->id_etablissement === null) {

            abort(
                403,
                'Aucun établissement n’est associé à votre compte.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'unicité dans l'établissement
        |--------------------------------------------------------------------------
        */

        $existe = CategorieFrais::where(
                'id_etablissement',
                $user->id_etablissement
            )
            ->where(
                'libelle',
                $validated['libelle']
            )
            ->exists();


        if ($existe) {

            return back()
                ->withInput()
                ->withErrors([
                    'libelle' =>
                        'Cette catégorie existe déjà dans votre établissement.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Ajouter l'établissement
        |--------------------------------------------------------------------------
        */

        $validated['id_etablissement'] =
            $user->id_etablissement;


        $categorie = CategorieFrais::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),

            'action' =>
                'Ajout d’une catégorie de frais',

            'table_concernee' =>
                'categories_frais',

            'id_enregistrement' =>
                $categorie->id_categorie_frais,

            'anciennes_valeurs' =>
                null,

            'nouvelles_valeurs' =>
                json_encode(
                    $categorie->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),

            'adresse_ip' =>
                request()->ip(),

            'navigateur' =>
                request()->userAgent(),

            'date_heure' =>
                now(),
        ]);


        return redirect()
            ->route('categories-frais.index')
            ->with(
                'success',
                'Catégorie de frais ajoutée avec succès.'
            );
    }


    /**
     * Affichage d'une catégorie.
     */
    public function show(
        CategorieFrais $categorieFrais
    ) {

        $this->verifierEtablissement(
            $categorieFrais
        );

        return view(
            'categories_frais.show',
            compact('categorieFrais')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(
        CategorieFrais $categorieFrais
    ) {

        $this->verifierEtablissement(
            $categorieFrais
        );

        return view(
            'categories_frais.edit',
            compact('categorieFrais')
        );
    }


    /**
     * Modification d'une catégorie.
     */
    public function update(
        Request $request,
        CategorieFrais $categorieFrais
    ) {

        $this->verifierEtablissement(
            $categorieFrais
        );


        $user = auth()->user();


        $validated = $request->validate([
            'libelle' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'statut' => [
                'required',
                'in:ACTIVE,INACTIVE',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'unicité dans l'établissement
        |--------------------------------------------------------------------------
        */

        $existe = CategorieFrais::where(
                'id_etablissement',
                $user->id_etablissement
            )
            ->where(
                'libelle',
                $validated['libelle']
            )
            ->where(
                'id_categorie_frais',
                '!=',
                $categorieFrais->id_categorie_frais
            )
            ->exists();


        if ($existe) {

            return back()
                ->withInput()
                ->withErrors([
                    'libelle' =>
                        'Cette catégorie existe déjà dans votre établissement.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $categorieFrais->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | Modification
        |--------------------------------------------------------------------------
        */

        $categorieFrais->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' =>
                auth()->id(),

            'action' =>
                'Modification d’une catégorie de frais',

            'table_concernee' =>
                'categories_frais',

            'id_enregistrement' =>
                $categorieFrais->id_categorie_frais,

            'anciennes_valeurs' =>
                json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

            'nouvelles_valeurs' =>
                json_encode(
                    $validated,
                    JSON_UNESCAPED_UNICODE
                ),

            'adresse_ip' =>
                request()->ip(),

            'navigateur' =>
                request()->userAgent(),

            'date_heure' =>
                now(),
        ]);


        return redirect()
            ->route('categories-frais.index')
            ->with(
                'success',
                'Catégorie de frais modifiée avec succès.'
            );
    }


    /**
     * Suppression d'une catégorie.
     */
    public function destroy(
        CategorieFrais $categorieFrais
    ) {

        $this->verifierEtablissement(
            $categorieFrais
        );


        try {

            /*
            |--------------------------------------------------------------------------
            | Anciennes valeurs
            |--------------------------------------------------------------------------
            */

            $anciennesValeurs =
                $categorieFrais->getAttributes();


            /*
            |--------------------------------------------------------------------------
            | ID avant suppression
            |--------------------------------------------------------------------------
            */

            $idCategorie =
                $categorieFrais->id_categorie_frais;


            /*
            |--------------------------------------------------------------------------
            | Suppression
            |--------------------------------------------------------------------------
            */

            $categorieFrais->delete();


            /*
            |--------------------------------------------------------------------------
            | Journalisation
            |--------------------------------------------------------------------------
            */

            JournalActivite::create([
                'id_utilisateur' =>
                    auth()->id(),

                'action' =>
                    'Suppression d’une catégorie de frais',

                'table_concernee' =>
                    'categories_frais',

                'id_enregistrement' =>
                    $idCategorie,

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
                ->route('categories-frais.index')
                ->with(
                    'success',
                    'Catégorie de frais supprimée avec succès.'
                );


        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()
                ->route('categories-frais.index')
                ->with(
                    'error',
                    'Cette catégorie ne peut pas être supprimée car elle est utilisée par un tarif scolaire.'
                );
        }
    }


    /**
     * Vérifier que la catégorie appartient à l'établissement
     * de l'utilisateur connecté.
     */
    private function verifierEtablissement(
        CategorieFrais $categorieFrais
    ): void {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Administrateur global
        |--------------------------------------------------------------------------
        */

        if (
            $user->id_etablissement === null
            && $user->aLeRole('Administrateur')
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Utilisateur sans établissement
        |--------------------------------------------------------------------------
        */

        if (
            $user->id_etablissement === null
        ) {

            abort(
                403,
                'Votre compte n’est associé à aucun établissement.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Mauvais établissement
        |--------------------------------------------------------------------------
        */

        if (
            $categorieFrais->id_etablissement
            != $user->id_etablissement
        ) {

            abort(
                403,
                'Vous n’avez pas accès à cette catégorie de frais.'
            );
        }
    }
}
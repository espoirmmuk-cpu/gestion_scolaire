<?php

namespace App\Http\Controllers;

use App\Models\TarifScolaire;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\CategorieFrais;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarifScolaireController extends Controller
{
    /**
     * Liste des tarifs scolaires.
     */
    public function index()
    {
        $user = Auth::user();

        $query = TarifScolaire::with([
            'anneeScolaire',
            'classe',
            'categorieFrais'
        ]);

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMINISTRATEUR
        |--------------------------------------------------------------------------
        |
        | Administrateur sans établissement = accès global.
        |
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            /*
            |----------------------------------------------------------------------
            | Utilisateur d'un établissement
            |----------------------------------------------------------------------
            */

            $query->whereHas('classe', function ($q) use ($user) {
                $q->where(
                    'id_etablissement',
                    $user->id_etablissement
                );
            });
        }

        $tarifs = $query
            ->orderByDesc('id_tarif')
            ->get();

        return view(
            'tarifs_scolaires.index',
            compact('tarifs')
        );
    }


    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */

        $annees = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classesQuery = Classe::query();

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            $classesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $classes = $classesQuery
            ->orderBy('libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Catégories de frais
        |--------------------------------------------------------------------------
        */

        $categoriesQuery = CategorieFrais::query()
            ->where('statut', 'ACTIVE');

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            $categoriesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $categories = $categoriesQuery
            ->orderBy('libelle')
            ->get();


        return view(
            'tarifs_scolaires.create',
            compact(
                'annees',
                'classes',
                'categories'
            )
        );
    }


    /**
     * Enregistrer un tarif.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Validation de base
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_annee_scolaire' => [
                'required',
                'exists:annees_scolaires,id_annee_scolaire'
            ],

            'id_classe' => [
                'required',
                'exists:classes,id_classe'
            ],

            'id_categorie_frais' => [
                'required',
                'exists:categories_frais,id_categorie_frais'
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0'
            ],

            'devise' => [
                'required',
                'in:USD,CDF'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérification de l'établissement
        |--------------------------------------------------------------------------
        */

        $classe = Classe::findOrFail(
            $validated['id_classe']
        );

        $categorie = CategorieFrais::findOrFail(
            $validated['id_categorie_frais']
        );


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'accès à la classe
        |--------------------------------------------------------------------------
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            if (
                $classe->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à cette classe.'
                );
            }


            /*
            |----------------------------------------------------------------------
            | Vérifier l'accès à la catégorie
            |----------------------------------------------------------------------
            */

            if (
                $categorie->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à cette catégorie de frais.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier que classe et catégorie appartiennent au même établissement
        |--------------------------------------------------------------------------
        */

        if (
            $classe->id_etablissement
            !== $categorie->id_etablissement
        ) {

            abort(
                403,
                'La classe et la catégorie de frais ne correspondent pas au même établissement.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier les doublons
        |--------------------------------------------------------------------------
        */

        $existe = TarifScolaire::where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
        ->where(
            'id_classe',
            $validated['id_classe']
        )
        ->where(
            'id_categorie_frais',
            $validated['id_categorie_frais']
        )
        ->exists();


        if ($existe) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Un tarif existe déjà pour cette année, cette classe et cette catégorie de frais.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Création du tarif
        |--------------------------------------------------------------------------
        */

        $tarifScolaire = TarifScolaire::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' => Auth::id(),

            'action' => 'Ajout d’un tarif scolaire',

            'table_concernee' => 'tarifs_scolaires',

            'id_enregistrement' =>
                $tarifScolaire->id_tarif,

            'anciennes_valeurs' => null,

            'nouvelles_valeurs' => json_encode(
                $tarifScolaire
                    ->fresh()
                    ->toArray(),
                JSON_UNESCAPED_UNICODE
            ),

            'adresse_ip' => $request->ip(),

            'navigateur' => $request->userAgent(),

            'date_heure' => now(),
        ]);


        return redirect()
            ->route('tarifs-scolaires.index')
            ->with(
                'success',
                'Tarif scolaire ajouté avec succès.'
            );
    }


    /**
     * Afficher un tarif.
     */
    public function show(
        TarifScolaire $tarifScolaire
    ) {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Vérification de l'établissement
        |--------------------------------------------------------------------------
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            if (
                !$tarifScolaire->classe ||
                $tarifScolaire->classe->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à ce tarif.'
                );
            }
        }


        $tarifScolaire->load([
            'anneeScolaire',
            'classe',
            'categorieFrais'
        ]);


        return view(
            'tarifs_scolaires.show',
            compact('tarifScolaire')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(
        TarifScolaire $tarifScolaire
    ) {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'accès au tarif
        |--------------------------------------------------------------------------
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            if (
                !$tarifScolaire->classe ||
                $tarifScolaire->classe->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à ce tarif.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */

        $annees = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classesQuery = Classe::query();

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            $classesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $classes = $classesQuery
            ->orderBy('libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Catégories
        |--------------------------------------------------------------------------
        */

        $categoriesQuery = CategorieFrais::query()
            ->where('statut', 'ACTIVE');

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            $categoriesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $categories = $categoriesQuery
            ->orderBy('libelle')
            ->get();


        return view(
            'tarifs_scolaires.edit',
            compact(
                'tarifScolaire',
                'annees',
                'classes',
                'categories'
            )
        );
    }


    /**
     * Modifier un tarif.
     */
    public function update(
        Request $request,
        TarifScolaire $tarifScolaire
    ) {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'accès au tarif existant
        |--------------------------------------------------------------------------
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            if (
                !$tarifScolaire->classe ||
                $tarifScolaire->classe->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à ce tarif.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_annee_scolaire' => [
                'required',
                'exists:annees_scolaires,id_annee_scolaire'
            ],

            'id_classe' => [
                'required',
                'exists:classes,id_classe'
            ],

            'id_categorie_frais' => [
                'required',
                'exists:categories_frais,id_categorie_frais'
            ],

            'montant' => [
                'required',
                'numeric',
                'min:0'
            ],

            'devise' => [
                'required',
                'in:USD,CDF'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier classe et catégorie
        |--------------------------------------------------------------------------
        */

        $classe = Classe::findOrFail(
            $validated['id_classe']
        );

        $categorie = CategorieFrais::findOrFail(
            $validated['id_categorie_frais']
        );


        /*
        |--------------------------------------------------------------------------
        | Vérifier établissement
        |--------------------------------------------------------------------------
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            if (
                $classe->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à cette classe.'
                );
            }


            if (
                $categorie->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à cette catégorie de frais.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Classe et catégorie doivent appartenir au même établissement
        |--------------------------------------------------------------------------
        */

        if (
            $classe->id_etablissement
            !== $categorie->id_etablissement
        ) {

            abort(
                403,
                'La classe et la catégorie de frais ne correspondent pas au même établissement.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier doublon
        |--------------------------------------------------------------------------
        */

        $existe = TarifScolaire::where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
        ->where(
            'id_classe',
            $validated['id_classe']
        )
        ->where(
            'id_categorie_frais',
            $validated['id_categorie_frais']
        )
        ->where(
            'id_tarif',
            '!=',
            $tarifScolaire->id_tarif
        )
        ->exists();


        if ($existe) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Un autre tarif existe déjà pour cette année, cette classe et cette catégorie.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $tarifScolaire->toArray();


        /*
        |--------------------------------------------------------------------------
        | Modification
        |--------------------------------------------------------------------------
        */

        $tarifScolaire->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Nouvelles valeurs
        |--------------------------------------------------------------------------
        */

        $nouvellesValeurs =
            $tarifScolaire
                ->fresh()
                ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' => Auth::id(),

            'action' =>
                'Modification d’un tarif scolaire',

            'table_concernee' =>
                'tarifs_scolaires',

            'id_enregistrement' =>
                $tarifScolaire->id_tarif,

            'anciennes_valeurs' =>
                json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

            'nouvelles_valeurs' =>
                json_encode(
                    $nouvellesValeurs,
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
            ->route('tarifs-scolaires.index')
            ->with(
                'success',
                'Tarif scolaire modifié avec succès.'
            );
    }


    /**
     * Supprimer un tarif.
     */
    public function destroy(
        Request $request,
        TarifScolaire $tarifScolaire
    ) {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'accès au tarif
        |--------------------------------------------------------------------------
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            if (
                !$tarifScolaire->classe ||
                $tarifScolaire->classe->id_etablissement
                != $user->id_etablissement
            ) {

                abort(
                    403,
                    'Vous n’avez pas accès à ce tarif.'
                );
            }
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | Anciennes valeurs
            |--------------------------------------------------------------------------
            */

            $anciennesValeurs =
                $tarifScolaire->toArray();

            $idTarif =
                $tarifScolaire->id_tarif;


            /*
            |--------------------------------------------------------------------------
            | Suppression
            |--------------------------------------------------------------------------
            */

            $tarifScolaire->delete();


            /*
            |--------------------------------------------------------------------------
            | Journalisation
            |--------------------------------------------------------------------------
            */

            JournalActivite::create([

                'id_utilisateur' =>
                    Auth::id(),

                'action' =>
                    'Suppression d’un tarif scolaire',

                'table_concernee' =>
                    'tarifs_scolaires',

                'id_enregistrement' =>
                    $idTarif,

                'anciennes_valeurs' =>
                    json_encode(
                        $anciennesValeurs,
                        JSON_UNESCAPED_UNICODE
                    ),

                'nouvelles_valeurs' =>
                    null,

                'adresse_ip' =>
                    $request->ip(),

                'navigateur' =>
                    $request->userAgent(),

                'date_heure' =>
                    now(),
            ]);


            return redirect()
                ->route('tarifs-scolaires.index')
                ->with(
                    'success',
                    'Tarif scolaire supprimé avec succès.'
                );

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()
                ->route('tarifs-scolaires.index')
                ->with(
                    'error',
                    'Ce tarif ne peut pas être supprimé car il est déjà utilisé par un élève.'
                );
        }
    }
}
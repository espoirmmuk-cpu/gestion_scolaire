<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\JournalActivite;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * Retourne l'ID de l'établissement courant.
     *
     * Administrateur sans établissement = accès global.
     */
    private function etablissementCourant()
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        return $user->id_etablissement;
    }


    /**
     * Afficher la liste du personnel.
     */
    public function index(Request $request)
    {
        $query = Personnel::query();

        /*
        |--------------------------------------------------------------------------
        | FILTRAGE PAR ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        $idEtablissement = $this->etablissementCourant();

        if ($idEtablissement !== null) {
            $query->where(
                'id_etablissement',
                $idEtablissement
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('matricule', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%")
                  ->orWhere('postnom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE SEXE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('sexe')) {
            $query->where(
                'sexe',
                $request->sexe
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE FONCTION
        |--------------------------------------------------------------------------
        */

        if ($request->filled('fonction')) {

            $query->where(
                'fonction',
                'like',
                '%' . $request->fonction . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE STATUT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('statut')) {

            $query->where(
                'statut',
                $request->statut
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $personnels = $query
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | FONCTIONS
        |--------------------------------------------------------------------------
        */

        $fonctionsQuery = Personnel::query()
            ->whereNotNull('fonction')
            ->where('fonction', '!=', '');


        if ($idEtablissement !== null) {

            $fonctionsQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }


        $fonctions = $fonctionsQuery
            ->distinct()
            ->orderBy('fonction')
            ->pluck('fonction');


        return view(
            'personnel.index',
            compact(
                'personnels',
                'fonctions'
            )
        );
    }


    /**
     * Afficher le formulaire d'ajout.
     */
    public function create()
    {
        return view('personnel.create');
    }


    /**
     * Enregistrer un nouveau membre du personnel.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'matricule' => [
                'required',
                'string',
                'max:50',
            ],

            'nom' => [
                'required',
                'string',
                'max:100'
            ],

            'postnom' => [
                'nullable',
                'string',
                'max:100'
            ],

            'prenom' => [
                'nullable',
                'string',
                'max:100'
            ],

            'sexe' => [
                'required',
                'in:M,F'
            ],

            'fonction' => [
                'required',
                'string',
                'max:100'
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:150'
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'email' => [
                'nullable',
                'email',
                'max:150'
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255'
            ],

            'date_engagement' => [
                'nullable',
                'date'
            ],

            'statut' => [
                'required',
                'in:ACTIF,INACTIF,SUSPENDU'
            ],

            'photo' => [
                'nullable',
                'string',
                'max:255'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT AUTOMATIQUE
        |--------------------------------------------------------------------------
        */

        $idEtablissement = $this->etablissementCourant();

        if ($idEtablissement !== null) {

            $validated['id_etablissement'] =
                $idEtablissement;

        } else {

            abort(
                403,
                'Impossible d’ajouter un membre du personnel sans établissement.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        $personnel = Personnel::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | JOURNALISATION
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Ajout d’un membre du personnel',
            'table_concernee' => 'personnel',
            'id_enregistrement' => $personnel->id_personnel,
            'anciennes_valeurs' => null,
            'nouvelles_valeurs' => json_encode(
                $personnel->getAttributes()
            ),
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_heure' => now(),
        ]);


        return redirect()
            ->route('personnel.index')
            ->with(
                'success',
                'Membre du personnel ajouté avec succès.'
            );
    }


    /**
     * Afficher les informations d'un membre du personnel.
     */
    public function show(Personnel $personnel)
    {
        $this->verifierEtablissement(
            $personnel
        );

        return view(
            'personnel.show',
            compact('personnel')
        );
    }


    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Personnel $personnel)
    {
        $this->verifierEtablissement(
            $personnel
        );

        return view(
            'personnel.edit',
            compact('personnel')
        );
    }


    /**
     * Modifier un membre du personnel.
     */
    public function update(
        Request $request,
        Personnel $personnel
    ) {

        $this->verifierEtablissement(
            $personnel
        );


        $validated = $request->validate([

            'matricule' => [
                'required',
                'string',
                'max:50',
            ],

            'nom' => [
                'required',
                'string',
                'max:100'
            ],

            'postnom' => [
                'nullable',
                'string',
                'max:100'
            ],

            'prenom' => [
                'nullable',
                'string',
                'max:100'
            ],

            'sexe' => [
                'required',
                'in:M,F'
            ],

            'fonction' => [
                'required',
                'string',
                'max:100'
            ],

            'qualification' => [
                'nullable',
                'string',
                'max:150'
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50'
            ],

            'email' => [
                'nullable',
                'email',
                'max:150'
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255'
            ],

            'date_engagement' => [
                'nullable',
                'date'
            ],

            'statut' => [
                'required',
                'in:ACTIF,INACTIF,SUSPENDU'
            ],

            'photo' => [
                'nullable',
                'string',
                'max:255'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | NE JAMAIS PERMETTRE DE MODIFIER L'ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        $validated['id_etablissement'] =
            $personnel->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | ANCIENNES VALEURS
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $personnel->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        $personnel->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | JOURNALISATION
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Modification d’un membre du personnel',
            'table_concernee' => 'personnel',
            'id_enregistrement' => $personnel->id_personnel,
            'anciennes_valeurs' => json_encode(
                $anciennesValeurs
            ),
            'nouvelles_valeurs' => json_encode(
                $personnel->getAttributes()
            ),
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_heure' => now(),
        ]);


        return redirect()
            ->route('personnel.index')
            ->with(
                'success',
                'Membre du personnel modifié avec succès.'
            );
    }


    /**
     * Supprimer un membre du personnel.
     */
    public function destroy(
        Personnel $personnel
    ) {

        $this->verifierEtablissement(
            $personnel
        );


        /*
        |--------------------------------------------------------------------------
        | ANCIENNES VALEURS
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $personnel->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | JOURNALISATION
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Suppression d’un membre du personnel',
            'table_concernee' => 'personnel',
            'id_enregistrement' => $personnel->id_personnel,
            'anciennes_valeurs' => json_encode(
                $anciennesValeurs
            ),
            'nouvelles_valeurs' => null,
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_heure' => now(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION
        |--------------------------------------------------------------------------
        */

        $personnel->delete();


        return redirect()
            ->route('personnel.index')
            ->with(
                'success',
                'Membre du personnel supprimé avec succès.'
            );
    }


    /**
     * Vérifier que le personnel appartient
     * à l'établissement de l'utilisateur.
     */
    private function verifierEtablissement(
        Personnel $personnel
    ): void {

        $idEtablissement =
            $this->etablissementCourant();


        /*
        | Administrateur global
        */

        if ($idEtablissement === null) {

            if (
                auth()->user()->aLeRole(
                    'Administrateur'
                )
            ) {
                return;
            }

            abort(
                403,
                'Votre compte n’est associé à aucun établissement.'
            );
        }


        /*
        | Vérification
        */

        if (
            (int) $personnel->id_etablissement !==
            (int) $idEtablissement
        ) {

            abort(
                403,
                'Vous n’avez pas accès à ce membre du personnel.'
            );
        }
    }
}
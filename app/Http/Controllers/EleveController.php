<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\JournalActivite;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    /**
     * Retourne l'identifiant de l'établissement courant.
     *
     * Un administrateur sans établissement possède un accès global.
     */
    private function etablissementCourant()
    {
        return auth()->user()->id_etablissement;
    }


    /**
     * Liste des élèves.
     */
    public function index(Request $request)
    {
        $query = Eleve::query();

        /*
        |--------------------------------------------------------------------------
        | FILTRE ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() !== null) {
            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
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
                    ->orWhere('prenom', 'like', "%{$search}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE SEXE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE STATUT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $eleves = $query
            ->orderBy('nom')
            ->orderBy('postnom')
            ->paginate(10)
            ->withQueryString();


        return view('eleves.index', compact('eleves'));
    }


    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('eleves.create');
    }


    /**
     * Enregistrement d'un élève.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|max:50',
            'nom' => 'required|string|max:100',
            'postnom' => 'nullable|string|max:100',
            'prenom' => 'nullable|string|max:100',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:150',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'statut' => 'required|in:ACTIF,INACTIF',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION DU MATRICULE DANS L'ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        $query = Eleve::where(
            'matricule',
            $validated['matricule']
        );


        if ($this->etablissementCourant() !== null) {

            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }


        if ($query->exists()) {

            return back()
                ->withErrors([
                    'matricule' =>
                        'Ce matricule existe déjà dans cet établissement.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() !== null) {

            $validated['id_etablissement'] =
                $this->etablissementCourant();

        } else {

            /*
            | Un administrateur global doit éventuellement
            | choisir l'établissement.
            |
            | Pour le moment, on bloque la création sans établissement.
            */

            abort(
                403,
                'Veuillez sélectionner un établissement pour créer cet élève.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATES
        |--------------------------------------------------------------------------
        */

        $validated['date_creation'] = now();
        $validated['date_modification'] = now();


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        $eleve = Eleve::create($validated);


        /*
        |--------------------------------------------------------------------------
        | JOURNAL
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Ajout d’un élève',
            'table_concernee' => 'eleves',
            'id_enregistrement' => $eleve->id_eleve,
            'anciennes_valeurs' => null,
            'nouvelles_valeurs' => json_encode($validated),
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_heure' => now(),
        ]);


        return redirect()
            ->route('eleves.index')
            ->with('success', 'Élève ajouté avec succès.');
    }


    /**
     * Affichage d'un élève.
     */
    public function show(Eleve $eleve)
    {
        $this->verifierEtablissement($eleve);

        return view('eleves.show', compact('eleve'));
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Eleve $eleve)
    {
        $this->verifierEtablissement($eleve);

        return view('eleves.edit', compact('eleve'));
    }


    /**
     * Mise à jour.
     */
    public function update(Request $request, Eleve $eleve)
    {
        $this->verifierEtablissement($eleve);


        $validated = $request->validate([
            'matricule' =>
                'required|string|max:50',

            'nom' =>
                'required|string|max:100',

            'postnom' =>
                'nullable|string|max:100',

            'prenom' =>
                'nullable|string|max:100',

            'sexe' =>
                'required|in:M,F',

            'date_naissance' =>
                'nullable|date',

            'lieu_naissance' =>
                'nullable|string|max:150',

            'adresse' =>
                'nullable|string|max:255',

            'telephone' =>
                'nullable|string|max:30',

            'email' =>
                'nullable|email|max:150',

            'statut' =>
                'required|in:ACTIF,INACTIF',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION MATRICULE
        |--------------------------------------------------------------------------
        */

        $query = Eleve::where(
            'matricule',
            $validated['matricule']
        )
        ->where(
            'id_eleve',
            '!=',
            $eleve->id_eleve
        );


        if ($this->etablissementCourant() !== null) {

            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }


        if ($query->exists()) {

            return back()
                ->withErrors([
                    'matricule' =>
                        'Ce matricule existe déjà dans cet établissement.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | ANCIENNES VALEURS
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs = $eleve->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        $validated['date_modification'] = now();

        $eleve->update($validated);


        /*
        |--------------------------------------------------------------------------
        | JOURNAL
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Modification d’un élève',
            'table_concernee' => 'eleves',
            'id_enregistrement' => $eleve->id_eleve,
            'anciennes_valeurs' => json_encode($anciennesValeurs),
            'nouvelles_valeurs' => json_encode($validated),
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_heure' => now(),
        ]);


        return redirect()
            ->route('eleves.index')
            ->with('success', 'Élève modifié avec succès.');
    }


    /**
     * Suppression.
     */
    public function destroy(Eleve $eleve)
    {
        $this->verifierEtablissement($eleve);


        $anciennesValeurs = $eleve->getAttributes();


        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Suppression d’un élève',
            'table_concernee' => 'eleves',
            'id_enregistrement' => $eleve->id_eleve,
            'anciennes_valeurs' => json_encode($anciennesValeurs),
            'nouvelles_valeurs' => null,
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_heure' => now(),
        ]);


        $eleve->delete();


        return redirect()
            ->route('eleves.index')
            ->with('success', 'Élève supprimé avec succès.');
    }


    /**
     * Vérifie que l'élève appartient à l'établissement
     * de l'utilisateur connecté.
     */
    private function verifierEtablissement(Eleve $eleve)
    {
        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATEUR GLOBAL
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() === null) {

            if (auth()->user()->aLeRole('Administrateur')) {
                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR D'UN ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if (
            $eleve->id_etablissement !==
            $this->etablissementCourant()
        ) {

            abort(
                403,
                'Vous n’avez pas accès à cet élève.'
            );
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\JournalActivite;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    /**
     * Établissement de l'utilisateur connecté.
     */
    private function etablissementCourant()
    {
        return auth()->user()->id_etablissement;
    }


    /**
     * Liste des classes.
     */
    public function index(Request $request)
    {
        $query = Classe::with([
            'anneeScolaire',
            'niveau',
            'etablissement'
        ]);


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

                $q->where('libelle', 'like', "%{$search}%")
                    ->orWhere('option_classe', 'like', "%{$search}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | STATUT
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
        | ANNÉE SCOLAIRE
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
        | PAGINATION
        |--------------------------------------------------------------------------
        */

       $classes = $query
            ->orderBy('libelle')
            ->paginate(10)
            ->withQueryString();

        $niveaux = \App\Models\Niveau::orderBy('libelle')->get();
        $anneesScolaires = \App\Models\AnneeScolaire::orderByDesc('date_debut')->get();           

            return view('classes.index', compact(
                'classes',
                'niveaux',
                'anneesScolaires'
            ));

    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Ici il faudra charger uniquement les années scolaires
        | appartenant à l'établissement de l'utilisateur.
        */

        $query = \App\Models\AnneeScolaire::query();

        if ($this->etablissementCourant() !== null) {

            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $anneesScolaires = $query
            ->orderByDesc('id_annee_scolaire')
            ->get();


        $niveaux = \App\Models\Niveau::orderBy('libelle')->get();


        return view(
            'classes.create',
            compact(
                'anneesScolaires',
                'niveaux'
            )
        );
    }


    /**
     * Enregistrement d'une classe.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_annee_scolaire' =>
                'required|integer',

            'id_niveau' =>
                'required|integer',

            'libelle' =>
                'required|string|max:100',

            'option_classe' =>
                'nullable|string|max:100',

            'capacite' =>
                'nullable|integer|min:1',

            'statut' =>
                'required|in:ACTIVE,INACTIVE',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() === null) {

            abort(
                403,
                'Veuillez sélectionner un établissement pour créer cette classe.'
            );
        }


        $validated['id_etablissement'] =
            $this->etablissementCourant();


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER QUE L'ANNÉE SCOLAIRE APPARTIENT À L'ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        $annee = \App\Models\AnneeScolaire::find(
            $validated['id_annee_scolaire']
        );

        if (!$annee) {

            return back()
                ->withErrors([
                    'id_annee_scolaire' =>
                        'L’année scolaire sélectionnée est introuvable.'
                ])
                ->withInput();
        }


        if (
            $this->etablissementCourant() !== null &&
            $annee->id_etablissement !=
            $this->etablissementCourant()
        ) {

            abort(
                403,
                'Cette année scolaire n’appartient pas à votre établissement.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER LE DOUBLON
        |--------------------------------------------------------------------------
        */

        $doublon = Classe::where(
            'id_etablissement',
            $this->etablissementCourant()
        )
        ->where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
        ->where(
            'libelle',
            $validated['libelle']
        )
        ->exists();


        if ($doublon) {

            return back()
                ->withErrors([
                    'libelle' =>
                        'Cette classe existe déjà dans cette année scolaire.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        $classe = Classe::create($validated);


        /*
        |--------------------------------------------------------------------------
        | JOURNAL
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Ajout d’une classe',
            'table_concernee' => 'classes',
            'id_enregistrement' => $classe->id_classe,
            'anciennes_valeurs' => null,
            'nouvelles_valeurs' => json_encode($validated),
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_heure' => now(),
        ]);


        return redirect()
            ->route('classes.index')
            ->with(
                'success',
                'Classe ajoutée avec succès.'
            );
    }


    /**
     * Afficher une classe.
     */
    public function show(Classe $classe)
    {
        $this->verifierEtablissement($classe);

        return view(
            'classes.show',
            compact('classe')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Classe $classe)
    {
        $this->verifierEtablissement($classe);


        $query = \App\Models\AnneeScolaire::query();

        if ($this->etablissementCourant() !== null) {

            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $anneesScolaires = $query
            ->orderByDesc('id_annee_scolaire')
            ->get();


        $niveaux = \App\Models\Niveau::orderBy('libelle')->get();


        return view(
            'classes.edit',
            compact(
                'classe',
                'anneesScolaires',
                'niveaux'
            )
        );
    }


    /**
     * Mise à jour.
     */
    public function update(
        Request $request,
        Classe $classe
    ) {

        $this->verifierEtablissement($classe);


        $validated = $request->validate([
            'id_annee_scolaire' =>
                'required|integer',

            'id_niveau' =>
                'required|integer',

            'libelle' =>
                'required|string|max:100',

            'option_classe' =>
                'nullable|string|max:100',

            'capacite' =>
                'nullable|integer|min:1',

            'statut' =>
                'required|in:ACTIVE,INACTIVE',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER L'ANNÉE SCOLAIRE
        |--------------------------------------------------------------------------
        */

        $annee = \App\Models\AnneeScolaire::find(
            $validated['id_annee_scolaire']
        );


        if (!$annee) {

            return back()
                ->withErrors([
                    'id_annee_scolaire' =>
                        'L’année scolaire sélectionnée est introuvable.'
                ])
                ->withInput();
        }


        if (
            $this->etablissementCourant() !== null &&
            $annee->id_etablissement !=
            $this->etablissementCourant()
        ) {

            abort(
                403,
                'Cette année scolaire n’appartient pas à votre établissement.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFIER DOUBLON
        |--------------------------------------------------------------------------
        */

        $doublon = Classe::where(
            'id_etablissement',
            $classe->id_etablissement
        )
        ->where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
        ->where(
            'libelle',
            $validated['libelle']
        )
        ->where(
            'id_classe',
            '!=',
            $classe->id_classe
        )
        ->exists();


        if ($doublon) {

            return back()
                ->withErrors([
                    'libelle' =>
                        'Cette classe existe déjà dans cette année scolaire.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | ANCIENNES VALEURS
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $classe->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        $classe->update($validated);


        /*
        |--------------------------------------------------------------------------
        | JOURNAL
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Modification d’une classe',
            'table_concernee' => 'classes',
            'id_enregistrement' => $classe->id_classe,
            'anciennes_valeurs' =>
                json_encode($anciennesValeurs),
            'nouvelles_valeurs' =>
                json_encode($validated),
            'adresse_ip' =>
                request()->ip(),
            'navigateur' =>
                request()->userAgent(),
            'date_heure' => now(),
        ]);


        return redirect()
            ->route('classes.index')
            ->with(
                'success',
                'Classe modifiée avec succès.'
            );
    }


    /**
     * Suppression.
     */
    public function destroy(Classe $classe)
    {
        $this->verifierEtablissement($classe);


        $anciennesValeurs =
            $classe->getAttributes();


        JournalActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => 'Suppression d’une classe',
            'table_concernee' => 'classes',
            'id_enregistrement' => $classe->id_classe,
            'anciennes_valeurs' =>
                json_encode($anciennesValeurs),
            'nouvelles_valeurs' => null,
            'adresse_ip' =>
                request()->ip(),
            'navigateur' =>
                request()->userAgent(),
            'date_heure' => now(),
        ]);


        $classe->delete();


        return redirect()
            ->route('classes.index')
            ->with(
                'success',
                'Classe supprimée avec succès.'
            );
    }


    /**
     * Vérification de l'établissement.
     */
    private function verifierEtablissement(Classe $classe)
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
            $classe->id_etablissement !=
            $this->etablissementCourant()
        ) {

            abort(
                403,
                'Vous n’avez pas accès à cette classe.'
            );
        }
    }
}
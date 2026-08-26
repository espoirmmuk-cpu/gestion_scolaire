<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EtablissementController extends Controller
{
    /**
     * Liste des établissements.
     */
    public function index()
    {
        $etablissements = Etablissement::orderBy('nom')->get();

        return view(
            'etablissements.index',
            compact('etablissements')
        );
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('etablissements.create');
    }

    /**
     * Enregistrement d'un établissement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:etablissements,code',
            ],

            'type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'province' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ville' => [
                'nullable',
                'string',
                'max:100',
            ],

            'commune' => [
                'nullable',
                'string',
                'max:100',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'directeur' => [
                'nullable',
                'string',
                'max:150',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'ACTIF',
                    'INACTIF',
                ]),
            ],
        ]);

        /*
         * Gestion du logo.
         */
        if ($request->hasFile('logo')) {

            $validated['logo'] =
                $request->file('logo')
                    ->store('logos', 'public');
        }

        /*
         * Création de l'établissement.
         */
        Etablissement::create($validated);

        return redirect()
            ->route('etablissements.index')
            ->with(
                'success',
                'Établissement créé avec succès.'
            );
    }

    /**
     * Affichage d'un établissement.
     */
    public function show(Etablissement $etablissement)
    {
        /*
         * Charger les personnels associés.
         */
        $etablissement->load('personnels');

        return view(
            'etablissements.show',
            compact('etablissement')
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Etablissement $etablissement)
    {
        return view(
            'etablissements.edit',
            compact('etablissement')
        );
    }

    /**
     * Mise à jour d'un établissement.
     */
    public function update(
        Request $request,
        Etablissement $etablissement
    ) {
        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'etablissements',
                    'code'
                )->ignore(
                    $etablissement->id_etablissement,
                    'id_etablissement'
                ),
            ],

            'type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'province' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ville' => [
                'nullable',
                'string',
                'max:100',
            ],

            'commune' => [
                'nullable',
                'string',
                'max:100',
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'directeur' => [
                'nullable',
                'string',
                'max:150',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'ACTIF',
                    'INACTIF',
                ]),
            ],
        ]);

        /*
         * Nouveau logo.
         */
        if ($request->hasFile('logo')) {

            /*
             * Supprimer l'ancien logo.
             */
            if (
                $etablissement->logo &&
                Storage::disk('public')->exists(
                    $etablissement->logo
                )
            ) {
                Storage::disk('public')->delete(
                    $etablissement->logo
                );
            }

            /*
             * Enregistrer le nouveau logo.
             */
            $validated['logo'] =
                $request->file('logo')
                    ->store('logos', 'public');
        }

        /*
         * Mise à jour.
         */
        $etablissement->update($validated);

        return redirect()
            ->route(
                'etablissements.show',
                $etablissement
            )
            ->with(
                'success',
                'Établissement modifié avec succès.'
            );
    }

    /**
     * Suppression.
     */
    public function destroy(Etablissement $etablissement)
    {
        /*
         * Vérifier s'il existe des utilisateurs
         * associés à cet établissement.
         */
        if ($etablissement->personnels()->exists()) {

            return redirect()
                ->route('etablissements.index')
                ->with(
                    'error',
                    'Impossible de supprimer cet établissement car du personnel y est associé.'
                );
        }

        /*
         * Vérifier les utilisateurs.
         */
        if (
            \App\Models\User::where(
                'id_etablissement',
                $etablissement->id_etablissement
            )->exists()
        ) {

            return redirect()
                ->route('etablissements.index')
                ->with(
                    'error',
                    'Impossible de supprimer cet établissement car des utilisateurs y sont associés.'
                );
        }

        /*
         * Supprimer le logo.
         */
        if (
            $etablissement->logo &&
            Storage::disk('public')->exists(
                $etablissement->logo
            )
        ) {
            Storage::disk('public')->delete(
                $etablissement->logo
            );
        }

        /*
         * Supprimer l'établissement.
         */
        $etablissement->delete();

        return redirect()
            ->route('etablissements.index')
            ->with(
                'success',
                'Établissement supprimé avec succès.'
            );
    }
}
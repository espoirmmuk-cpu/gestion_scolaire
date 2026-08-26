<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs.
     */
    public function index()
    {
        $utilisateurs = User::with([
            'etablissement',
            'roles',
        ])
        ->orderBy('nom')
        ->get();

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $etablissements = Etablissement::orderBy('nom')->get();

        $roles = Role::orderBy('nom')->get();

        return view('utilisateurs.create', compact(
            'etablissements',
            'roles'
        ));
    }

    /**
     * Enregistrer un utilisateur.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_etablissement' => [
                'nullable',
                'exists:etablissements,id_etablissement',
            ],

            'nom' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:utilisateurs,email',
            ],

            'mot_de_passe' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],

            'id_role' => [
                'required',
                'exists:roles,id_role',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'ACTIF',
                    'INACTIF',
                    'BLOQUE',
                ]),
            ],
        ]);

        $utilisateur = User::create([
            'id_etablissement' => $validated['id_etablissement'] ?? null,
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'mot_de_passe' => Hash::make($validated['mot_de_passe']),
            'statut' => $validated['statut'],
        ]);

        /*
         * Attribution du rôle.
         */
        $utilisateur->roles()->sync([
            $validated['id_role'],
        ]);

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Afficher un utilisateur.
     */
    public function show(User $utilisateur)
    {
        $utilisateur->load([
            'etablissement',
            'roles',
        ]);

        return view('utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(User $utilisateur)
    {
        $utilisateur->load('roles');

        $etablissements = Etablissement::orderBy('nom')->get();

        $roles = Role::orderBy('nom')->get();

        return view('utilisateurs.edit', compact(
            'utilisateur',
            'etablissements',
            'roles'
        ));
    }

    /**
     * Modifier un utilisateur.
     */
    public function update(Request $request, User $utilisateur)
    {
        $validated = $request->validate([
            'id_etablissement' => [
                'nullable',
                'exists:etablissements,id_etablissement',
            ],

            'nom' => [
                'required',
                'string',
                'max:150',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('utilisateurs', 'email')
                    ->ignore(
                        $utilisateur->id_utilisateur,
                        'id_utilisateur'
                    ),
            ],

            'mot_de_passe' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

            'id_role' => [
                'required',
                'exists:roles,id_role',
            ],

            'statut' => [
                'required',
                Rule::in([
                    'ACTIF',
                    'INACTIF',
                    'BLOQUE',
                ]),
            ],
        ]);

        $utilisateur->nom = $validated['nom'];
        $utilisateur->email = $validated['email'];
        $utilisateur->id_etablissement =
            $validated['id_etablissement'] ?? null;
        $utilisateur->statut = $validated['statut'];

        /*
         * Changer le mot de passe uniquement
         * lorsqu'un nouveau mot de passe est fourni.
         */
        if (!empty($validated['mot_de_passe'])) {
            $utilisateur->mot_de_passe =
                Hash::make($validated['mot_de_passe']);
        }

        $utilisateur->save();

        /*
         * Remplacer le rôle.
         */
        $utilisateur->roles()->sync([
            $validated['id_role'],
        ]);

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $utilisateur)
    {
        /*
         * Éviter qu'un utilisateur supprime son propre compte.
         */
        if (
            auth()->check() &&
            auth()->user()->id_utilisateur ===
            $utilisateur->id_utilisateur
        ) {
            return redirect()
                ->route('utilisateurs.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        /*
         * Supprimer les rôles associés.
         */
        $utilisateur->roles()->detach();

        $utilisateur->delete();

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
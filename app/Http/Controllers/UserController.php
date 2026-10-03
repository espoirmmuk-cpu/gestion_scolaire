<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Etablissement;
use App\Services\Sync\RelationSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $roleId = (int) $validated['id_role'];

        $utilisateur->roles()->sync([
            $roleId,
        ]);

        /*
         * Synchronisation de la relation utilisateur ↔ rôle.
         */
        app(RelationSyncService::class)->recordRelationOperation(
            'utilisateurs_roles',
            [
                'id_utilisateur' => $utilisateur->id_utilisateur,
                'id_role' => $roleId,
            ],
            'create'
        );

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

        /*
         * Capturer les rôles actuels AVANT la modification.
         */
        $anciensRoles = $utilisateur->roles()
            ->pluck('roles.id_role')
            ->map(fn ($id) => (int) $id)
            ->all();

        $nouveauRole = (int) $validated['id_role'];

        /*
         * Modifier les informations de l'utilisateur.
         */
        $utilisateur->nom = $validated['nom'];
        $utilisateur->email = $validated['email'];
        $utilisateur->id_etablissement =
            $validated['id_etablissement'] ?? null;
        $utilisateur->statut = $validated['statut'];

        /*
         * Modifier le mot de passe uniquement
         * lorsqu'un nouveau mot de passe est fourni.
         */
        if (!empty($validated['mot_de_passe'])) {
            $utilisateur->mot_de_passe =
                Hash::make($validated['mot_de_passe']);
        }

        $utilisateur->save();

        $relationSync = app(RelationSyncService::class);

        /*
         * Si le rôle change, enregistrer la suppression
         * de l'ancienne relation AVANT le sync().
         */
        foreach ($anciensRoles as $ancienRole) {
            if ($ancienRole !== $nouveauRole) {
                $relation = DB::table('utilisateurs_roles')
                    ->where('id_utilisateur', $utilisateur->id_utilisateur)
                    ->where('id_role', $ancienRole)
                    ->first();

                if ($relation) {
                    $relationSync->recordRelationDelete(
                        'utilisateurs_roles',
                        (array) $relation
                    );
                }
            }
        }

        /*
         * Remplacer le rôle.
         */
        $utilisateur->roles()->sync([
            $nouveauRole,
        ]);

        /*
         * Si le nouveau rôle n'existait pas déjà,
         * enregistrer la création de la nouvelle relation.
         */
        if (!in_array($nouveauRole, $anciensRoles, true)) {
            $relationSync->recordRelationOperation(
                'utilisateurs_roles',
                [
                    'id_utilisateur' => $utilisateur->id_utilisateur,
                    'id_role' => $nouveauRole,
                ],
                'create'
            );
        }

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
                ->with(
                    'error',
                    'Vous ne pouvez pas supprimer votre propre compte.'
                );
        }

        $relationSync = app(RelationSyncService::class);

        /*
         * Capturer les relations AVANT detach().
         *
         * C'est indispensable car après detach(),
         * les lignes n'existent plus et nous ne pouvons
         * plus récupérer leur uuid_sync.
         */
        $relations = DB::table('utilisateurs_roles')
            ->where('id_utilisateur', $utilisateur->id_utilisateur)
            ->get();

        foreach ($relations as $relation) {
            $relationSync->recordRelationDelete(
                'utilisateurs_roles',
                (array) $relation
            );
        }

        /*
         * Supprimer les rôles associés.
         */
        $utilisateur->roles()->detach();

        /*
         * Supprimer l'utilisateur.
         *
         * Le trait Syncable générera également
         * l'opération DELETE de l'utilisateur.
         */
        $utilisateur->delete();

        return redirect()
            ->route('utilisateurs.index')
            ->with(
                'success',
                'Utilisateur supprimé avec succès.'
            );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Liste des rôles.
     */
    public function index()
    {
        $roles = Role::withCount([
            'permissions',
            'utilisateurs',
        ])
        ->orderBy('nom')
        ->get();

        return view('roles.index', compact('roles'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Enregistrer un rôle.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:150',
                'unique:roles,nom',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        Role::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Afficher un rôle.
     */
    public function show(Role $role)
    {
        $role->load([
            'permissions',
            'utilisateurs',
        ]);

        return view('roles.show', compact('role'));
    }

    /**
     * Formulaire de modification des permissions.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('module')
            ->orderBy('action')
            ->orderBy('nom')
            ->get()
            ->groupBy('module');

        $permissionsRole = $role->permissions
            ->pluck('id_permission')
            ->toArray();

        return view('roles.edit', compact(
            'role',
            'permissions',
            'permissionsRole'
        ));
    }

    /**
     * Mise à jour du rôle et de ses permissions.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:150',
                Rule::unique('roles', 'nom')
                    ->ignore($role->id_role, 'id_role'),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'integer',
                'exists:permissions,id_permission',
            ],
        ]);

        DB::transaction(function () use ($validated, $role) {

            /*
             * Mise à jour du rôle.
             */
            $role->update([
                'nom' => $validated['nom'],
                'description' => $validated['description'] ?? null,
            ]);

            /*
             * Mise à jour des permissions.
             */
            $role->permissions()->sync(
                $validated['permissions'] ?? []
            );
        });

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rôle et permissions mis à jour avec succès.'
            );
    }

    /**
     * Supprimer un rôle.
     */
    public function destroy(Role $role)
    {
        /*
         * Empêcher la suppression du rôle Administrateur.
         */
        if ($role->nom === 'Administrateur') {
            return redirect()
                ->route('roles.index')
                ->with(
                    'error',
                    'Le rôle Administrateur ne peut pas être supprimé.'
                );
        }

        /*
         * Vérifier si le rôle est utilisé.
         */
        if ($role->utilisateurs()->exists()) {
            return redirect()
                ->route('roles.index')
                ->with(
                    'error',
                    'Impossible de supprimer ce rôle car il est attribué à un ou plusieurs utilisateurs.'
                );
        }

        /*
         * Supprimer les permissions associées.
         */
        $role->permissions()->detach();

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Rôle supprimé avec succès.'
            );
    }
}
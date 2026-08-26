<?php

namespace App\Policies;

use App\Models\Infrastructure;
use App\Models\User;

class InfrastructurePolicy
{
    /**
     * Détermine si l'utilisateur peut consulter les infrastructures.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut consulter une infrastructure.
     */
    public function view(User $user, Infrastructure $infrastructure): bool
    {
        // Administrateur global
        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return true;
        }

        return (int) $user->id_etablissement ===
               (int) $infrastructure->id_etablissement;
    }

    /**
     * Détermine si l'utilisateur peut créer une infrastructure.
     */
    public function create(User $user): bool
    {
        // Administrateur global
        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return true;
        }

        // Utilisateur rattaché à un établissement
        return $user->id_etablissement !== null;
    }

    /**
     * Détermine si l'utilisateur peut modifier une infrastructure.
     */
    public function update(
        User $user,
        Infrastructure $infrastructure
    ): bool {

        // Administrateur global
        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return true;
        }

        return (int) $user->id_etablissement ===
               (int) $infrastructure->id_etablissement;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une infrastructure.
     */
    public function delete(
        User $user,
        Infrastructure $infrastructure
    ): bool {

        // Administrateur global
        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return true;
        }

        return (int) $user->id_etablissement ===
               (int) $infrastructure->id_etablissement;
    }
}
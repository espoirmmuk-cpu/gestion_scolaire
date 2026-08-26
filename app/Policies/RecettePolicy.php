<?php

namespace App\Policies;

use App\Models\Recette;
use App\Models\User;

class RecettePolicy
{
    /**
     * Administrateur global.
     */
    private function estSuperAdministrateur(User $user): bool
    {
        return (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        );
    }

    /**
     * Voir la liste des recettes.
     */
    public function viewAny(User $user): bool
    {
        return $user->aLeRole('Administrateur')
            || $user->aLeRole('Directeur')
            || $user->aLeRole('Gestionnaire')
            || $this->estSuperAdministrateur($user);
    }

    /**
     * Voir une recette.
     */
    public function view(User $user, Recette $recette): bool
    {
        if ($this->estSuperAdministrateur($user)) {
            return true;
        }

        return $recette->id_etablissement === $user->id_etablissement;
    }

    /**
     * Créer une recette.
     */
    public function create(User $user): bool
    {
        return $user->aLeRole('Administrateur')
            || $user->aLeRole('Directeur')
            || $user->aLeRole('Gestionnaire')
            || $this->estSuperAdministrateur($user);
    }

    /**
     * Modifier une recette.
     */
    public function update(User $user, Recette $recette): bool
    {
        if ($this->estSuperAdministrateur($user)) {
            return true;
        }

        return (
            $recette->id_etablissement ===
            $user->id_etablissement
        );
    }

    /**
     * Supprimer une recette.
     */
    public function delete(User $user, Recette $recette): bool
    {
        if ($this->estSuperAdministrateur($user)) {
            return true;
        }

        return (
            $recette->id_etablissement ===
            $user->id_etablissement
        );
    }
}
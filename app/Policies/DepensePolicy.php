<?php

namespace App\Policies;

use App\Models\Depense;
use App\Models\User;

class DepensePolicy
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
     * Voir la liste des dépenses.
     */
    public function viewAny(User $user): bool
    {
        return $user->aLeRole('Administrateur')
            || $user->aLeRole('Directeur')
            || $user->aLeRole('Gestionnaire')
            || $this->estSuperAdministrateur($user);
    }

    /**
     * Voir une dépense.
     */
    public function view(User $user, Depense $depense): bool
    {
        if ($this->estSuperAdministrateur($user)) {
            return true;
        }

        return $depense->id_etablissement === $user->id_etablissement;
    }

    /**
     * Créer une dépense.
     */
    public function create(User $user): bool
    {
        return $user->aLeRole('Administrateur')
            || $user->aLeRole('Directeur')
            || $user->aLeRole('Gestionnaire')
            || $this->estSuperAdministrateur($user);
    }

    /**
     * Modifier une dépense.
     */
    public function update(User $user, Depense $depense): bool
    {
        if ($this->estSuperAdministrateur($user)) {
            return true;
        }

        return (
            $depense->id_etablissement ===
            $user->id_etablissement
        );
    }

    /**
     * Supprimer une dépense.
     */
    public function delete(User $user, Depense $depense): bool
    {
        if ($this->estSuperAdministrateur($user)) {
            return true;
        }

        return (
            $depense->id_etablissement ===
            $user->id_etablissement
        );
    }
}
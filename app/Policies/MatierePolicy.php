<?php

namespace App\Policies;

use App\Models\Matiere;
use App\Models\User;

class MatierePolicy
{
    /**
     * Détermine si l'utilisateur est un administrateur global.
     */
    private function estAdministrateurGlobal(User $user): bool
    {
        return $user->id_etablissement === null
            && $user->aLeRole('Administrateur');
    }

    /**
     * Vérifie que la matière appartient à l'établissement
     * de l'utilisateur.
     */
    private function memeEtablissement(
        User $user,
        Matiere $matiere
    ): bool {
        if ($this->estAdministrateurGlobal($user)) {
            return true;
        }

        return (int) $user->id_etablissement ===
               (int) $matiere->id_etablissement;
    }

    /**
     * Voir la liste des matières.
     */
    public function viewAny(User $user): bool
    {
        return $user->aUnePermission([
            'voir_matieres',
            'gerer_matieres',
        ]);
    }

    /**
     * Voir une matière.
     */
    public function view(
        User $user,
        Matiere $matiere
    ): bool {
        return $this->memeEtablissement(
            $user,
            $matiere
        )
        && $user->aUnePermission([
            'voir_matieres',
            'gerer_matieres',
        ]);
    }

    /**
     * Créer une matière.
     */
    public function create(User $user): bool
    {
        return $user->aUnePermission([
            'ajouter_matieres',
            'gerer_matieres',
        ]);
    }

    /**
     * Modifier une matière.
     */
    public function update(
        User $user,
        Matiere $matiere
    ): bool {
        return $this->memeEtablissement(
            $user,
            $matiere
        )
        && $user->aUnePermission([
            'modifier_matieres',
            'gerer_matieres',
        ]);
    }

    /**
     * Supprimer une matière.
     */
    public function delete(
        User $user,
        Matiere $matiere
    ): bool {
        return $this->memeEtablissement(
            $user,
            $matiere
        )
        && $user->aUnePermission([
            'supprimer_matieres',
            'gerer_matieres',
        ]);
    }

    /**
     * Restaurer une matière.
     */
    public function restore(
        User $user,
        Matiere $matiere
    ): bool {
        return false;
    }

    /**
     * Suppression définitive.
     */
    public function forceDelete(
        User $user,
        Matiere $matiere
    ): bool {
        return false;
    }
}
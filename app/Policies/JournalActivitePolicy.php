<?php

namespace App\Policies;

use App\Models\JournalActivite;
use App\Models\User;

class JournalActivitePolicy
{
    /**
     * Vérifie si l'utilisateur est administrateur global.
     */
    private function estAdministrateurGlobal(User $user): bool
    {
        return $user->id_etablissement === null
            && $user->aLeRole('Administrateur');
    }

    /**
     * Vérifie que le journal appartient au même établissement
     * que l'utilisateur connecté.
     */
    private function memeEtablissement(
        User $user,
        JournalActivite $journal
    ): bool {

        // Administrateur global
        if ($this->estAdministrateurGlobal($user)) {
            return true;
        }

        // L'utilisateur qui a effectué l'action
        // n'existe plus
        if (!$journal->utilisateur) {
            return false;
        }

        return (int) $user->id_etablissement ===
               (int) $journal->utilisateur->id_etablissement;
    }

    /**
     * Voir la liste des activités.
     */
    public function viewAny(User $user): bool
    {
        return $user->aUnePermission([
            'voir_journaux',
            'gerer_activites',
        ]);
    }

    /**
     * Voir une activité.
     */
    public function view(
        User $user,
        JournalActivite $journal
    ): bool {
        return $this->memeEtablissement(
            $user,
            $journal
        )
        && $user->aUnePermission([
            'voir_journaux',
            'gerer_activites',
        ]);
    }

    /**
     * Les journaux sont des traces système.
     *
     * On ne permet pas leur création depuis l'interface.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Modification interdite.
     */
    public function update(
        User $user,
        JournalActivite $journal
    ): bool {
        return false;
    }

    /**
     * Suppression interdite.
     */
    public function delete(
        User $user,
        JournalActivite $journal
    ): bool {
        return false;
    }

    /**
     * Restauration interdite.
     */
    public function restore(
        User $user,
        JournalActivite $journal
    ): bool {
        return false;
    }

    /**
     * Suppression définitive interdite.
     */
    public function forceDelete(
        User $user,
        JournalActivite $journal
    ): bool {
        return false;
    }
}
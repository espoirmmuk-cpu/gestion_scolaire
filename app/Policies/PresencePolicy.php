<?php

namespace App\Policies;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PresencePolicy
{
    /**
     * Voir la liste des présences.
     */
    public function viewAny(User $user): bool
    {
        return $user->aUnePermission([
            'voir_presences',
            'gerer_presences',
        ]);
    }

    /**
     * Voir une présence.
     */
    public function view(User $user, Presence $presence): bool
    {
        if (!$user->aUnePermission([
            'voir_presences',
            'gerer_presences',
        ])) {
            return false;
        }

        return $this->appartientAsonEtablissement(
            $user,
            $presence
        );
    }

    /**
     * Ajouter une présence.
     */
    public function create(User $user): bool
    {
        return $user->aUnePermission([
            'ajouter_presences',
            'gerer_presences',
        ]);
    }

    /**
     * Modifier une présence.
     */
    public function update(
        User $user,
        Presence $presence
    ): bool {
        if (!$user->aUnePermission([
            'modifier_presences',
            'gerer_presences',
        ])) {
            return false;
        }

        return $this->appartientAsonEtablissement(
            $user,
            $presence
        );
    }

    /**
     * Supprimer une présence.
     */
    public function delete(
        User $user,
        Presence $presence
    ): bool {
        if (!$user->aUnePermission([
            'supprimer_presences',
            'gerer_presences',
        ])) {
            return false;
        }

        return $this->appartientAsonEtablissement(
            $user,
            $presence
        );
    }

    /**
     * Vérifier que la présence appartient
     * à l'établissement de l'utilisateur.
     */
    private function appartientAsonEtablissement(
        User $user,
        Presence $presence
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Super administrateur
        |--------------------------------------------------------------------------
        */

        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'élève
        |--------------------------------------------------------------------------
        */

        if (!$presence->eleve) {
            return false;
        }

        if (
            (int) $presence->eleve->id_etablissement !==
            (int) $user->id_etablissement
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification de la classe
        |--------------------------------------------------------------------------
        */

        if (!$presence->classe) {
            return false;
        }

        if (
            (int) $presence->classe->id_etablissement !==
            (int) $user->id_etablissement
        ) {
            return false;
        }

        return true;
    }
}
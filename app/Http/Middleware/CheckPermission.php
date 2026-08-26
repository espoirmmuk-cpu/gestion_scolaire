<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Vérifie qu'un utilisateur possède une permission.
     *
     * Utilisation :
     *
     * ->middleware('permission:paiements,voir')
     *
     * Exemple :
     *
     * ->middleware('permission:paiements,ajouter')
     */
    public function handle(
        Request $request,
        Closure $next,
        string $module,
        string $action
    ): Response {

        /*
         * L'utilisateur doit être connecté.
         */
        if (!auth()->check()) {
            abort(403, 'Vous devez être connecté.');
        }


        $user = auth()->user();


        /*
         * Le Super Administrateur possède tous les droits.
         *
         * Pour l'instant, nous identifions le Super Administrateur
         * grâce au rôle "Administrateur".
         */
        if (
            $user->roles()
                ->where('nom', 'Administrateur')
                ->exists()
        ) {
            return $next($request);
        }


        /*
         * Vérification de la permission :
         *
         * module = paiements
         * action = voir
         *
         * Exemple :
         * paiements.voir
         */
        $autorise = $user->roles()
            ->whereHas('permissions', function ($query) use ($module, $action) {

                $query->where('module', $module)
                      ->where('action', $action);

            })
            ->exists();


        /*
         * Permission refusée.
         */
        if (!$autorise) {

            abort(
                403,
                "Vous n'avez pas l'autorisation d'effectuer cette action."
            );

        }


        /*
         * Permission accordée.
         */
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EtablissementMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        // L'utilisateur doit être connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMINISTRATEUR
        |--------------------------------------------------------------------------
        |
        | Administrateur sans établissement = accès global.
        |
        */

        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR D'UN ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($user->id_etablissement === null) {
            abort(
                403,
                'Votre compte n’est associé à aucun établissement.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stocker l'établissement courant
        |--------------------------------------------------------------------------
        */

        $request->attributes->set(
            'id_etablissement',
            $user->id_etablissement
        );

        return $next($request);
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Afficher la page de connexion.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Authentifier l'utilisateur.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authentification
        $request->authenticate();

        // Régénérer la session pour la sécurité
        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMINISTRATEUR
        |--------------------------------------------------------------------------
        |
        | Un Administrateur dont id_etablissement est NULL
        | est considéré comme Super Administrateur.
        |
        */

        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            $request->session()->put('super_admin', true);
        } else {
            $request->session()->put('super_admin', false);

            /*
            |--------------------------------------------------------------------------
            | UTILISATEUR RATTACHÉ À UNE ÉCOLE
            |--------------------------------------------------------------------------
            */

            $request->session()->put(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    /**
     * Déconnexion.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
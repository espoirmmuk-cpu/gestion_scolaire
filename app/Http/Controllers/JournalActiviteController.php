<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JournalActiviteController extends Controller
{
    /**
     * Liste du journal des activités.
     */
    public function index(Request $request)
    {
        Gate::authorize(
            'viewAny',
            JournalActivite::class
        );

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Requête de base
        |--------------------------------------------------------------------------
        */

        $query = JournalActivite::with('utilisateur')
            ->orderByDesc('date_heure');

        /*
        |--------------------------------------------------------------------------
        | Sécurité établissement
        |--------------------------------------------------------------------------
        |
        | L'administrateur global peut voir tous les journaux.
        |
        | Les autres utilisateurs ne voient que les journaux
        | effectués par les utilisateurs de leur établissement.
        |
        */

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            $query->whereHas(
                'utilisateur',
                function ($q) use ($user) {

                    $q->where(
                        'id_etablissement',
                        $user->id_etablissement
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre par action
        |--------------------------------------------------------------------------
        */

        if ($request->filled('action')) {

            $query->where(
                'action',
                'like',
                '%' . $request->action . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre par table concernée
        |--------------------------------------------------------------------------
        */

        if ($request->filled('table_concernee')) {

            $query->where(
                'table_concernee',
                $request->table_concernee
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre par utilisateur
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_utilisateur')) {

            $query->where(
                'id_utilisateur',
                $request->id_utilisateur
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date début
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_debut')) {

            $query->whereDate(
                'date_heure',
                '>=',
                $request->date_debut
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date fin
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_fin')) {

            $query->whereDate(
                'date_heure',
                '<=',
                $request->date_fin
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $activites = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Utilisateurs disponibles dans les filtres
        |--------------------------------------------------------------------------
        */

        $utilisateursQuery = User::query()
            ->orderBy('nom');

        if (!(
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        )) {

            $utilisateursQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $utilisateurs = $utilisateursQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Tables concernées
        |--------------------------------------------------------------------------
        |
        | On utilise la même requête sécurisée.
        |
        */

        $tables = (clone $query)
            ->reorder()
            ->whereNotNull('table_concernee')
            ->where(
                'table_concernee',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('table_concernee')
            ->pluck('table_concernee');

        /*
        |--------------------------------------------------------------------------
        | Affichage
        |--------------------------------------------------------------------------
        */

        return view(
            'journaux-activites.index',
            compact(
                'activites',
                'utilisateurs',
                'tables'
            )
        );
    }

    /**
     * Afficher le détail d'une activité.
     */
    public function show(
        JournalActivite $journal
    ) {

        $journal->load('utilisateur');

        Gate::authorize(
            'view',
            $journal
        );

        return view(
            'journaux-activites.show',
            compact('journal')
        );
    }
}
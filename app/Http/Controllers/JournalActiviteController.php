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
        Gate::authorize('viewAny', JournalActivite::class);

        $user = auth()->user();

        $query = JournalActivite::with('utilisateur')
            ->orderByDesc('date_heure');

        /*
        |--------------------------------------------------------------------------
        | Isolation stricte par établissement
        |--------------------------------------------------------------------------
        */

        $adminGlobal = (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        );

        if (!$adminGlobal) {
            $query->where('id_etablissement', $user->id_etablissement);
        }

        /*
        |--------------------------------------------------------------------------
        | Filtres
        |--------------------------------------------------------------------------
        */

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('table_concernee')) {
            $query->where('table_concernee', $request->table_concernee);
        }

        if ($request->filled('id_utilisateur')) {
            $query->where('id_utilisateur', $request->id_utilisateur);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_heure', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_heure', '<=', $request->date_fin);
        }

        $activites = $query
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Utilisateurs du même établissement
        |--------------------------------------------------------------------------
        */

        $utilisateursQuery = User::query()
            ->orderBy('nom');

        if (!$adminGlobal) {
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
        */

        $tables = (clone $query)
            ->reorder()
            ->whereNotNull('table_concernee')
            ->where('table_concernee', '!=', '')
            ->distinct()
            ->orderBy('table_concernee')
            ->pluck('table_concernee');

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
    public function show(JournalActivite $journal)
    {
        $user = auth()->user();

        $adminGlobal = (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        );

        if (!$adminGlobal) {
            abort_unless(
                $journal->id_etablissement === $user->id_etablissement,
                403
            );
        }

        $journal->load('utilisateur');

        Gate::authorize('view', $journal);

        return view(
            'journaux-activites.show',
            compact('journal')
        );
    }
}
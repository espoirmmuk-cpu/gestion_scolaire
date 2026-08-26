<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;

class MatiereController extends Controller
{
    /**
     * Liste des matières de l'établissement connecté.
     */
    public function index()
    {
        Gate::authorize('viewAny', Matiere::class);

        $idEtablissement = auth()->user()->id_etablissement;

        $matieres = Matiere::where(
            'id_etablissement',
            $idEtablissement
        )
        ->orderBy('libelle')
        ->get();

        return view(
            'matieres.index',
            compact('matieres')
        );
    }

    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        Gate::authorize('create', Matiere::class);

        return view('matieres.create');
    }

    /**
     * Enregistrer une matière.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Matiere::class);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:matieres,code',
            ],

            'libelle' => [
                'required',
                'string',
                'max:150',
            ],

            'coefficient' => [
                'required',
                'numeric',
                'min:0',
            ],

            'statut' => [
                'required',
                'in:ACTIVE,INACTIVE',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Établissement de l'utilisateur connecté
        |--------------------------------------------------------------------------
        */

        $validated['id_etablissement'] =
            auth()->user()->id_etablissement;

        /*
        |--------------------------------------------------------------------------
        | Vérifier l'unicité dans l'établissement
        |--------------------------------------------------------------------------
        */

        $existe = Matiere::where(
            'id_etablissement',
            $validated['id_etablissement']
        )
        ->where(function ($query) use ($validated) {
            $query
                ->where('code', $validated['code'])
                ->orWhere('libelle', $validated['libelle']);
        })
        ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' =>
                        'Cette matière existe déjà dans votre établissement.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Création
        |--------------------------------------------------------------------------
        */

        $matiere = Matiere::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),

            'action' => 'Ajout d’une matière',

            'table_concernee' => 'matieres',

            'id_enregistrement' => $matiere->id_matiere,

            'anciennes_valeurs' => null,

            'nouvelles_valeurs' => json_encode(
                $matiere->getAttributes(),
                JSON_UNESCAPED_UNICODE
            ),

            'adresse_ip' => request()->ip(),

            'navigateur' => request()->userAgent(),

            'date_heure' => now(),
        ]);

        return redirect()
            ->route('matieres.index')
            ->with(
                'success',
                'Matière ajoutée avec succès.'
            );
    }

    /**
     * Afficher une matière.
     */
    public function show(Matiere $matiere)
    {
        Gate::authorize(
            'view',
            $matiere
        );

        return view(
            'matieres.show',
            compact('matiere')
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Matiere $matiere)
    {
        Gate::authorize(
            'update',
            $matiere
        );

        return view(
            'matieres.edit',
            compact('matiere')
        );
    }

    /**
     * Modifier une matière.
     */
    public function update(
        Request $request,
        Matiere $matiere
    ) {
        Gate::authorize(
            'update',
            $matiere
        );

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:matieres,code,' .
                    $matiere->id_matiere .
                    ',id_matiere',
            ],

            'libelle' => [
                'required',
                'string',
                'max:150',
            ],

            'coefficient' => [
                'required',
                'numeric',
                'min:0',
            ],

            'statut' => [
                'required',
                'in:ACTIVE,INACTIVE',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérification supplémentaire
        |--------------------------------------------------------------------------
        */

        $existe = Matiere::where(
            'id_etablissement',
            auth()->user()->id_etablissement
        )
        ->where('id_matiere', '!=', $matiere->id_matiere)
        ->where(function ($query) use ($validated) {
            $query
                ->where('code', $validated['code'])
                ->orWhere('libelle', $validated['libelle']);
        })
        ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' =>
                        'Cette matière existe déjà dans votre établissement.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $matiere->getAttributes();

        /*
        |--------------------------------------------------------------------------
        | Modification
        |--------------------------------------------------------------------------
        */

        $matiere->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => auth()->id(),

            'action' => 'Modification d’une matière',

            'table_concernee' => 'matieres',

            'id_enregistrement' => $matiere->id_matiere,

            'anciennes_valeurs' => json_encode(
                $anciennesValeurs,
                JSON_UNESCAPED_UNICODE
            ),

            'nouvelles_valeurs' => json_encode(
                $matiere->getAttributes(),
                JSON_UNESCAPED_UNICODE
            ),

            'adresse_ip' => request()->ip(),

            'navigateur' => request()->userAgent(),

            'date_heure' => now(),
        ]);

        return redirect()
            ->route('matieres.index')
            ->with(
                'success',
                'Matière modifiée avec succès.'
            );
    }

    /**
     * Supprimer une matière.
     */
    public function destroy(Matiere $matiere)
    {
        Gate::authorize(
            'delete',
            $matiere
        );

        $anciennesValeurs =
            $matiere->getAttributes();

        try {
            /*
            |--------------------------------------------------------------------------
            | Suppression
            |--------------------------------------------------------------------------
            */

            $matiere->delete();

            /*
            |--------------------------------------------------------------------------
            | Journalisation
            |--------------------------------------------------------------------------
            */

            JournalActivite::create([
                'id_utilisateur' => auth()->id(),

                'action' => 'Suppression d’une matière',

                'table_concernee' => 'matieres',

                'id_enregistrement' =>
                    $matiere->id_matiere,

                'anciennes_valeurs' => json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

                'nouvelles_valeurs' => null,

                'adresse_ip' => request()->ip(),

                'navigateur' => request()->userAgent(),

                'date_heure' => now(),
            ]);

            return redirect()
                ->route('matieres.index')
                ->with(
                    'success',
                    'Matière supprimée avec succès.'
                );

        } catch (QueryException $e) {

            return redirect()
                ->route('matieres.index')
                ->with(
                    'error',
                    'Cette matière ne peut pas être supprimée car elle est utilisée dans une évaluation ou une note.'
                );
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Infrastructure;
use App\Models\JournalActivite;
use Illuminate\Http\Request;

class InfrastructureController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ÉTABLISSEMENT COURANT
    |--------------------------------------------------------------------------
    */

    private function etablissementCourant()
    {
        return auth()->user()->id_etablissement;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Infrastructure::query()
            ->with('etablissement');


        /*
        |--------------------------------------------------------------------------
        | FILTRE ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() !== null) {

            $query->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RECHERCHE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'designation',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'type',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'localisation',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'observation',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE TYPE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {

            $query->where(
                'type',
                'like',
                '%' . $request->type . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRE ÉTAT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('etat')) {

            $query->where(
                'etat',
                $request->etat
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TRI
        |--------------------------------------------------------------------------
        */

        $query
            ->orderBy('designation')
            ->orderBy('type');


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $infrastructures = $query
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TYPES DISPONIBLES
        |--------------------------------------------------------------------------
        */

        $typesQuery = Infrastructure::query();

        if ($this->etablissementCourant() !== null) {

            $typesQuery->where(
                'id_etablissement',
                $this->etablissementCourant()
            );
        }

        $types = $typesQuery
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');


        /*
        |--------------------------------------------------------------------------
        | ÉTATS
        |--------------------------------------------------------------------------
        */

        $etats = [
            'BON',
            'MOYEN',
            'A_REHABILITER',
            'HORS_SERVICE',
        ];


        return view(
            'infrastructures.index',
            compact(
                'infrastructures',
                'types',
                'etats'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('infrastructures.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'designation' => [
                'required',
                'string',
                'max:150',
            ],

            'type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'quantite' => [
                'required',
                'integer',
                'min:1',
            ],

            'etat' => [
                'required',
                'in:BON,MOYEN,A_REHABILITER,HORS_SERVICE',
            ],

            'localisation' => [
                'nullable',
                'string',
                'max:150',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() === null) {

            abort(
                403,
                'Veuillez sélectionner un établissement pour créer cette infrastructure.'
            );
        }

        $validated['id_etablissement'] =
            $this->etablissementCourant();


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        $infrastructure = Infrastructure::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | JOURNALISATION
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' =>
                auth()->user()->id_utilisateur
                ?? auth()->id(),

            'action' =>
                'Ajout d’une infrastructure',

            'table_concernee' =>
                'infrastructures',

            'id_enregistrement' =>
                $infrastructure->id_infrastructure,

            'anciennes_valeurs' =>
                null,

            'nouvelles_valeurs' =>
                json_encode(
                    $infrastructure->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),

            'adresse_ip' =>
                $request->ip(),

            'navigateur' =>
                $request->userAgent(),

            'date_heure' =>
                now(),
        ]);


        return redirect()
            ->route('infrastructures.index')
            ->with(
                'success',
                'Infrastructure enregistrée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Infrastructure $infrastructure
    ) {
        $this->verifierEtablissement(
            $infrastructure
        );

        $infrastructure->load(
            'etablissement'
        );

        return view(
            'infrastructures.show',
            compact('infrastructure')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Infrastructure $infrastructure
    ) {
        $this->verifierEtablissement(
            $infrastructure
        );

        return view(
            'infrastructures.edit',
            compact('infrastructure')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Infrastructure $infrastructure
    ) {
        $this->verifierEtablissement(
            $infrastructure
        );


        $validated = $request->validate([

            'designation' => [
                'required',
                'string',
                'max:150',
            ],

            'type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'quantite' => [
                'required',
                'integer',
                'min:1',
            ],

            'etat' => [
                'required',
                'in:BON,MOYEN,A_REHABILITER,HORS_SERVICE',
            ],

            'localisation' => [
                'nullable',
                'string',
                'max:150',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ANCIENNES VALEURS
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $infrastructure->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        $infrastructure->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | JOURNALISATION
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' =>
                auth()->user()->id_utilisateur
                ?? auth()->id(),

            'action' =>
                'Modification d’une infrastructure',

            'table_concernee' =>
                'infrastructures',

            'id_enregistrement' =>
                $infrastructure->id_infrastructure,

            'anciennes_valeurs' =>
                json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

            'nouvelles_valeurs' =>
                json_encode(
                    $infrastructure->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),

            'adresse_ip' =>
                $request->ip(),

            'navigateur' =>
                $request->userAgent(),

            'date_heure' =>
                now(),
        ]);


        return redirect()
            ->route('infrastructures.index')
            ->with(
                'success',
                'Infrastructure modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Infrastructure $infrastructure
    ) {
        $this->verifierEtablissement(
            $infrastructure
        );


        $anciennesValeurs =
            $infrastructure->getAttributes();


        try {

            /*
            |--------------------------------------------------------------------------
            | SUPPRESSION
            |--------------------------------------------------------------------------
            */

            $infrastructure->delete();


            /*
            |--------------------------------------------------------------------------
            | JOURNALISATION
            |--------------------------------------------------------------------------
            */

            JournalActivite::create([

                'id_utilisateur' =>
                    auth()->user()->id_utilisateur
                    ?? auth()->id(),

                'action' =>
                    'Suppression d’une infrastructure',

                'table_concernee' =>
                    'infrastructures',

                'id_enregistrement' =>
                    $infrastructure->id_infrastructure,

                'anciennes_valeurs' =>
                    json_encode(
                        $anciennesValeurs,
                        JSON_UNESCAPED_UNICODE
                    ),

                'nouvelles_valeurs' =>
                    null,

                'adresse_ip' =>
                    request()->ip(),

                'navigateur' =>
                    request()->userAgent(),

                'date_heure' =>
                    now(),
            ]);


            return redirect()
                ->route('infrastructures.index')
                ->with(
                    'success',
                    'Infrastructure supprimée avec succès.'
                );

        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()
                ->route('infrastructures.index')
                ->with(
                    'error',
                    'Cette infrastructure ne peut pas être supprimée.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VÉRIFICATION ÉTABLISSEMENT
    |--------------------------------------------------------------------------
    */

    private function verifierEtablissement(
        Infrastructure $infrastructure
    ): void {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATEUR GLOBAL
        |--------------------------------------------------------------------------
        */

        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR D'UN ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if (
            (int) $infrastructure->id_etablissement !==
            (int) $user->id_etablissement
        ) {

            abort(
                403,
                'Vous n’avez pas accès à cette infrastructure.'
            );
        }
    }
}
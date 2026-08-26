<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\PeriodeScolaire;
use App\Models\JournalActivite;
use App\Models\Inscription;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Liste des évaluations.
     */
    public function index()
    {
        $idEtablissement = Auth::user()->id_etablissement;

        $evaluations = Evaluation::with([
            'anneeScolaire',
            'classe',
            'matiere',
            'periode',
        ])
            ->whereHas('classe', function ($query) use ($idEtablissement) {
                $query->where(
                    'id_etablissement',
                    $idEtablissement
                );
            })
            ->orderByDesc('date_evaluation')
            ->orderBy('libelle')
            ->get();

        return view(
            'evaluations.index',
            compact('evaluations')
        );
    }


    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        $idEtablissement = Auth::user()->id_etablissement;

        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */

        $annees = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | Classes de l'établissement
        |--------------------------------------------------------------------------
        */

        $classes = Classe::where(
            'id_etablissement',
            $idEtablissement
        )
            ->where(
                'statut',
                'ACTIVE'
            )
            ->orderBy('libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Matières actives
        |--------------------------------------------------------------------------
        |
        | La table matières ne possède pas actuellement de
        | id_etablissement dans la structure fournie.
        |
        */

        $matieres = Matiere::where(
            'statut',
            'ACTIVE'
        )
            ->orderBy('libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Périodes scolaires
        |--------------------------------------------------------------------------
        */

        $periodes = PeriodeScolaire::with(
            'anneeScolaire'
        )
            ->orderBy('date_debut')
            ->get();


        return view(
            'evaluations.create',
            compact(
                'annees',
                'classes',
                'matieres',
                'periodes'
            )
        );
    }


    /**
     * Enregistrer une évaluation.
     */
    public function store(Request $request)
    {
        $idEtablissement = Auth::user()->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_annee_scolaire' => [
                'required',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'id_classe' => [
                'required',
                'exists:classes,id_classe',
            ],

            'id_matiere' => [
                'required',
                'exists:matieres,id_matiere',
            ],

            'id_periode' => [
                'required',
                'exists:periodes_scolaires,id_periode',
            ],

            'libelle' => [
                'required',
                'string',
                'max:150',
            ],

            'type_evaluation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'note_maximale' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999.99',
            ],

            'date_evaluation' => [
                'nullable',
                'date',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier que la classe appartient à l'établissement
        |--------------------------------------------------------------------------
        */

        $classe = Classe::where(
            'id_classe',
            $validated['id_classe']
        )
            ->where(
                'id_etablissement',
                $idEtablissement
            )
            ->first();

        if (!$classe) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_classe' =>
                        'La classe sélectionnée n’appartient pas à votre établissement.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier que la classe correspond à l'année scolaire
        |--------------------------------------------------------------------------
        */

        if (
            (int) $classe->id_annee_scolaire
            !==
            (int) $validated['id_annee_scolaire']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_annee_scolaire' =>
                        'L’année scolaire ne correspond pas à la classe sélectionnée.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la période scolaire
        |--------------------------------------------------------------------------
        */

        $periode = PeriodeScolaire::find(
            $validated['id_periode']
        );

        if (!$periode) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_periode' =>
                        'La période scolaire sélectionnée est invalide.',
                ]);
        }


        if (
            (int) $periode->id_annee_scolaire
            !==
            (int) $validated['id_annee_scolaire']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_periode' =>
                        'La période scolaire ne correspond pas à l’année scolaire sélectionnée.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la matière
        |--------------------------------------------------------------------------
        */

        $matiere = Matiere::where(
            'id_matiere',
            $validated['id_matiere']
        )
            ->where(
                'statut',
                'ACTIVE'
            )
            ->first();

        if (!$matiere) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_matiere' =>
                        'La matière sélectionnée est invalide ou inactive.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier les doublons
        |--------------------------------------------------------------------------
        */

        $existe = Evaluation::where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
            ->where(
                'id_classe',
                $validated['id_classe']
            )
            ->where(
                'id_matiere',
                $validated['id_matiere']
            )
            ->where(
                'id_periode',
                $validated['id_periode']
            )
            ->where(
                'libelle',
                $validated['libelle']
            )
            ->exists();

        if ($existe) {

            return back()
                ->withInput()
                ->withErrors([
                    'libelle' =>
                        'Une évaluation portant ce libellé existe déjà pour cette classe, cette matière, cette période et cette année scolaire.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Création + journalisation
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $request
        ) {

            $evaluation = Evaluation::create(
                $validated
            );


            JournalActivite::create([

                'id_utilisateur' =>
                    Auth::id(),

                'action' =>
                    'Ajout d’une évaluation',

                'table_concernee' =>
                    'evaluations',

                'id_enregistrement' =>
                    $evaluation->id_evaluation,

                'anciennes_valeurs' =>
                    null,

                'nouvelles_valeurs' =>
                    json_encode(
                        $evaluation
                            ->fresh()
                            ->toArray(),
                        JSON_UNESCAPED_UNICODE
                    ),

                'adresse_ip' =>
                    $request->ip(),

                'navigateur' =>
                    $request->userAgent(),

                'date_heure' =>
                    now(),
            ]);
        });


        return redirect()
            ->route('evaluations.index')
            ->with(
                'success',
                'Évaluation ajoutée avec succès.'
            );
    }


    /**
     * Afficher une évaluation.
     */
    public function show(Evaluation $evaluation)
    {
        $idEtablissement = Auth::user()->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'établissement
        |--------------------------------------------------------------------------
        */

        $evaluation->load([
            'anneeScolaire',
            'classe',
            'matiere',
            'periode',
        ]);


        if (
            !$evaluation->classe
            ||
            (int) $evaluation->classe->id_etablissement
            !==
            (int) $idEtablissement
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Élèves inscrits dans la classe et l'année
        |--------------------------------------------------------------------------
        */

        $inscriptions = Inscription::with(
            'eleve'
        )
            ->where(
                'id_classe',
                $evaluation->id_classe
            )
            ->where(
                'id_annee_scolaire',
                $evaluation->id_annee_scolaire
            )
            ->where(
                'statut',
                'INSCRIT'
            )
            ->whereHas(
                'eleve',
                function ($query) use ($idEtablissement) {

                    $query->where(
                        'id_etablissement',
                        $idEtablissement
                    );
                }
            )
            ->orderBy(
                'id_inscription'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Notes existantes
        |--------------------------------------------------------------------------
        */

        $notes = Note::where(
            'id_evaluation',
            $evaluation->id_evaluation
        )
            ->whereIn(
                'id_eleve',
                $inscriptions->pluck('id_eleve')
            )
            ->get()
            ->keyBy(
                'id_eleve'
            );


        return view(
            'evaluations.show',
            compact(
                'evaluation',
                'inscriptions',
                'notes'
            )
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Evaluation $evaluation)
    {
        $idEtablissement = Auth::user()->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'évaluation appartient à l'établissement
        |--------------------------------------------------------------------------
        */

        $evaluation->load('classe');

        if (
            !$evaluation->classe
            ||
            (int) $evaluation->classe->id_etablissement
            !==
            (int) $idEtablissement
        ) {

            abort(404);
        }


        $annees = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();


        $classes = Classe::where(
            'id_etablissement',
            $idEtablissement
        )
            ->where(
                'statut',
                'ACTIVE'
            )
            ->orderBy('libelle')
            ->get();


        $matieres = Matiere::where(
            'statut',
            'ACTIVE'
        )
            ->orderBy('libelle')
            ->get();


        $periodes = PeriodeScolaire::with(
            'anneeScolaire'
        )
            ->orderBy('date_debut')
            ->get();


        return view(
            'evaluations.edit',
            compact(
                'evaluation',
                'annees',
                'classes',
                'matieres',
                'periodes'
            )
        );
    }


    /**
     * Modifier une évaluation.
     */
    public function update(
        Request $request,
        Evaluation $evaluation
    ) {
        $idEtablissement = Auth::user()->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'établissement
        |--------------------------------------------------------------------------
        */

        $evaluation->load('classe');

        if (
            !$evaluation->classe
            ||
            (int) $evaluation->classe->id_etablissement
            !==
            (int) $idEtablissement
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_annee_scolaire' => [
                'required',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'id_classe' => [
                'required',
                'exists:classes,id_classe',
            ],

            'id_matiere' => [
                'required',
                'exists:matieres,id_matiere',
            ],

            'id_periode' => [
                'required',
                'exists:periodes_scolaires,id_periode',
            ],

            'libelle' => [
                'required',
                'string',
                'max:150',
            ],

            'type_evaluation' => [
                'nullable',
                'string',
                'max:100',
            ],

            'note_maximale' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999.99',
            ],

            'date_evaluation' => [
                'nullable',
                'date',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        */

        $classe = Classe::where(
            'id_classe',
            $validated['id_classe']
        )
            ->where(
                'id_etablissement',
                $idEtablissement
            )
            ->first();

        if (!$classe) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_classe' =>
                        'La classe sélectionnée n’appartient pas à votre établissement.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année de la classe
        |--------------------------------------------------------------------------
        */

        if (
            (int) $classe->id_annee_scolaire
            !==
            (int) $validated['id_annee_scolaire']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_annee_scolaire' =>
                        'L’année scolaire ne correspond pas à la classe sélectionnée.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la période
        |--------------------------------------------------------------------------
        */

        $periode = PeriodeScolaire::find(
            $validated['id_periode']
        );

        if (
            !$periode
            ||
            (int) $periode->id_annee_scolaire
            !==
            (int) $validated['id_annee_scolaire']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_periode' =>
                        'La période scolaire ne correspond pas à l’année scolaire sélectionnée.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la matière
        |--------------------------------------------------------------------------
        */

        $matiere = Matiere::where(
            'id_matiere',
            $validated['id_matiere']
        )
            ->where(
                'statut',
                'ACTIVE'
            )
            ->first();

        if (!$matiere) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_matiere' =>
                        'La matière sélectionnée est invalide ou inactive.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier les doublons
        |--------------------------------------------------------------------------
        */

        $existe = Evaluation::where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
            ->where(
                'id_classe',
                $validated['id_classe']
            )
            ->where(
                'id_matiere',
                $validated['id_matiere']
            )
            ->where(
                'id_periode',
                $validated['id_periode']
            )
            ->where(
                'libelle',
                $validated['libelle']
            )
            ->where(
                'id_evaluation',
                '!=',
                $evaluation->id_evaluation
            )
            ->exists();

        if ($existe) {

            return back()
                ->withInput()
                ->withErrors([
                    'libelle' =>
                        'Une autre évaluation portant ce libellé existe déjà pour cette classe, cette matière, cette période et cette année scolaire.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $evaluation->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | Modification
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $evaluation,
            $validated,
            $anciennesValeurs,
            $request
        ) {

            $evaluation->update(
                $validated
            );


            /*
            |----------------------------------------------------------------------
            | Journalisation
            |----------------------------------------------------------------------
            */

            JournalActivite::create([

                'id_utilisateur' =>
                    Auth::id(),

                'action' =>
                    'Modification d’une évaluation',

                'table_concernee' =>
                    'evaluations',

                'id_enregistrement' =>
                    $evaluation->id_evaluation,

                'anciennes_valeurs' =>
                    json_encode(
                        $anciennesValeurs,
                        JSON_UNESCAPED_UNICODE
                    ),

                'nouvelles_valeurs' =>
                    json_encode(
                        $evaluation
                            ->fresh()
                            ->toArray(),
                        JSON_UNESCAPED_UNICODE
                    ),

                'adresse_ip' =>
                    $request->ip(),

                'navigateur' =>
                    $request->userAgent(),

                'date_heure' =>
                    now(),
            ]);
        });


        return redirect()
            ->route('evaluations.index')
            ->with(
                'success',
                'Évaluation modifiée avec succès.'
            );
    }


    /**
     * Supprimer une évaluation.
     */
    public function destroy(
        Request $request,
        Evaluation $evaluation
    ) {
        $idEtablissement =
            Auth::user()->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'établissement
        |--------------------------------------------------------------------------
        */

        $evaluation->load('classe');

        if (
            !$evaluation->classe
            ||
            (int) $evaluation->classe->id_etablissement
            !==
            (int) $idEtablissement
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier si des notes existent
        |--------------------------------------------------------------------------
        */

        if (
            $evaluation->notes()->exists()
        ) {

            return redirect()
                ->route('evaluations.index')
                ->with(
                    'error',
                    'Cette évaluation ne peut pas être supprimée car elle possède déjà des notes.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $evaluation->getAttributes();

        $idEvaluation =
            $evaluation->id_evaluation;


        /*
        |--------------------------------------------------------------------------
        | Suppression + journalisation
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $evaluation,
            $anciennesValeurs,
            $idEvaluation,
            $request
        ) {

            $evaluation->delete();


            JournalActivite::create([

                'id_utilisateur' =>
                    Auth::id(),

                'action' =>
                    'Suppression d’une évaluation',

                'table_concernee' =>
                    'evaluations',

                'id_enregistrement' =>
                    $idEvaluation,

                'anciennes_valeurs' =>
                    json_encode(
                        $anciennesValeurs,
                        JSON_UNESCAPED_UNICODE
                    ),

                'nouvelles_valeurs' =>
                    null,

                'adresse_ip' =>
                    $request->ip(),

                'navigateur' =>
                    $request->userAgent(),

                'date_heure' =>
                    now(),
            ]);
        });


        return redirect()
            ->route('evaluations.index')
            ->with(
                'success',
                'Évaluation supprimée avec succès.'
            );
    }


    /**
     * Enregistrer toutes les notes d'une évaluation.
     */
    public function enregistrerNotes(
        Request $request,
        Evaluation $evaluation
    ) {
        $idEtablissement =
            Auth::user()->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'évaluation appartient à l'établissement
        |--------------------------------------------------------------------------
        */

        $evaluation->load('classe');

        if (
            !$evaluation->classe
            ||
            (int) $evaluation->classe->id_etablissement
            !==
            (int) $idEtablissement
        ) {

            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation générale
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'notes' => [
                'nullable',
                'array',
            ],

            'notes.*.note' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'notes.*.appreciation' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        $notes = $request->input(
            'notes',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $notes,
            $evaluation,
            $request,
            $idEtablissement
        ) {

            foreach ($notes as $idEleve => $data) {

                /*
                |--------------------------------------------------------------------------
                | Ignorer une note vide
                |--------------------------------------------------------------------------
                */

                if (
                    !isset($data['note'])
                    ||
                    $data['note'] === ''
                    ||
                    $data['note'] === null
                ) {
                    continue;
                }


                $noteSaisie =
                    (float) $data['note'];


                /*
                |--------------------------------------------------------------------------
                | Vérifier la note maximale
                |--------------------------------------------------------------------------
                */

                if (
                    $noteSaisie >
                    (float) $evaluation->note_maximale
                ) {

                    throw new \Exception(
                        "La note de l'élève {$idEleve} ne peut pas dépasser {$evaluation->note_maximale}."
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Vérifier que l'élève appartient
                | à la classe et à l'établissement
                |--------------------------------------------------------------------------
                */

                $inscription =
                    Inscription::where(
                        'id_eleve',
                        $idEleve
                    )
                        ->where(
                            'id_classe',
                            $evaluation->id_classe
                        )
                        ->where(
                            'id_annee_scolaire',
                            $evaluation->id_annee_scolaire
                        )
                        ->where(
                            'statut',
                            'INSCRIT'
                        )
                        ->whereHas(
                            'eleve',
                            function ($query) use (
                                $idEtablissement
                            ) {

                                $query->where(
                                    'id_etablissement',
                                    $idEtablissement
                                );
                            }
                        )
                        ->first();


                if (!$inscription) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Chercher la note existante
                |--------------------------------------------------------------------------
                */

                $note = Note::where(
                    'id_evaluation',
                    $evaluation->id_evaluation
                )
                    ->where(
                        'id_eleve',
                        $idEleve
                    )
                    ->first();


                /*
                |--------------------------------------------------------------------------
                | Modification
                |--------------------------------------------------------------------------
                */

                if ($note) {

                    $anciennesValeurs =
                        $note->getAttributes();


                    $note->update([

                        'note' =>
                            $noteSaisie,

                        'appreciation' =>
                            $data['appreciation']
                            ?? null,
                    ]);


                    JournalActivite::create([

                        'id_utilisateur' =>
                            Auth::id(),

                        'action' =>
                            'Modification d’une note',

                        'table_concernee' =>
                            'notes',

                        'id_enregistrement' =>
                            $note->id_note,

                        'anciennes_valeurs' =>
                            json_encode(
                                $anciennesValeurs,
                                JSON_UNESCAPED_UNICODE
                            ),

                        'nouvelles_valeurs' =>
                            json_encode(
                                $note
                                    ->fresh()
                                    ->toArray(),
                                JSON_UNESCAPED_UNICODE
                            ),

                        'adresse_ip' =>
                            $request->ip(),

                        'navigateur' =>
                            $request->userAgent(),

                        'date_heure' =>
                            now(),
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Nouvelle note
                |--------------------------------------------------------------------------
                */

                else {

                    $note = Note::create([

                        'id_evaluation' =>
                            $evaluation->id_evaluation,

                        'id_eleve' =>
                            $idEleve,

                        'note' =>
                            $noteSaisie,

                        'appreciation' =>
                            $data['appreciation']
                            ?? null,
                    ]);


                    JournalActivite::create([

                        'id_utilisateur' =>
                            Auth::id(),

                        'action' =>
                            'Ajout d’une note',

                        'table_concernee' =>
                            'notes',

                        'id_enregistrement' =>
                            $note->id_note,

                        'anciennes_valeurs' =>
                            null,

                        'nouvelles_valeurs' =>
                            json_encode(
                                $note
                                    ->fresh()
                                    ->toArray(),
                                JSON_UNESCAPED_UNICODE
                            ),

                        'adresse_ip' =>
                            $request->ip(),

                        'navigateur' =>
                            $request->userAgent(),

                        'date_heure' =>
                            now(),
                    ]);
                }
            }
        });


        return redirect()
            ->route(
                'evaluations.show',
                $evaluation
            )
            ->with(
                'success',
                'Toutes les notes ont été enregistrées avec succès.'
            );
    }
}
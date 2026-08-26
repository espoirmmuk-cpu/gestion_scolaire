<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Evaluation;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    /**
     * Retourne l'établissement de l'utilisateur connecté.
     */
    private function getEtablissementId()
    {
        $user = Auth::user();

        abort_unless($user, 403, 'Utilisateur non authentifié.');

        abort_unless(
            $user->id_etablissement,
            403,
            'Aucun établissement n’est associé à votre compte.'
        );

        return $user->id_etablissement;
    }


    /**
     * Vérifie qu'une évaluation appartient bien
     * à l'établissement de l'utilisateur connecté.
     */
    private function verifierEvaluation(Evaluation $evaluation)
    {
        $idEtablissement = $this->getEtablissementId();

        $evaluation->loadMissing('classe');

        abort_unless(
            $evaluation->classe &&
            $evaluation->classe->id_etablissement == $idEtablissement,
            403,
            'Cette évaluation n’appartient pas à votre établissement.'
        );

        return $evaluation;
    }


    /**
     * Vérifie qu'un élève appartient à l'établissement.
     */
    private function verifierEleve(Eleve $eleve)
    {
        $idEtablissement = $this->getEtablissementId();

        abort_unless(
            $eleve->id_etablissement == $idEtablissement,
            403,
            'Cet élève n’appartient pas à votre établissement.'
        );

        return $eleve;
    }


    /**
     * Vérifie qu'une note appartient à l'établissement.
     */
    private function verifierNote(Note $note)
    {
        $note->loadMissing([
            'evaluation.classe',
            'eleve',
        ]);

        $idEtablissement = $this->getEtablissementId();

        abort_unless(
            $note->evaluation &&
            $note->evaluation->classe &&
            $note->evaluation->classe->id_etablissement == $idEtablissement,
            403,
            'Cette note n’appartient pas à votre établissement.'
        );

        abort_unless(
            $note->eleve &&
            $note->eleve->id_etablissement == $idEtablissement,
            403,
            'L’élève associé à cette note n’appartient pas à votre établissement.'
        );

        return $note;
    }


    /**
     * Liste des notes.
     */
    public function index()
    {
        $idEtablissement = $this->getEtablissementId();

        $notes = Note::with([
            'evaluation.matiere',
            'evaluation.classe',
            'evaluation.periode',
            'evaluation.anneeScolaire',
            'eleve',
        ])
        ->whereHas('evaluation.classe', function ($query) use ($idEtablissement) {
            $query->where(
                'id_etablissement',
                $idEtablissement
            );
        })
        ->whereHas('eleve', function ($query) use ($idEtablissement) {
            $query->where(
                'id_etablissement',
                $idEtablissement
            );
        })
        ->orderByDesc('id_note')
        ->get();

        return view(
            'notes.index',
            compact('notes')
        );
    }


    /**
     * Formulaire d'ajout d'une note.
     */
    public function create(Request $request)
    {
        $idEtablissement = $this->getEtablissementId();

        $evaluation = null;
        $eleve = null;


        /*
        |--------------------------------------------------------------------------
        | Évaluation sélectionnée
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_evaluation')) {

            $evaluation = Evaluation::with([
                'anneeScolaire',
                'classe',
                'matiere',
                'periode',
            ])
            ->findOrFail(
                $request->id_evaluation
            );

            $this->verifierEvaluation($evaluation);
        }


        /*
        |--------------------------------------------------------------------------
        | Élève sélectionné
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_eleve')) {

            $eleve = Eleve::findOrFail(
                $request->id_eleve
            );

            $this->verifierEleve($eleve);
        }


        /*
        |--------------------------------------------------------------------------
        | Élèves disponibles pour l'évaluation
        |--------------------------------------------------------------------------
        */

        $eleves = collect();

        if ($evaluation) {

            $eleves = Inscription::with('eleve')
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
                ->whereHas('eleve', function ($query) use ($idEtablissement) {
                    $query->where(
                        'id_etablissement',
                        $idEtablissement
                    );
                })
                ->orderBy('id_inscription')
                ->get()
                ->pluck('eleve')
                ->filter();
        }


        /*
        |--------------------------------------------------------------------------
        | Liste des évaluations de l'établissement
        |--------------------------------------------------------------------------
        */

        if (!$evaluation) {

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

        } else {

            $evaluations = collect();
        }


        return view(
            'notes.create',
            compact(
                'evaluation',
                'eleve',
                'evaluations',
                'eleves'
            )
        );
    }


    /**
     * Enregistrement d'une note.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'id_evaluation' => [
                'required',
                'exists:evaluations,id_evaluation',
            ],

            'id_eleve' => [
                'required',
                'exists:eleves,id_eleve',
            ],

            'note' => [
                'required',
                'numeric',
                'min:0',
            ],

            'appreciation' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Évaluation
        |--------------------------------------------------------------------------
        */

        $evaluation = Evaluation::with([
            'classe',
            'anneeScolaire',
            'matiere',
            'periode',
        ])
        ->findOrFail(
            $validated['id_evaluation']
        );

        $this->verifierEvaluation($evaluation);


        /*
        |--------------------------------------------------------------------------
        | Élève
        |--------------------------------------------------------------------------
        */

        $eleve = Eleve::findOrFail(
            $validated['id_eleve']
        );

        $this->verifierEleve($eleve);


        /*
        |--------------------------------------------------------------------------
        | Vérification de la note maximale
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['note'] >
            (float) $evaluation->note_maximale
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'note' =>
                        'La note ne peut pas dépasser ' .
                        $evaluation->note_maximale . '.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'inscription de l'élève
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::where(
            'id_eleve',
            $validated['id_eleve']
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
        ->whereHas('eleve', function ($query) {
            $query->where(
                'id_etablissement',
                $this->getEtablissementId()
            );
        })
        ->first();


        if (!$inscription) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_eleve' =>
                        'Cet élève n’est pas inscrit dans la classe de cette évaluation.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier si une note existe déjà
        |--------------------------------------------------------------------------
        */

        $existe = Note::where(
            'id_evaluation',
            $validated['id_evaluation']
        )
        ->where(
            'id_eleve',
            $validated['id_eleve']
        )
        ->exists();


        if ($existe) {

            return back()
                ->withInput()
                ->withErrors([
                    'id_eleve' =>
                        'Cet élève possède déjà une note pour cette évaluation.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Création + journalisation
        |--------------------------------------------------------------------------
        */

        $note = DB::transaction(function () use (
            $validated,
            $request
        ) {

            $note = Note::create(
                $validated
            );

            JournalActivite::create([
                'id_utilisateur' => Auth::id(),
                'action' => 'Ajout d’une note',
                'table_concernee' => 'notes',
                'id_enregistrement' => $note->id_note,
                'anciennes_valeurs' => null,
                'nouvelles_valeurs' => json_encode(
                    $note->fresh()->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),
                'adresse_ip' => $request->ip(),
                'navigateur' => $request->userAgent(),
                'date_heure' => now(),
            ]);

            return $note;
        });


        return redirect()
            ->route(
                'evaluations.show',
                $evaluation->id_evaluation
            )
            ->with(
                'success',
                'Note ajoutée avec succès.'
            );
    }


    /**
     * Affichage d'une note.
     */
    public function show(Note $note)
    {
        $this->verifierNote($note);

        $note->load([
            'evaluation.anneeScolaire',
            'evaluation.classe',
            'evaluation.matiere',
            'evaluation.periode',
            'eleve',
        ]);

        return view(
            'notes.show',
            compact('note')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Note $note)
    {
        $this->verifierNote($note);

        $note->load([
            'evaluation.anneeScolaire',
            'evaluation.classe',
            'evaluation.matiere',
            'evaluation.periode',
            'eleve',
        ]);

        return view(
            'notes.edit',
            compact('note')
        );
    }


    /**
     * Modification d'une note.
     */
    public function update(
        Request $request,
        Note $note
    ) {

        $this->verifierNote($note);

        $validated = $request->validate([

            'note' => [
                'required',
                'numeric',
                'min:0',
            ],

            'appreciation' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);


        $evaluation = $note->evaluation;


        /*
        |--------------------------------------------------------------------------
        | Vérification de la note maximale
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['note'] >
            (float) $evaluation->note_maximale
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'note' =>
                        'La note ne peut pas dépasser ' .
                        $evaluation->note_maximale . '.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $note->getAttributes();


        /*
        |--------------------------------------------------------------------------
        | Modification + journalisation
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $note,
            $validated,
            $anciennesValeurs,
            $request
        ) {

            $note->update(
                $validated
            );

            JournalActivite::create([
                'id_utilisateur' => Auth::id(),
                'action' => 'Modification d’une note',
                'table_concernee' => 'notes',
                'id_enregistrement' => $note->id_note,
                'anciennes_valeurs' => json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),
                'nouvelles_valeurs' => json_encode(
                    $note->fresh()->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),
                'adresse_ip' => $request->ip(),
                'navigateur' => $request->userAgent(),
                'date_heure' => now(),
            ]);
        });


        return redirect()
            ->route(
                'evaluations.show',
                $evaluation->id_evaluation
            )
            ->with(
                'success',
                'Note modifiée avec succès.'
            );
    }


    /**
     * Suppression d'une note.
     */
    public function destroy(
        Request $request,
        Note $note
    ) {

        $this->verifierNote($note);

        $evaluationId =
            $note->id_evaluation;

        $anciennesValeurs =
            $note->getAttributes();

        $idNote =
            $note->id_note;


        /*
        |--------------------------------------------------------------------------
        | Suppression + journalisation
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $note,
            $anciennesValeurs,
            $idNote,
            $request
        ) {

            $note->delete();

            JournalActivite::create([
                'id_utilisateur' => Auth::id(),
                'action' => 'Suppression d’une note',
                'table_concernee' => 'notes',
                'id_enregistrement' => $idNote,
                'anciennes_valeurs' => json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),
                'nouvelles_valeurs' => null,
                'adresse_ip' => $request->ip(),
                'navigateur' => $request->userAgent(),
                'date_heure' => now(),
            ]);
        });


        return redirect()
            ->route(
                'evaluations.show',
                $evaluationId
            )
            ->with(
                'success',
                'Note supprimée avec succès.'
            );
    }
}
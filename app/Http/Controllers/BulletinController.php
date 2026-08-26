<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use App\Models\PeriodeScolaire;
use App\Models\Inscription;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BulletinController extends Controller
{
    /**
     * Liste des élèves pouvant consulter leur bulletin.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->id_etablissement) {
            abort(403, 'Aucun établissement associé à votre compte.');
        }

        $idEtablissement = $user->id_etablissement;

        /*
        |--------------------------------------------------------------------------
        | Années scolaires de l'établissement
        |--------------------------------------------------------------------------
        */

        $anneesScolaires = AnneeScolaire::where(
            'id_etablissement',
            $idEtablissement
        )
            ->orderByDesc('id_annee_scolaire')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Classes de l'établissement
        |--------------------------------------------------------------------------
        */

        $classes = Classe::where(
            'id_etablissement',
            $idEtablissement
        )
            ->orderBy('libelle')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Périodes scolaires
        |--------------------------------------------------------------------------
        */

        $periodes = PeriodeScolaire::whereHas(
            'anneeScolaire',
            function ($query) use ($idEtablissement) {
                $query->where(
                    'id_etablissement',
                    $idEtablissement
                );
            }
        )
            ->orderBy('date_debut')
            ->get();

        $eleves = collect();

        /*
        |--------------------------------------------------------------------------
        | Recherche des élèves
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('id_annee_scolaire') &&
            $request->filled('id_classe')
        ) {

            $annee = AnneeScolaire::where(
                'id_annee_scolaire',
                $request->id_annee_scolaire
            )
                ->where(
                    'id_etablissement',
                    $idEtablissement
                )
                ->first();

            $classe = Classe::where(
                'id_classe',
                $request->id_classe
            )
                ->where(
                    'id_etablissement',
                    $idEtablissement
                )
                ->first();

            if ($annee && $classe) {

                $eleves = Inscription::with('eleve')
                    ->where(
                        'id_annee_scolaire',
                        $annee->id_annee_scolaire
                    )
                    ->where(
                        'id_classe',
                        $classe->id_classe
                    )
                    ->where('statut', 'INSCRIT')
                    ->whereHas(
                        'eleve',
                        function ($query) use ($idEtablissement) {
                            $query->where(
                                'id_etablissement',
                                $idEtablissement
                            );
                        }
                    )
                    ->orderBy('id_inscription')
                    ->get()
                    ->pluck('eleve')
                    ->filter();
            }
        }

        return view(
            'bulletins.index',
            compact(
                'anneesScolaires',
                'periodes',
                'classes',
                'eleves'
            )
        );
    }


    /**
     * Afficher le bulletin d'un élève.
     */
    public function show(
        Request $request,
        Eleve $eleve
    ) {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Vérification utilisateur
        |--------------------------------------------------------------------------
        */

        if (!$user || !$user->id_etablissement) {
            abort(403, 'Aucun établissement associé à votre compte.');
        }

        $idEtablissement = $user->id_etablissement;

        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'élève appartient à l'établissement
        |--------------------------------------------------------------------------
        */

        if (
            (int) $eleve->id_etablissement !==
            (int) $idEtablissement
        ) {
            abort(
                403,
                'Cet élève n’appartient pas à votre établissement.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'id_annee_scolaire' => [
                'required',
                'integer',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'id_periode' => [
                'required',
                'integer',
                'exists:periodes_scolaires,id_periode',
            ],

            'id_classe' => [
                'required',
                'integer',
                'exists:classes,id_classe',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Année scolaire
        |--------------------------------------------------------------------------
        */

        $anneeScolaire = AnneeScolaire::where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
            ->where(
                'id_etablissement',
                $idEtablissement
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Établissement
        |--------------------------------------------------------------------------
        */

        $etablissement = Etablissement::findOrFail(
            $idEtablissement
        );

        /*
        |--------------------------------------------------------------------------
        | Classe
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
            ->where(
                'id_annee_scolaire',
                $anneeScolaire->id_annee_scolaire
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Période scolaire
        |--------------------------------------------------------------------------
        */

        $periode = PeriodeScolaire::where(
            'id_periode',
            $validated['id_periode']
        )
            ->whereHas(
                'anneeScolaire',
                function ($query) use (
                    $idEtablissement,
                    $anneeScolaire
                ) {
                    $query
                        ->where(
                            'id_etablissement',
                            $idEtablissement
                        )
                        ->where(
                            'id_annee_scolaire',
                            $anneeScolaire->id_annee_scolaire
                        );
                }
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'inscription
        |--------------------------------------------------------------------------
        */

        $inscription = Inscription::where(
            'id_eleve',
            $eleve->id_eleve
        )
            ->where(
                'id_annee_scolaire',
                $anneeScolaire->id_annee_scolaire
            )
            ->where(
                'id_classe',
                $classe->id_classe
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
            ->first();

        if (!$inscription) {
            return redirect()
                ->route('bulletins.index')
                ->with(
                    'error',
                    'Cet élève n’est pas inscrit dans cette classe pour cette année scolaire.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Évaluations
        |--------------------------------------------------------------------------
        */

        $evaluations = Evaluation::with('matiere')
            ->where(
                'id_annee_scolaire',
                $anneeScolaire->id_annee_scolaire
            )
            ->where(
                'id_classe',
                $classe->id_classe
            )
            ->where(
                'id_periode',
                $periode->id_periode
            )
            ->whereHas(
                'classe',
                function ($query) use ($idEtablissement) {
                    $query->where(
                        'id_etablissement',
                        $idEtablissement
                    );
                }
            )
            ->orderBy('id_matiere')
            ->orderBy('date_evaluation')
            ->orderBy('id_evaluation')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

        $notes = Note::where(
            'id_eleve',
            $eleve->id_eleve
        )
            ->whereIn(
                'id_evaluation',
                $evaluations->pluck('id_evaluation')
            )
            ->get()
            ->keyBy('id_evaluation');

        /*
        |--------------------------------------------------------------------------
        | Regroupement par matière
        |--------------------------------------------------------------------------
        */

        $matieres = $evaluations->groupBy(
            'id_matiere'
        );

        $resultats = collect();

        $totalPoints = 0;
        $totalCoefficients = 0;

        /*
        |--------------------------------------------------------------------------
        | Calcul des résultats
        |--------------------------------------------------------------------------
        */

        foreach (
            $matieres as $idMatiere => $evaluationsMatiere
        ) {

            $matiere = $evaluationsMatiere
                ->first()
                ->matiere;

            if (!$matiere) {
                continue;
            }

            $notesMatiere = collect();

            foreach (
                $evaluationsMatiere as $evaluation
            ) {

                $note = $notes->get(
                    $evaluation->id_evaluation
                );

                if (!$note) {
                    continue;
                }

                $noteMaximale =
                    (float) $evaluation->note_maximale;

                $noteSur20 =
                    $noteMaximale > 0
                        ? (
                            (float) $note->note /
                            $noteMaximale
                        ) * 20
                        : 0;

                $notesMatiere->push([
                    'evaluation' => $evaluation,
                    'note' => (float) $note->note,
                    'note_maximale' => $noteMaximale,
                    'note_sur_20' => $noteSur20,
                    'appreciation' => $note->appreciation,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Moyenne matière
            |--------------------------------------------------------------------------
            */

            $moyenne = null;

            if ($notesMatiere->isNotEmpty()) {
                $moyenne =
                    $notesMatiere->avg(
                        'note_sur_20'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Coefficient
            |--------------------------------------------------------------------------
            */

            $coefficient =
                (float) $matiere->coefficient;

            /*
            |--------------------------------------------------------------------------
            | Points
            |--------------------------------------------------------------------------
            */

            $points =
                $moyenne !== null
                    ? $moyenne * $coefficient
                    : 0;

            /*
            |--------------------------------------------------------------------------
            | Totaux
            |--------------------------------------------------------------------------
            */

            if ($moyenne !== null) {

                $totalPoints += $points;

                $totalCoefficients +=
                    $coefficient;
            }

            /*
            |--------------------------------------------------------------------------
            | Résultat matière
            |--------------------------------------------------------------------------
            */

            $resultats->push([
                'matiere' => $matiere,
                'evaluations' => $notesMatiere,
                'moyenne' => $moyenne,
                'coefficient' => $coefficient,
                'points' => $points,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Moyenne générale
        |--------------------------------------------------------------------------
        */

        $moyenneGenerale =
            $totalCoefficients > 0
                ? $totalPoints /
                    $totalCoefficients
                : null;

        /*
        |--------------------------------------------------------------------------
        | Retour vers la vue
        |--------------------------------------------------------------------------
        */

        return view(
            'bulletins.show',
            compact(
                'eleve',
                'inscription',
                'etablissement',
                'anneeScolaire',
                'periode',
                'classe',
                'resultats',
                'totalPoints',
                'totalCoefficients',
                'moyenneGenerale'
            )
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\PeriodeScolaire;
use App\Models\AnneeScolaire;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriodeScolaireController extends Controller
{
    /**
     * Retourne l'établissement de l'utilisateur connecté.
     */
    private function idEtablissement()
    {
        $user = Auth::user();

        if (!$user || !$user->id_etablissement) {
            abort(
                403,
                'Aucun établissement associé à votre compte.'
            );
        }

        return $user->id_etablissement;
    }


    /**
     * Liste des périodes scolaires.
     */
    public function index()
    {
        $idEtablissement = $this->idEtablissement();

        $periodes = PeriodeScolaire::with('anneeScolaire')
            ->whereHas(
                'anneeScolaire',
                function ($query) use ($idEtablissement) {
                    $query->where(
                        'id_etablissement',
                        $idEtablissement
                    );
                }
            )
            ->orderByDesc('date_debut')
            ->get();

        return view(
            'periodes_scolaires.index',
            compact('periodes')
        );
    }


    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        $idEtablissement = $this->idEtablissement();

        /*
        |--------------------------------------------------------------------------
        | Années scolaires appartenant à l'établissement
        |--------------------------------------------------------------------------
        */

        $annees = AnneeScolaire::where(
            'id_etablissement',
            $idEtablissement
        )
            ->orderByDesc('id_annee_scolaire')
            ->get();

        return view(
            'periodes_scolaires.create',
            compact('annees')
        );
    }


    /**
     * Enregistrement d'une période scolaire.
     */
    public function store(Request $request)
    {
        $idEtablissement = $this->idEtablissement();

        /*
        |--------------------------------------------------------------------------
        | Validation de base
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'id_annee_scolaire' => [
                'required',
                'integer',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'libelle' => [
                'required',
                'string',
                'max:100',
            ],

            'date_debut' => [
                'required',
                'date',
            ],

            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'année appartient à l'établissement
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
            ->first();

        if (!$anneeScolaire) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cette année scolaire n’appartient pas à votre établissement.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier que les dates sont dans l'année scolaire
        |--------------------------------------------------------------------------
        */

        if (
            $validated['date_debut'] <
            $anneeScolaire->date_debut ||
            $validated['date_fin'] >
            $anneeScolaire->date_fin
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Les dates de la période doivent être comprises dans les dates de l’année scolaire.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier les chevauchements
        |--------------------------------------------------------------------------
        */

        $chevauchement = PeriodeScolaire::where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
            ->where(function ($query) use ($validated) {

                $query
                    ->whereBetween(
                        'date_debut',
                        [
                            $validated['date_debut'],
                            $validated['date_fin'],
                        ]
                    )
                    ->orWhereBetween(
                        'date_fin',
                        [
                            $validated['date_debut'],
                            $validated['date_fin'],
                        ]
                    )
                    ->orWhere(function ($query) use ($validated) {

                        $query
                            ->where(
                                'date_debut',
                                '<=',
                                $validated['date_debut']
                            )
                            ->where(
                                'date_fin',
                                '>=',
                                $validated['date_fin']
                            );
                    });
            })
            ->exists();

        if ($chevauchement) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Les dates de cette période chevauchent une période scolaire existante.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier le doublon de libellé
        |--------------------------------------------------------------------------
        */

        $libelleExiste = PeriodeScolaire::where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
            ->whereRaw(
                'LOWER(libelle) = ?',
                [mb_strtolower($validated['libelle'])]
            )
            ->exists();

        if ($libelleExiste) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Une période portant ce libellé existe déjà pour cette année scolaire.'
                );
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

            $periode = PeriodeScolaire::create(
                $validated
            );

            JournalActivite::create([
                'id_utilisateur' => Auth::id(),
                'action' => 'Ajout d’une période scolaire',
                'table_concernee' => 'periodes_scolaires',
                'id_enregistrement' => $periode->id_periode,
                'anciennes_valeurs' => null,
                'nouvelles_valeurs' => json_encode(
                    $periode->fresh()->getAttributes(),
                    JSON_UNESCAPED_UNICODE
                ),
                'adresse_ip' => $request->ip(),
                'navigateur' => $request->userAgent(),
                'date_heure' => now(),
            ]);
        });

        return redirect()
            ->route('periodes-scolaires.index')
            ->with(
                'success',
                'Période scolaire ajoutée avec succès.'
            );
    }


    /**
     * Affichage d'une période scolaire.
     */
    public function show(
        PeriodeScolaire $periodeScolaire
    ) {
        $idEtablissement = $this->idEtablissement();

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'établissement
        |--------------------------------------------------------------------------
        */

        $this->verifierAppartenance(
            $periodeScolaire,
            $idEtablissement
        );

        $periodeScolaire->load([
            'anneeScolaire',
            'evaluations'
        ]);

        return view(
            'periodes_scolaires.show',
            compact('periodeScolaire')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(
        PeriodeScolaire $periodeScolaire
    ) {
        $idEtablissement = $this->idEtablissement();

        /*
        |--------------------------------------------------------------------------
        | Vérifier que la période appartient à l'établissement
        |--------------------------------------------------------------------------
        */

        $this->verifierAppartenance(
            $periodeScolaire,
            $idEtablissement
        );

        /*
        |--------------------------------------------------------------------------
        | Années scolaires de l'établissement
        |--------------------------------------------------------------------------
        */

        $annees = AnneeScolaire::where(
            'id_etablissement',
            $idEtablissement
        )
            ->orderByDesc('id_annee_scolaire')
            ->get();

        return view(
            'periodes_scolaires.edit',
            compact(
                'periodeScolaire',
                'annees'
            )
        );
    }


    /**
     * Modification d'une période scolaire.
     */
    public function update(
        Request $request,
        PeriodeScolaire $periodeScolaire
    ) {
        $idEtablissement = $this->idEtablissement();

        /*
        |--------------------------------------------------------------------------
        | Vérifier l'appartenance
        |--------------------------------------------------------------------------
        */

        $this->verifierAppartenance(
            $periodeScolaire,
            $idEtablissement
        );

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

            'libelle' => [
                'required',
                'string',
                'max:100',
            ],

            'date_debut' => [
                'required',
                'date',
            ],

            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérifier la nouvelle année scolaire
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
            ->first();

        if (!$anneeScolaire) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cette année scolaire n’appartient pas à votre établissement.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier les dates
        |--------------------------------------------------------------------------
        */

        if (
            $validated['date_debut'] <
            $anneeScolaire->date_debut ||
            $validated['date_fin'] >
            $anneeScolaire->date_fin
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Les dates de la période doivent être comprises dans les dates de l’année scolaire.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier les chevauchements
        |--------------------------------------------------------------------------
        */

        $chevauchement = PeriodeScolaire::where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
            ->where(
                'id_periode',
                '!=',
                $periodeScolaire->id_periode
            )
            ->where(function ($query) use ($validated) {

                $query
                    ->whereBetween(
                        'date_debut',
                        [
                            $validated['date_debut'],
                            $validated['date_fin'],
                        ]
                    )
                    ->orWhereBetween(
                        'date_fin',
                        [
                            $validated['date_debut'],
                            $validated['date_fin'],
                        ]
                    )
                    ->orWhere(function ($query) use ($validated) {

                        $query
                            ->where(
                                'date_debut',
                                '<=',
                                $validated['date_debut']
                            )
                            ->where(
                                'date_fin',
                                '>=',
                                $validated['date_fin']
                            );
                    });
            })
            ->exists();

        if ($chevauchement) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Les dates de cette période chevauchent une autre période scolaire.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier le libellé
        |--------------------------------------------------------------------------
        */

        $libelleExiste = PeriodeScolaire::where(
            'id_annee_scolaire',
            $anneeScolaire->id_annee_scolaire
        )
            ->where(
                'id_periode',
                '!=',
                $periodeScolaire->id_periode
            )
            ->whereRaw(
                'LOWER(libelle) = ?',
                [mb_strtolower($validated['libelle'])]
            )
            ->exists();

        if ($libelleExiste) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Une période portant ce libellé existe déjà pour cette année scolaire.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $periodeScolaire->getAttributes();

        /*
        |--------------------------------------------------------------------------
        | Modification
        |--------------------------------------------------------------------------
        */

        $periodeScolaire->update(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Nouvelles valeurs
        |--------------------------------------------------------------------------
        */

        $nouvellesValeurs =
            $periodeScolaire
                ->fresh()
                ->getAttributes();

        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([
            'id_utilisateur' => Auth::id(),
            'action' => 'Modification d’une période scolaire',
            'table_concernee' => 'periodes_scolaires',
            'id_enregistrement' => $periodeScolaire->id_periode,
            'anciennes_valeurs' => json_encode(
                $anciennesValeurs,
                JSON_UNESCAPED_UNICODE
            ),
            'nouvelles_valeurs' => json_encode(
                $nouvellesValeurs,
                JSON_UNESCAPED_UNICODE
            ),
            'adresse_ip' => $request->ip(),
            'navigateur' => $request->userAgent(),
            'date_heure' => now(),
        ]);

        return redirect()
            ->route('periodes-scolaires.index')
            ->with(
                'success',
                'Période scolaire modifiée avec succès.'
            );
    }


    /**
     * Suppression d'une période scolaire.
     */
    public function destroy(
        Request $request,
        PeriodeScolaire $periodeScolaire
    ) {
        $idEtablissement = $this->idEtablissement();

        /*
        |--------------------------------------------------------------------------
        | Vérifier l'appartenance
        |--------------------------------------------------------------------------
        */

        $this->verifierAppartenance(
            $periodeScolaire,
            $idEtablissement
        );

        /*
        |--------------------------------------------------------------------------
        | Vérifier si des évaluations utilisent cette période
        |--------------------------------------------------------------------------
        */

        if (
            $periodeScolaire
                ->evaluations()
                ->exists()
        ) {
            return redirect()
                ->route('periodes-scolaires.index')
                ->with(
                    'error',
                    'Cette période scolaire ne peut pas être supprimée car elle possède des évaluations.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $periodeScolaire->getAttributes();

        $idPeriode =
            $periodeScolaire->id_periode;

        /*
        |--------------------------------------------------------------------------
        | Suppression + journalisation
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $periodeScolaire,
            $anciennesValeurs,
            $idPeriode,
            $request
        ) {

            $periodeScolaire->delete();

            JournalActivite::create([
                'id_utilisateur' => Auth::id(),
                'action' => 'Suppression d’une période scolaire',
                'table_concernee' => 'periodes_scolaires',
                'id_enregistrement' => $idPeriode,
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
            ->route('periodes-scolaires.index')
            ->with(
                'success',
                'Période scolaire supprimée avec succès.'
            );
    }


    /**
     * Vérifie qu'une période appartient bien
     * à l'établissement de l'utilisateur.
     */
    private function verifierAppartenance(
        PeriodeScolaire $periodeScolaire,
        $idEtablissement
    ) {
        $appartient = AnneeScolaire::where(
            'id_annee_scolaire',
            $periodeScolaire->id_annee_scolaire
        )
            ->where(
                'id_etablissement',
                $idEtablissement
            )
            ->exists();

        if (!$appartient) {
            abort(
                403,
                'Cette période scolaire n’appartient pas à votre établissement.'
            );
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\AnneeScolaire;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    /**
     * Retourne l'ID de l'établissement de l'utilisateur connecté.
     */
    private function getEtablissementId()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Utilisateur non authentifié.');
        }

        // Super administrateur : accès global
        if (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        ) {
            return null;
        }

        if ($user->id_etablissement === null) {
            abort(
                403,
                'Votre compte n’est associé à aucun établissement.'
            );
        }

        return $user->id_etablissement;
    }


    /**
     * Liste des inscriptions.
     */
    public function index()
    {
        $idEtablissement = $this->getEtablissementId();

        $query = Inscription::with([
            'eleve',
            'classe',
            'anneeScolaire',
            'fraisEleves',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Séparation par établissement
        |--------------------------------------------------------------------------
        */

        if ($idEtablissement !== null) {

            $query->whereHas('eleve', function ($q) use ($idEtablissement) {

                $q->where(
                    'id_etablissement',
                    $idEtablissement
                );

            });
        }

        $inscriptions = $query
            ->orderByDesc('id_inscription')
            ->get();

        return view(
            'inscriptions.index',
            compact('inscriptions')
        );
    }


    /**
     * Formulaire d'ajout.
     */
    public function create()
    {
        $idEtablissement = $this->getEtablissementId();

        /*
        |--------------------------------------------------------------------------
        | Élèves
        |--------------------------------------------------------------------------
        */

        $elevesQuery = Eleve::where(
            'statut',
            'ACTIF'
        );

        if ($idEtablissement !== null) {

            $elevesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $eleves = $elevesQuery
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classesQuery = Classe::where(
            'statut',
            'ACTIVE'
        );

        if ($idEtablissement !== null) {

            $classesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $classes = $classesQuery
            ->orderBy('libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        |
        | Les années scolaires ne possèdent pas forcément id_etablissement
        | dans ton architecture actuelle.
        |
        */

        $annees = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();


        return view(
            'inscriptions.create',
            compact(
                'eleves',
                'classes',
                'annees'
            )
        );
    }


    /**
     * Enregistre une inscription.
     */
    public function store(Request $request)
    {
        $idEtablissement = $this->getEtablissementId();


        /*
        |--------------------------------------------------------------------------
        | Validation de base
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_eleve' => [
                'required',
                'exists:eleves,id_eleve',
            ],

            'id_annee_scolaire' => [
                'required',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'id_classe' => [
                'required',
                'exists:classes,id_classe',
            ],

            'date_inscription' => [
                'required',
                'date',
            ],

            'statut' => [
                'required',
                'in:INSCRIT,ABANDON,TRANSFERE,RADIE,DIPLOME',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'élève
        |--------------------------------------------------------------------------
        */

        $eleveQuery = Eleve::where(
            'id_eleve',
            $validated['id_eleve']
        );

        if ($idEtablissement !== null) {

            $eleveQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $eleve = $eleveQuery->first();

        if (!$eleve) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cet élève n’appartient pas à votre établissement.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        */

        $classeQuery = Classe::where(
            'id_classe',
            $validated['id_classe']
        );

        if ($idEtablissement !== null) {

            $classeQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $classe = $classeQuery->first();

        if (!$classe) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cette classe n’appartient pas à votre établissement.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'élève et la classe appartiennent au même établissement
        |--------------------------------------------------------------------------
        */

        if (
            $idEtablissement !== null &&
            $eleve->id_etablissement != $classe->id_etablissement
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'L’élève et la classe sélectionnés appartiennent à des établissements différents.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérification du doublon
        |--------------------------------------------------------------------------
        */

        $existe = Inscription::where(
            'id_eleve',
            $validated['id_eleve']
        )
        ->where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
        ->exists();

        if ($existe) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cet élève est déjà inscrit pour cette année scolaire.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Création + génération des frais + journalisation
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $request
        ) {

            $inscription = Inscription::create(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Génération automatique des frais
            |--------------------------------------------------------------------------
            */

            $inscription->genererFrais();


            /*
            |--------------------------------------------------------------------------
            | Journalisation
            |--------------------------------------------------------------------------
            */

            JournalActivite::create([

                'id_utilisateur' => Auth::id(),

                'action' => 'Ajout d’une inscription',

                'table_concernee' => 'inscriptions',

                'id_enregistrement' =>
                    $inscription->id_inscription,

                'anciennes_valeurs' => null,

                'nouvelles_valeurs' => json_encode(
                    $inscription
                        ->fresh()
                        ->toArray(),
                    JSON_UNESCAPED_UNICODE
                ),

                'adresse_ip' => $request->ip(),

                'navigateur' => $request->userAgent(),

                'date_heure' => now(),
            ]);
        });


        return redirect()
            ->route('inscriptions.index')
            ->with(
                'success',
                'Inscription enregistrée et frais scolaires générés avec succès.'
            );
    }


    /**
     * Affiche une inscription.
     */
    public function show(
        Inscription $inscription
    ) {
        $idEtablissement = $this->getEtablissementId();


        /*
        |--------------------------------------------------------------------------
        | Sécurité : vérifier l'établissement
        |--------------------------------------------------------------------------
        */

        if ($idEtablissement !== null) {

            $appartient = $inscription
                ->eleve()
                ->where(
                    'id_etablissement',
                    $idEtablissement
                )
                ->exists();

            if (!$appartient) {
                abort(
                    403,
                    'Cette inscription n’appartient pas à votre établissement.'
                );
            }
        }


        $inscription->load([
            'eleve',
            'classe',
            'anneeScolaire',
            'fraisEleves',
        ]);


        return view(
            'inscriptions.show',
            compact('inscription')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(
        Inscription $inscription
    ) {
        $idEtablissement = $this->getEtablissementId();


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'accès à l'inscription
        |--------------------------------------------------------------------------
        */

        if ($idEtablissement !== null) {

            $appartient = $inscription
                ->eleve()
                ->where(
                    'id_etablissement',
                    $idEtablissement
                )
                ->exists();

            if (!$appartient) {
                abort(
                    403,
                    'Cette inscription n’appartient pas à votre établissement.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Élèves
        |--------------------------------------------------------------------------
        */

        $elevesQuery = Eleve::query();

        if ($idEtablissement !== null) {

            $elevesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $eleves = $elevesQuery
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classesQuery = Classe::query();

        if ($idEtablissement !== null) {

            $classesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $classes = $classesQuery
            ->orderBy('libelle')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */

        $annees = AnneeScolaire::orderByDesc(
            'id_annee_scolaire'
        )->get();


        return view(
            'inscriptions.edit',
            compact(
                'inscription',
                'eleves',
                'classes',
                'annees'
            )
        );
    }


    /**
     * Mise à jour.
     */
    public function update(
        Request $request,
        Inscription $inscription
    ) {
        $idEtablissement = $this->getEtablissementId();


        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'inscription appartient à l'établissement
        |--------------------------------------------------------------------------
        */

        if ($idEtablissement !== null) {

            $appartient = $inscription
                ->eleve()
                ->where(
                    'id_etablissement',
                    $idEtablissement
                )
                ->exists();

            if (!$appartient) {

                abort(
                    403,
                    'Cette inscription n’appartient pas à votre établissement.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_eleve' => [
                'required',
                'exists:eleves,id_eleve',
            ],

            'id_annee_scolaire' => [
                'required',
                'exists:annees_scolaires,id_annee_scolaire',
            ],

            'id_classe' => [
                'required',
                'exists:classes,id_classe',
            ],

            'date_inscription' => [
                'required',
                'date',
            ],

            'statut' => [
                'required',
                'in:INSCRIT,ABANDON,TRANSFERE,RADIE,DIPLOME',
            ],

            'observation' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'élève
        |--------------------------------------------------------------------------
        */

        $eleveQuery = Eleve::where(
            'id_eleve',
            $validated['id_eleve']
        );

        if ($idEtablissement !== null) {

            $eleveQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $eleve = $eleveQuery->first();

        if (!$eleve) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cet élève n’appartient pas à votre établissement.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        */

        $classeQuery = Classe::where(
            'id_classe',
            $validated['id_classe']
        );

        if ($idEtablissement !== null) {

            $classeQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $classe = $classeQuery->first();

        if (!$classe) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cette classe n’appartient pas à votre établissement.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier la cohérence établissement
        |--------------------------------------------------------------------------
        */

        if (
            $idEtablissement !== null &&
            $eleve->id_etablissement != $classe->id_etablissement
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'L’élève et la classe appartiennent à des établissements différents.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Vérification du doublon
        |--------------------------------------------------------------------------
        */

        $existe = Inscription::where(
            'id_eleve',
            $validated['id_eleve']
        )
        ->where(
            'id_annee_scolaire',
            $validated['id_annee_scolaire']
        )
        ->where(
            'id_inscription',
            '!=',
            $inscription->id_inscription
        )
        ->exists();

        if ($existe) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cet élève est déjà inscrit pour cette année scolaire.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $inscription->toArray();


        /*
        |--------------------------------------------------------------------------
        | Modification
        |--------------------------------------------------------------------------
        */

        $inscription->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Nouvelles valeurs
        |--------------------------------------------------------------------------
        */

        $nouvellesValeurs =
            $inscription
                ->fresh()
                ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' => Auth::id(),

            'action' =>
                'Modification d’une inscription',

            'table_concernee' =>
                'inscriptions',

            'id_enregistrement' =>
                $inscription->id_inscription,

            'anciennes_valeurs' =>
                json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

            'nouvelles_valeurs' =>
                json_encode(
                    $nouvellesValeurs,
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
            ->route('inscriptions.index')
            ->with(
                'success',
                'Inscription modifiée avec succès.'
            );
    }


    /**
     * Suppression.
     */
    public function destroy(
        Request $request,
        Inscription $inscription
    ) {
        $idEtablissement =
            $this->getEtablissementId();


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'établissement
        |--------------------------------------------------------------------------
        */

        if ($idEtablissement !== null) {

            $appartient = $inscription
                ->eleve()
                ->where(
                    'id_etablissement',
                    $idEtablissement
                )
                ->exists();

            if (!$appartient) {

                abort(
                    403,
                    'Cette inscription n’appartient pas à votre établissement.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Vérifier les frais associés
        |--------------------------------------------------------------------------
        */

        if (
            $inscription
                ->fraisEleves()
                ->exists()
        ) {

            return back()
                ->with(
                    'error',
                    'Impossible de supprimer cette inscription car des frais lui sont associés.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $inscription->toArray();

        $idInscription =
            $inscription->id_inscription;


        /*
        |--------------------------------------------------------------------------
        | Suppression
        |--------------------------------------------------------------------------
        */

        $inscription->delete();


        /*
        |--------------------------------------------------------------------------
        | Journalisation
        |--------------------------------------------------------------------------
        */

        JournalActivite::create([

            'id_utilisateur' =>
                Auth::id(),

            'action' =>
                'Suppression d’une inscription',

            'table_concernee' =>
                'inscriptions',

            'id_enregistrement' =>
                $idInscription,

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


        return redirect()
            ->route('inscriptions.index')
            ->with(
                'success',
                'Inscription supprimée avec succès.'
            );
    }
}
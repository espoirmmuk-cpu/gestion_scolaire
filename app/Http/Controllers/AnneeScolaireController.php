<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnneeScolaireController extends Controller
{
    /**
     * Retourne l'identifiant de l'établissement courant.
     *
     * NULL = administrateur global / Super Administrateur.
     */
    private function etablissementCourant()
    {
        return auth()->user()->id_etablissement;
    }


    /**
     * Vérifie qu'une année scolaire appartient
     * à l'établissement de l'utilisateur connecté.
     */
    private function verifierEtablissement($annee)
    {
        /*
        |--------------------------------------------------------------------------
        | ADMINISTRATEUR GLOBAL
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() === null) {

            if (auth()->user()->aLeRole('Administrateur')) {
                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR D'UN ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if (
            (int) $annee->id_etablissement !==
            (int) $this->etablissementCourant()
        ) {

            abort(
                403,
                'Vous n’avez pas accès à cette année scolaire.'
            );
        }
    }


    /**
     * Vérifie qu'une année n'est pas clôturée.
     */
    private function verifierNonCloturee($annee)
    {
        if ((int) ($annee->est_cloturee ?? 0) === 1) {

            abort(
                403,
                'Cette année scolaire est clôturée. Elle ne peut plus être modifiée.'
            );
        }
    }


    /**
     * Liste des années scolaires.
     */
    public function index()
    {
        $query = DB::table('annees_scolaires')
            ->orderByDesc('date_debut');


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


        $anneesScolaires = $query->get();


        return view(
            'annees_scolaires.index',
            compact('anneesScolaires')
        );
    }


    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('annees_scolaires.create');
    }


    /**
     * Enregistrement d'une année scolaire.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'libelle' => [
                'required',
                'string',
                'max:20',
            ],

            'date_debut' => [
                'required',
                'date',
            ],

            'date_fin' => [
                'required',
                'date',
                'after:date_debut',
            ],

            'est_active' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($this->etablissementCourant() === null) {

            if (!auth()->user()->aLeRole('Administrateur')) {

                abort(
                    403,
                    'Vous devez être rattaché à un établissement.'
                );
            }

            abort(
                403,
                'Veuillez sélectionner un établissement.'
            );
        }


        $idEtablissement = $this->etablissementCourant();


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION DES DATES
        |--------------------------------------------------------------------------
        */

        if (
            strtotime($validated['date_fin']) <=
            strtotime($validated['date_debut'])
        ) {

            return back()
                ->withErrors([
                    'date_fin' =>
                        'La date de fin doit être postérieure à la date de début.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION DU LIBELLÉ
        |--------------------------------------------------------------------------
        */

        $existe = DB::table('annees_scolaires')
            ->where(
                'id_etablissement',
                $idEtablissement
            )
            ->where(
                'libelle',
                $validated['libelle']
            )
            ->exists();


        if ($existe) {

            return back()
                ->withErrors([
                    'libelle' =>
                        'Cette année scolaire existe déjà dans cet établissement.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | ANNÉE ACTIVE
        |--------------------------------------------------------------------------
        */

        $estActive = !empty($validated['est_active']) ? 1 : 0;


        /*
        |--------------------------------------------------------------------------
        | UNE SEULE ANNÉE ACTIVE PAR ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        if ($estActive === 1) {

            DB::table('annees_scolaires')
                ->where(
                    'id_etablissement',
                    $idEtablissement
                )
                ->update([
                    'est_active' => 0,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CRÉATION
        |--------------------------------------------------------------------------
        */

        DB::table('annees_scolaires')->insert([
            'id_etablissement' => $idEtablissement,
            'libelle' => $validated['libelle'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'est_active' => $estActive,
            'est_cloturee' => 0,
            'date_creation' => now(),
        ]);


        return redirect()
            ->route('annees-scolaires.index')
            ->with(
                'success',
                'Année scolaire créée avec succès.'
            );
    }


    /**
     * Affichage d'une année scolaire.
     */
    public function show($id)
    {
        $annee = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $id
            )
            ->first();


        if (!$annee) {

            abort(
                404,
                'Année scolaire introuvable.'
            );
        }


        $this->verifierEtablissement($annee);


        return view(
            'annees_scolaires.show',
            compact('annee')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit($id)
    {
        $annee = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $id
            )
            ->first();


        if (!$annee) {

            abort(
                404,
                'Année scolaire introuvable.'
            );
        }


        $this->verifierEtablissement($annee);

        /*
        |--------------------------------------------------------------------------
        | UNE ANNÉE CLÔTURÉE NE PEUT PAS ÊTRE MODIFIÉE
        |--------------------------------------------------------------------------
        */

        $this->verifierNonCloturee($annee);


        return view(
            'annees_scolaires.edit',
            compact('annee')
        );
    }


    /**
     * Mise à jour.
     */
    public function update(Request $request, $id)
    {
        $annee = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $id
            )
            ->first();


        if (!$annee) {

            abort(
                404,
                'Année scolaire introuvable.'
            );
        }


        $this->verifierEtablissement($annee);

        /*
        |--------------------------------------------------------------------------
        | PROTECTION ANNÉE CLÔTURÉE
        |--------------------------------------------------------------------------
        */

        $this->verifierNonCloturee($annee);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'libelle' => [
                'required',
                'string',
                'max:20',
            ],

            'date_debut' => [
                'required',
                'date',
            ],

            'date_fin' => [
                'required',
                'date',
                'after:date_debut',
            ],

            'est_active' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION DU LIBELLÉ
        |--------------------------------------------------------------------------
        */

        $existe = DB::table('annees_scolaires')
            ->where(
                'id_etablissement',
                $annee->id_etablissement
            )
            ->where(
                'libelle',
                $validated['libelle']
            )
            ->where(
                'id_annee_scolaire',
                '!=',
                $annee->id_annee_scolaire
            )
            ->exists();


        if ($existe) {

            return back()
                ->withErrors([
                    'libelle' =>
                        'Cette année scolaire existe déjà dans cet établissement.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | ANNÉE ACTIVE
        |--------------------------------------------------------------------------
        */

        $estActive = !empty($validated['est_active']) ? 1 : 0;


        /*
        |--------------------------------------------------------------------------
        | UNE SEULE ANNÉE ACTIVE
        |--------------------------------------------------------------------------
        */

        if ($estActive === 1) {

            DB::table('annees_scolaires')
                ->where(
                    'id_etablissement',
                    $annee->id_etablissement
                )
                ->where(
                    'id_annee_scolaire',
                    '!=',
                    $annee->id_annee_scolaire
                )
                ->update([
                    'est_active' => 0,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MISE À JOUR
        |--------------------------------------------------------------------------
        */

        DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->update([
                'libelle' => $validated['libelle'],
                'date_debut' => $validated['date_debut'],
                'date_fin' => $validated['date_fin'],
                'est_active' => $estActive,
            ]);


        return redirect()
            ->route('annees-scolaires.index')
            ->with(
                'success',
                'Année scolaire modifiée avec succès.'
            );
    }


    /**
     * Clôture d'une année scolaire.
     */
    public function cloturer($id)
    {
        $annee = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $id
            )
            ->first();


        if (!$annee) {

            abort(
                404,
                'Année scolaire introuvable.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        $this->verifierEtablissement($annee);


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION SI DÉJÀ CLÔTURÉE
        |--------------------------------------------------------------------------
        */

        if ((int) ($annee->est_cloturee ?? 0) === 1) {

            return back()
                ->with(
                    'error',
                    'Cette année scolaire est déjà clôturée.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SEULE UNE ANNÉE ACTIVE PEUT ÊTRE CLÔTURÉE
        |--------------------------------------------------------------------------
        */

        if ((int) $annee->est_active !== 1) {

            return back()
                ->with(
                    'error',
                    'Seule l’année scolaire active peut être clôturée.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CLÔTURE
        |--------------------------------------------------------------------------
        */

        DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->update([
                'est_active' => 0,
                'est_cloturee' => 1,
                'date_cloture' => now(),
                'cloturee_par' => auth()->user()->id_utilisateur,
            ]);


        return redirect()
            ->route('annees-scolaires.index')
            ->with(
                'success',
                'L’année scolaire « ' .
                $annee->libelle .
                ' » a été clôturée avec succès.'
            );
    }


    /**
     * Suppression.
     */
    public function destroy($id)
    {
        $annee = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $id
            )
            ->first();


        if (!$annee) {

            abort(
                404,
                'Année scolaire introuvable.'
            );
        }


        $this->verifierEtablissement($annee);


        /*
        |--------------------------------------------------------------------------
        | PROTECTION ANNÉE CLÔTURÉE
        |--------------------------------------------------------------------------
        */

        if ((int) ($annee->est_cloturee ?? 0) === 1) {

            return back()
                ->with(
                    'error',
                    'Impossible de supprimer une année scolaire clôturée.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PROTECTION DE L'ANNÉE ACTIVE
        |--------------------------------------------------------------------------
        */

        if ((int) $annee->est_active === 1) {

            return back()
                ->with(
                    'error',
                    'Impossible de supprimer l’année scolaire active.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VÉRIFICATION DES DONNÉES ASSOCIÉES
        |--------------------------------------------------------------------------
        */

        $inscriptions = DB::table('inscriptions')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->exists();


        $evaluations = DB::table('evaluations')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->exists();


        $classes = DB::table('classes')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->exists();


        if (
            $inscriptions ||
            $evaluations ||
            $classes
        ) {

            return back()
                ->with(
                    'error',
                    'Impossible de supprimer cette année scolaire car des données scolaires y sont déjà associées.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SUPPRESSION
        |--------------------------------------------------------------------------
        */

        DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $annee->id_annee_scolaire
            )
            ->delete();


        return redirect()
            ->route('annees-scolaires.index')
            ->with(
                'success',
                'Année scolaire supprimée avec succès.'
            );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\AffectationEnseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffectationEnseignantController extends Controller
{
    /**
     * ================================================================
     * ÉTABLISSEMENT COURANT
     * ================================================================
     *
     * Retourne l'établissement de l'utilisateur connecté.
     *
     * NULL = Super Administrateur.
     */
    private function etablissementCourant()
    {
        return auth()->user()->id_etablissement;
    }


    /**
     * ================================================================
     * INDEX
     * ================================================================
     *
     * Affiche la liste des affectations.
     */
    public function index()
    {
        abort_unless(
            auth()->user()->aLaPermission('gerer_enseignants'),
            403
        );

        $idEtablissement = $this->etablissementCourant();

        $query = DB::table('affectations_enseignants')
            ->join(
                'personnel',
                'affectations_enseignants.id_enseignant',
                '=',
                'personnel.id_personnel'
            )
            ->join(
                'classes',
                'affectations_enseignants.id_classe',
                '=',
                'classes.id_classe'
            )
            ->join(
                'matieres',
                'affectations_enseignants.id_matiere',
                '=',
                'matieres.id_matiere'
            )
            ->join(
                'annees_scolaires',
                'affectations_enseignants.id_annee_scolaire',
                '=',
                'annees_scolaires.id_annee_scolaire'
            )
            ->select(
                'affectations_enseignants.*',

                'personnel.nom as enseignant_nom',
                'personnel.postnom as enseignant_postnom',
                'personnel.prenom as enseignant_prenom',
                'personnel.matricule',

                'classes.libelle as classe_libelle',

                'matieres.libelle as matiere_libelle',

                'annees_scolaires.libelle as annee_scolaire_libelle'
            );

        /*
         * Protection par établissement.
         *
         * Le Super Administrateur voit tous les établissements.
         */
        if ($idEtablissement !== null) {
            $query->where(
                'affectations_enseignants.id_etablissement',
                $idEtablissement
            );
        }

        $affectations = $query
            ->orderBy('annees_scolaires.libelle', 'desc')
            ->orderBy('classes.libelle')
            ->orderBy('matieres.libelle')
            ->orderBy('personnel.nom')
            ->get();

        return view(
            'affectations-enseignants.index',
            compact('affectations')
        );
    }


    /**
     * ================================================================
     * CREATE
     * ================================================================
     *
     * Affiche le formulaire de création.
     */
    public function create()
    {
        abort_unless(
            auth()->user()->aLaPermission('gerer_enseignants'),
            403
        );

        $idEtablissement = $this->etablissementCourant();

        /*
         * ENSEIGNANTS
         */
        $enseignantsQuery = DB::table('personnel')
            ->where('fonction', 'ENSEIGNANT')
            ->where('statut', 'ACTIF')
            ->select(
                'id_personnel',
                'matricule',
                'nom',
                'postnom',
                'prenom'
            )
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom');

        /*
         * Protection par établissement.
         */
        if ($idEtablissement !== null) {
            $enseignantsQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $enseignants = $enseignantsQuery->get();


        /*
         * CLASSES
         */
        $classesQuery = DB::table('classes')
            ->select(
                'id_classe',
                'libelle'
            )
            ->orderBy('libelle');

        if ($idEtablissement !== null) {
            $classesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $classes = $classesQuery->get();


        /*
         * MATIÈRES
         */
        $matieresQuery = DB::table('matieres')
            ->select(
                'id_matiere',
                'libelle'
            )
            ->orderBy('libelle');

        if ($idEtablissement !== null) {
            $matieresQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $matieres = $matieresQuery->get();


        /*
         * ANNÉES SCOLAIRES
         */
        $anneesQuery = DB::table('annees_scolaires')
            ->select(
                'id_annee_scolaire',
                'libelle'
            )
            ->orderByDesc('libelle');

        if ($idEtablissement !== null) {
            $anneesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $anneesScolaires = $anneesQuery->get();


        return view(
            'affectations-enseignants.create',
            compact(
                'enseignants',
                'classes',
                'matieres',
                'anneesScolaires'
            )
        );
    }


    /**
     * ================================================================
     * STORE
     * ================================================================
     *
     * Enregistre une nouvelle affectation.
     */
    public function store(Request $request)
    {
        abort_unless(
            auth()->user()->aLaPermission('gerer_enseignants'),
            403
        );

        $idEtablissement = $this->etablissementCourant();

        $validated = $request->validate([
            'id_enseignant' => [
                'required',
                'integer',
            ],

            'id_classe' => [
                'required',
                'integer',
            ],

            'id_matiere' => [
                'required',
                'integer',
            ],

            'id_annee_scolaire' => [
                'required',
                'integer',
            ],

            'est_titulaire' => [
                'nullable',
                'boolean',
            ],
        ], [
            'id_enseignant.required' => 'Veuillez sélectionner un enseignant.',
            'id_classe.required' => 'Veuillez sélectionner une classe.',
            'id_matiere.required' => 'Veuillez sélectionner une matière.',
            'id_annee_scolaire.required' => 'Veuillez sélectionner une année scolaire.',
        ]);


        /*
         * ============================================================
         * VÉRIFICATION DE L'ENSEIGNANT
         * ============================================================
         */

        $enseignantQuery = DB::table('personnel')
            ->where('id_personnel', $validated['id_enseignant'])
            ->where('fonction', 'ENSEIGNANT')
            ->where('statut', 'ACTIF');

        if ($idEtablissement !== null) {
            $enseignantQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $enseignant = $enseignantQuery->first();

        abort_unless($enseignant, 403);


        /*
         * ============================================================
         * VÉRIFICATION DE LA CLASSE
         * ============================================================
         */

        $classeQuery = DB::table('classes')
            ->where(
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

        abort_unless($classe, 403);


        /*
         * ============================================================
         * VÉRIFICATION DE LA MATIÈRE
         * ============================================================
         */

        $matiereQuery = DB::table('matieres')
            ->where(
                'id_matiere',
                $validated['id_matiere']
            );

        if ($idEtablissement !== null) {
            $matiereQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $matiere = $matiereQuery->first();

        abort_unless($matiere, 403);


        /*
         * ============================================================
         * VÉRIFICATION DE L'ANNÉE SCOLAIRE
         * ============================================================
         */

        $anneeQuery = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $validated['id_annee_scolaire']
            );

        if ($idEtablissement !== null) {
            $anneeQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $annee = $anneeQuery->first();

        abort_unless($annee, 403);


        /*
         * ============================================================
         * VÉRIFICATION DU DOUBLON
         * ============================================================
         *
         * Un enseignant ne peut pas être affecté deux fois
         * à la même classe, matière et année scolaire.
         */

        $doublonQuery = DB::table('affectations_enseignants')
            ->where(
                'id_enseignant',
                $validated['id_enseignant']
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
                'id_annee_scolaire',
                $validated['id_annee_scolaire']
            );

        if ($idEtablissement !== null) {
            $doublonQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        if ($doublonQuery->exists()) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cette affectation existe déjà.'
                );
        }


        /*
         * ============================================================
         * CRÉATION
         * ============================================================
         */

        $affectation = new AffectationEnseignant();

        if ($idEtablissement !== null) {
            $affectation->id_etablissement = $idEtablissement;
        } else {

            /*
             * Pour le Super Administrateur, on récupère
             * l'établissement de l'enseignant.
             */
            $affectation->id_etablissement =
                $enseignant->id_etablissement;
        }

        $affectation->id_enseignant =
            $validated['id_enseignant'];

        $affectation->id_classe =
            $validated['id_classe'];

        $affectation->id_matiere =
            $validated['id_matiere'];

        $affectation->id_annee_scolaire =
            $validated['id_annee_scolaire'];

        $affectation->est_titulaire =
            $request->boolean('est_titulaire');

        $affectation->save();


        return redirect()
            ->route('affectations-enseignants.index')
            ->with(
                'success',
                'L’affectation de l’enseignant a été enregistrée avec succès.'
            );
    }


    /**
     * ================================================================
     * SHOW
     * ================================================================
     *
     * Affiche une affectation.
     */
    public function show($id)
    {
        abort_unless(
            auth()->user()->aLaPermission('gerer_enseignants'),
            403
        );

        $idEtablissement = $this->etablissementCourant();

        $query = DB::table('affectations_enseignants')
            ->join(
                'personnel',
                'affectations_enseignants.id_enseignant',
                '=',
                'personnel.id_personnel'
            )
            ->join(
                'classes',
                'affectations_enseignants.id_classe',
                '=',
                'classes.id_classe'
            )
            ->join(
                'matieres',
                'affectations_enseignants.id_matiere',
                '=',
                'matieres.id_matiere'
            )
            ->join(
                'annees_scolaires',
                'affectations_enseignants.id_annee_scolaire',
                '=',
                'annees_scolaires.id_annee_scolaire'
            )
            ->where(
                'affectations_enseignants.id_affectation',
                $id
            )
            ->select(
                'affectations_enseignants.*',

                'personnel.nom as enseignant_nom',
                'personnel.postnom as enseignant_postnom',
                'personnel.prenom as enseignant_prenom',
                'personnel.matricule',

                'classes.libelle as classe_libelle',

                'matieres.libelle as matiere_libelle',

                'annees_scolaires.libelle as annee_scolaire_libelle'
            );

        if ($idEtablissement !== null) {
            $query->where(
                'affectations_enseignants.id_etablissement',
                $idEtablissement
            );
        }

        $affectation = $query->first();

        abort_unless($affectation, 404);

        return view(
            'affectations-enseignants.show',
            compact('affectation')
        );
    }


    /**
     * ================================================================
     * EDIT
     * ================================================================
     *
     * Affiche le formulaire de modification.
     */
    public function edit($id)
    {
        abort_unless(
            auth()->user()->aLaPermission('gerer_enseignants'),
            403
        );

        $idEtablissement = $this->etablissementCourant();


        /*
         * ============================================================
         * AFFECTATION
         * ============================================================
         */

        $affectationQuery = DB::table('affectations_enseignants')
            ->where(
                'id_affectation',
                $id
            );

        if ($idEtablissement !== null) {
            $affectationQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $affectation = $affectationQuery->first();

        abort_unless($affectation, 404);


        /*
         * ============================================================
         * ENSEIGNANTS
         * ============================================================
         */

        $enseignantsQuery = DB::table('personnel')
            ->where('fonction', 'ENSEIGNANT')
            ->where('statut', 'ACTIF')
            ->select(
                'id_personnel',
                'matricule',
                'nom',
                'postnom',
                'prenom'
            )
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom');

        if ($idEtablissement !== null) {
            $enseignantsQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $enseignants = $enseignantsQuery->get();


        /*
         * ============================================================
         * CLASSES
         * ============================================================
         */

        $classesQuery = DB::table('classes')
            ->select(
                'id_classe',
                'libelle'
            )
            ->orderBy('libelle');

        if ($idEtablissement !== null) {
            $classesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $classes = $classesQuery->get();


        /*
         * ============================================================
         * MATIÈRES
         * ============================================================
         */

        $matieresQuery = DB::table('matieres')
            ->select(
                'id_matiere',
                'libelle'
            )
            ->orderBy('libelle');

        if ($idEtablissement !== null) {
            $matieresQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $matieres = $matieresQuery->get();


        /*
         * ============================================================
         * ANNÉES SCOLAIRES
         * ============================================================
         */

        $anneesQuery = DB::table('annees_scolaires')
            ->select(
                'id_annee_scolaire',
                'libelle'
            )
            ->orderByDesc('libelle');

        if ($idEtablissement !== null) {
            $anneesQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $anneesScolaires = $anneesQuery->get();


        return view(
            'affectations-enseignants.edit',
            compact(
                'affectation',
                'enseignants',
                'classes',
                'matieres',
                'anneesScolaires'
            )
        );
    }


    /**
     * ================================================================
     * UPDATE
     * ================================================================
     *
     * Met à jour une affectation.
     */
    public function update(Request $request, $id)
    {
        abort_unless(
            auth()->user()->aLaPermission('gerer_enseignants'),
            403
        );

        $idEtablissement = $this->etablissementCourant();

        $validated = $request->validate([
            'id_enseignant' => [
                'required',
                'integer',
            ],

            'id_classe' => [
                'required',
                'integer',
            ],

            'id_matiere' => [
                'required',
                'integer',
            ],

            'id_annee_scolaire' => [
                'required',
                'integer',
            ],

            'est_titulaire' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
         * ============================================================
         * RÉCUPÉRATION DE L'AFFECTATION
         * ============================================================
         */

        $affectationQuery = DB::table('affectations_enseignants')
            ->where(
                'id_affectation',
                $id
            );

        if ($idEtablissement !== null) {
            $affectationQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $affectation = $affectationQuery->first();

        abort_unless($affectation, 404);


        /*
         * ============================================================
         * VÉRIFICATION DE L'ENSEIGNANT
         * ============================================================
         */

        $enseignantQuery = DB::table('personnel')
            ->where(
                'id_personnel',
                $validated['id_enseignant']
            )
            ->where('fonction', 'ENSEIGNANT')
            ->where('statut', 'ACTIF');

        if ($idEtablissement !== null) {
            $enseignantQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $enseignant = $enseignantQuery->first();

        abort_unless($enseignant, 403);


        /*
         * ============================================================
         * VÉRIFICATION DE LA CLASSE
         * ============================================================
         */

        $classeQuery = DB::table('classes')
            ->where(
                'id_classe',
                $validated['id_classe']
            );

        if ($idEtablissement !== null) {
            $classeQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        abort_unless(
            $classeQuery->exists(),
            403
        );


        /*
         * ============================================================
         * VÉRIFICATION DE LA MATIÈRE
         * ============================================================
         */

        $matiereQuery = DB::table('matieres')
            ->where(
                'id_matiere',
                $validated['id_matiere']
            );

        if ($idEtablissement !== null) {
            $matiereQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        abort_unless(
            $matiereQuery->exists(),
            403
        );


        /*
         * ============================================================
         * VÉRIFICATION DE L'ANNÉE SCOLAIRE
         * ============================================================
         */

        $anneeQuery = DB::table('annees_scolaires')
            ->where(
                'id_annee_scolaire',
                $validated['id_annee_scolaire']
            );

        if ($idEtablissement !== null) {
            $anneeQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        abort_unless(
            $anneeQuery->exists(),
            403
        );


        /*
         * ============================================================
         * DOUBLON
         * ============================================================
         */

        $doublonQuery = DB::table('affectations_enseignants')
            ->where(
                'id_enseignant',
                $validated['id_enseignant']
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
                'id_annee_scolaire',
                $validated['id_annee_scolaire']
            )
            ->where(
                'id_affectation',
                '!=',
                $id
            );

        if ($idEtablissement !== null) {
            $doublonQuery->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        if ($doublonQuery->exists()) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Cette affectation existe déjà.'
                );
        }


        /*
         * ============================================================
         * MISE À JOUR
         * ============================================================
         */

        DB::table('affectations_enseignants')
            ->where(
                'id_affectation',
                $id
            )
            ->update([
                'id_enseignant' =>
                    $validated['id_enseignant'],

                'id_classe' =>
                    $validated['id_classe'],

                'id_matiere' =>
                    $validated['id_matiere'],

                'id_annee_scolaire' =>
                    $validated['id_annee_scolaire'],

                'est_titulaire' =>
                    $request->boolean('est_titulaire'),

                'date_modification' =>
                    now(),
            ]);


        return redirect()
            ->route('affectations-enseignants.index')
            ->with(
                'success',
                'L’affectation a été modifiée avec succès.'
            );
    }


    /**
     * ================================================================
     * DESTROY
     * ================================================================
     *
     * Supprime une affectation.
     */
    public function destroy($id)
    {
        abort_unless(
            auth()->user()->aLaPermission('gerer_enseignants'),
            403
        );

        $idEtablissement = $this->etablissementCourant();

        $query = DB::table('affectations_enseignants')
            ->where(
                'id_affectation',
                $id
            );

        if ($idEtablissement !== null) {
            $query->where(
                'id_etablissement',
                $idEtablissement
            );
        }

        $affectation = $query->first();

        abort_unless($affectation, 404);


        DB::table('affectations_enseignants')
            ->where(
                'id_affectation',
                $id
            )
            ->delete();


        return redirect()
            ->route('affectations-enseignants.index')
            ->with(
                'success',
                'L’affectation a été supprimée avec succès.'
            );
    }
}
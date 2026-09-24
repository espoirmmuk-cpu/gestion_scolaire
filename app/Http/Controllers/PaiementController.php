<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\JournalActivite;
use App\Models\FraisEleve;
use App\Models\DetailPaiement;
use App\Models\Recette;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{

    public function index(Request $request)
    {
        $idEtablissement = auth()->user()->id_etablissement;

        $query = Paiement::with([
            'eleve',
            'details.fraisEleve.inscription.classe',
        ])
            ->whereHas('eleve', function ($q) use ($idEtablissement) {
                $q->where('id_etablissement', $idEtablissement);
            });

        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('numero_recu', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhereHas('eleve', function ($eleve) use ($search) {
                        $eleve->where('matricule', 'like', "%{$search}%")
                            ->orWhere('nom', 'like', "%{$search}%")
                            ->orWhere('postnom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre classe
        |--------------------------------------------------------------------------
        |
        | Paiement
        |    └── details
        |          └── fraisEleve
        |                └── inscription
        |                      └── classe
        |
        */
        if ($request->filled('id_classe')) {
            $query->whereHas(
                'details.fraisEleve.inscription',
                function ($q) use ($request) {
                    $q->where('id_classe', $request->id_classe);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Situation financière
        |--------------------------------------------------------------------------
        |
        | EN_ORDRE      = aucun frais avec un solde > 0
        | RESTE_A_PAYER = au moins un frais avec un solde > 0
        |
        | On interroge directement frais_eleves car Eleve ne possède
        | pas de relation fraisEleves().
        |
        */
        if ($request->filled('situation')) {

            if ($request->situation === 'EN_ORDRE') {

                $query->whereHas('eleve', function ($q) {

                    $q->whereNotExists(function ($subQuery) {
                        $subQuery->select(DB::raw(1))
                            ->from('frais_eleves')
                            ->whereColumn(
                                'frais_eleves.id_eleve',
                                'eleves.id_eleve'
                            )
                            ->where('frais_eleves.solde', '>', 0);
                    });

                });

            } elseif ($request->situation === 'RESTE_A_PAYER') {

                $query->whereHas('eleve', function ($q) {

                    $q->whereExists(function ($subQuery) {
                        $subQuery->select(DB::raw(1))
                            ->from('frais_eleves')
                            ->whereColumn(
                                'frais_eleves.id_eleve',
                                'eleves.id_eleve'
                            )
                            ->where('frais_eleves.solde', '>', 0);
                    });

                });
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre devise
        |--------------------------------------------------------------------------
        */
        if ($request->filled('devise')) {
            $query->where('devise', $request->devise);
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre mode de paiement
        |--------------------------------------------------------------------------
        */
        if ($request->filled('mode_paiement')) {
            $query->where('mode_paiement', $request->mode_paiement);
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre date début
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_debut')) {
            $query->whereDate(
                'date_paiement',
                '>=',
                $request->date_debut
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtre date fin
        |--------------------------------------------------------------------------
        */
        if ($request->filled('date_fin')) {
            $query->whereDate(
                'date_paiement',
                '<=',
                $request->date_fin
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Liste des paiements
        |--------------------------------------------------------------------------
        */
        $paiements = $query
            ->orderByDesc('date_paiement')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Liste des classes
        |--------------------------------------------------------------------------
        */
        $classes = \App\Models\Classe::where(
            'id_etablissement',
            $idEtablissement
        )
            ->orderBy('libelle')
            ->get();

        return view(
            'paiements.index',
            compact('paiements', 'classes')
        );
    }


    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        $eleves = Eleve::where(
            'statut',
            'ACTIF'
        )
            ->where(
                'id_etablissement',
                auth()->user()->id_etablissement
            )
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();

        return view(
            'paiements.create',
            compact('eleves')
        );
    }


    /**
     * Récupérer les frais scolaires d'un élève.
     */
    public function fraisEleve($id_eleve)
    {
        $frais = FraisEleve::with([
            'tarif.categorieFrais',
            'inscription.classe',
            'inscription.anneeScolaire',
        ])
            ->where(
                'id_eleve',
                $id_eleve
            )
            ->whereIn(
                'statut',
                [
                    'NON_PAYE',
                    'PARTIEL'
                ]
            )
            ->where(
                'solde',
                '>',
                0
            )
            ->orderBy(
                'id_frais_eleve'
            )
            ->get();

        return response()->json($frais);
    }


    /**
     * Enregistrer un paiement.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_eleve' => [
                'required',
                'exists:eleves,id_eleve'
            ],

            'numero_recu' => [
                'required',
                'string',
                'max:50',
                'unique:paiements,numero_recu'
            ],

            'date_paiement' => [
                'required',
                'date'
            ],

            'montant_total' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'devise' => [
                'required',
                'in:USD,CDF'
            ],

            'mode_paiement' => [
                'required',
                'in:ESPECES,BANQUE,MOBILE_MONEY,CHEQUE,AUTRE'
            ],

            'reference' => [
                'nullable',
                'string',
                'max:100'
            ],

            'observation' => [
                'nullable',
                'string'
            ],

            'frais' => [
                'required',
                'array',
                'min:1'
            ],

            'frais.*' => [
                'required',
                'numeric',
                'min:0.01'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'élève
        |--------------------------------------------------------------------------
        */

        $eleve = Eleve::where(
            'id_eleve',
            $validated['id_eleve']
        )
            ->where(
                'id_etablissement',
                auth()->user()->id_etablissement
            )
            ->first();

        if (!$eleve) {

            abort(
                403,
                'Cet élève n’appartient pas à votre établissement.'
            );
        }


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Récupérer et verrouiller les frais sélectionnés
            |--------------------------------------------------------------------------
            */

            $fraisSelectionnes = FraisEleve::with([
                'tarif',
            ])
                ->where(
                    'id_eleve',
                    $validated['id_eleve']
                )
                ->whereIn(
                    'id_frais_eleve',
                    array_keys($validated['frais'])
                )
                ->whereIn(
                    'statut',
                    [
                        'NON_PAYE',
                        'PARTIEL'
                    ]
                )
                ->where(
                    'solde',
                    '>',
                    0
                )
                ->lockForUpdate()
                ->get();


            /*
            |--------------------------------------------------------------------------
            | Vérifier les frais
            |--------------------------------------------------------------------------
            */

            if (
                $fraisSelectionnes->count()
                !== count($validated['frais'])
            ) {

                throw new \Exception(
                    'Un ou plusieurs frais sélectionnés sont invalides ou déjà soldés.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Calcul des détails
            |--------------------------------------------------------------------------
            */

            $totalDetails = 0;

            foreach (
                $fraisSelectionnes
                as $frais
            ) {

                $montant = round(
                    (float) $validated['frais'][
                        $frais->id_frais_eleve
                    ],
                    2
                );


                if ($montant <= 0) {

                    throw new \Exception(
                        'Le montant affecté à un frais doit être supérieur à zéro.'
                    );
                }


                if (
                    $montant
                    > (float) $frais->solde
                ) {

                    throw new \Exception(
                        'Le montant payé pour le frais #'
                        . $frais->id_frais_eleve
                        . ' ne peut pas dépasser son solde de '
                        . $frais->solde
                        . '.'
                    );
                }


                $totalDetails += $montant;
            }


            $totalDetails = round(
                $totalDetails,
                2
            );


            $montantTotal = round(
                (float) $validated['montant_total'],
                2
            );


            /*
            |--------------------------------------------------------------------------
            | Vérifier total
            |--------------------------------------------------------------------------
            */

            if (
                $totalDetails
                !== $montantTotal
            ) {

                throw new \Exception(
                    'Le montant total du paiement doit être égal à la somme des montants affectés aux frais.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Vérifier devise
            |--------------------------------------------------------------------------
            */

            foreach (
                $fraisSelectionnes
                as $frais
            ) {

                if (
                    $frais->tarif
                    &&
                    $frais->tarif->devise
                    !== $validated['devise']
                ) {

                    throw new \Exception(
                        'La devise du paiement ne correspond pas à la devise du tarif.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Créer le paiement
            |--------------------------------------------------------------------------
            */

            $paiement = Paiement::create([

                'id_eleve' =>
                    $validated['id_eleve'],

                'numero_recu' =>
                    $validated['numero_recu'],

                'date_paiement' =>
                    $validated['date_paiement'],

                'montant_total' =>
                    $montantTotal,

                'devise' =>
                    $validated['devise'],

                'mode_paiement' =>
                    $validated['mode_paiement'],

                'reference' =>
                    $validated['reference'] ?? null,

                'id_utilisateur' =>
                    Auth::id(),

                'observation' =>
                    $validated['observation'] ?? null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Détails + mise à jour des frais
            |--------------------------------------------------------------------------
            */

            foreach (
                $fraisSelectionnes
                as $frais
            ) {

                $montant = round(
                    (float) $validated['frais'][
                        $frais->id_frais_eleve
                    ],
                    2
                );


                DetailPaiement::create([

                    'id_paiement' =>
                        $paiement->id_paiement,

                    'id_frais_eleve' =>
                        $frais->id_frais_eleve,

                    'montant' =>
                        $montant,

                ]);


                $nouveauMontantPaye = round(
                    (float) $frais->montant_paye
                    + $montant,
                    2
                );


                $nouveauSolde = round(
                    (float) $frais->montant_a_payer
                    - $nouveauMontantPaye,
                    2
                );


                if ($nouveauSolde < 0.01) {

                    $nouveauSolde = 0;
                }


                if ($nouveauSolde <= 0) {

                    $statut = 'SOLDE';

                } elseif ($nouveauMontantPaye > 0) {

                    $statut = 'PARTIEL';

                } else {

                    $statut = 'NON_PAYE';
                }


                $frais->update([

                    'montant_paye' =>
                        $nouveauMontantPaye,

                    'solde' =>
                        $nouveauSolde,

                    'statut' =>
                        $statut,

                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Créer automatiquement la recette
            |--------------------------------------------------------------------------
            */

            $fraisPrincipal =
                $fraisSelectionnes->first();

            $idAnneeScolaire = null;

            if (
                $fraisPrincipal
                &&
                $fraisPrincipal->tarif
            ) {

                $idAnneeScolaire =
                    $fraisPrincipal
                        ->tarif
                        ->id_annee_scolaire;
            }


            Recette::create([

                'id_paiement' =>
                    $paiement->id_paiement,

                'id_etablissement' =>
                    $eleve->id_etablissement,

                'id_annee_scolaire' =>
                    $idAnneeScolaire,

                'date_recette' =>
                    $validated['date_paiement'],

                'source' =>
                    'Paiement élève - Reçu '
                    . $paiement->numero_recu,

                'montant' =>
                    $montantTotal,

                'devise' =>
                    $validated['devise'],

                'description' =>
                    'Paiement de l’élève '
                    . trim(
                        $eleve->nom . ' '
                        . $eleve->postnom . ' '
                        . $eleve->prenom
                    ),

                'id_utilisateur' =>
                    Auth::id(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Journalisation
            |--------------------------------------------------------------------------
            */

            JournalActivite::create([

                'id_utilisateur' =>
                    Auth::id(),

                'action' =>
                    'Ajout d’un paiement',

                'table_concernee' =>
                    'paiements',

                'id_enregistrement' =>
                    $paiement->id_paiement,

                'anciennes_valeurs' =>
                    null,

                'nouvelles_valeurs' =>
                    json_encode(
                        $paiement
                            ->fresh()
                            ->load('details')
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


            /*
            |--------------------------------------------------------------------------
            | Valider transaction
            |--------------------------------------------------------------------------
            */

            DB::commit();


            return redirect()
                ->route(
                    'paiements.show',
                    $paiement
                )
                ->with(
                    'success',
                    'Paiement enregistré avec succès.'
                );


        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors([
                    'paiement' =>
                        $e->getMessage(),
                ]);
        }
    }


    /**
     * Afficher un paiement.
     */
    public function show(Paiement $paiement)
    {
        $paiement->load([
            'eleve',
            'utilisateur.etablissement',
            'details.fraisEleve.tarif.classe',
            'details.fraisEleve.tarif.anneeScolaire',
            'details.fraisEleve.tarif.categorieFrais',
        ]);

        return view(
            'paiements.show',
            compact('paiement')
        );
    }


    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Paiement $paiement)
    {
        $eleves = Eleve::where(
            'id_etablissement',
            auth()->user()->id_etablissement
        )
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();

        return view(
            'paiements.edit',
            compact(
                'paiement',
                'eleves'
            )
        );
    }


    /**
     * Modifier un paiement.
     */
    public function update(
        Request $request,
        Paiement $paiement
    ) {

        $validated = $request->validate([

            'id_eleve' => [
                'required',
                'exists:eleves,id_eleve'
            ],

            'numero_recu' => [
                'required',
                'string',
                'max:50',
                'unique:paiements,numero_recu,'
                . $paiement->id_paiement
                . ',id_paiement'
            ],

            'date_paiement' => [
                'required',
                'date'
            ],

            'montant_total' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'devise' => [
                'required',
                'in:USD,CDF'
            ],

            'mode_paiement' => [
                'required',
                'in:ESPECES,BANQUE,MOBILE_MONEY,CHEQUE,AUTRE'
            ],

            'reference' => [
                'nullable',
                'string',
                'max:100'
            ],

            'observation' => [
                'nullable',
                'string'
            ],
        ]);


        $anciennesValeurs =
            $paiement->toArray();


        $paiement->update(
            $validated
        );


        JournalActivite::create([

            'id_utilisateur' =>
                Auth::id(),

            'action' =>
                'Modification d’un paiement',

            'table_concernee' =>
                'paiements',

            'id_enregistrement' =>
                $paiement->id_paiement,

            'anciennes_valeurs' =>
                json_encode(
                    $anciennesValeurs,
                    JSON_UNESCAPED_UNICODE
                ),

            'nouvelles_valeurs' =>
                json_encode(
                    $paiement
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


        return redirect()
            ->route('paiements.index')
            ->with(
                'success',
                'Paiement modifié avec succès.'
            );
    }


    /**
     * Supprimer un paiement.
     */
    public function destroy(
        Request $request,
        Paiement $paiement
    ) {

        $anciennesValeurs =
            $paiement->toArray();

        $idPaiement =
            $paiement->id_paiement;


        $paiement->delete();


        JournalActivite::create([

            'id_utilisateur' =>
                Auth::id(),

            'action' =>
                'Suppression d’un paiement',

            'table_concernee' =>
                'paiements',

            'id_enregistrement' =>
                $idPaiement,

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
            ->route('paiements.index')
            ->with(
                'success',
                'Paiement supprimé avec succès.'
            );
    }
}
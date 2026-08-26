<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PresenceController extends Controller
{
    use AuthorizesRequests;

    /*
    |--------------------------------------------------------------------------
    | Vérifier si l'utilisateur est super administrateur
    |--------------------------------------------------------------------------
    */

    private function estSuperAdministrateur($user): bool
    {
        return (
            $user->id_etablissement === null &&
            $user->aLeRole('Administrateur')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Vérifier qu'une classe appartient à l'établissement
    |--------------------------------------------------------------------------
    */

    private function verifierClasse(Classe $classe, $user): void
    {
        if ($this->estSuperAdministrateur($user)) {
            return;
        }

        abort_unless(
            (int) $classe->id_etablissement ===
            (int) $user->id_etablissement,
            403,
            'Cette classe n’appartient pas à votre établissement.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Vérifier qu'un élève appartient à l'établissement
    |--------------------------------------------------------------------------
    */

    private function verifierEleve(Eleve $eleve, $user): void
    {
        if ($this->estSuperAdministrateur($user)) {
            return;
        }

        abort_unless(
            (int) $eleve->id_etablissement ===
            (int) $user->id_etablissement,
            403,
            'Cet élève n’appartient pas à votre établissement.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Liste des présences avec filtres.
    |
    */

    public function index(Request $request)
    {
        $this->authorize('viewAny', Presence::class);

        $user = auth()->user();

        $query = Presence::with([
            'eleve',
            'classe',
        ])
        ->orderByDesc('date_presence');


        /*
        |--------------------------------------------------------------------------
        | Sécurité établissement
        |--------------------------------------------------------------------------
        */

        if (!$this->estSuperAdministrateur($user)) {

            $query->whereHas('eleve', function ($q) use ($user) {

                $q->where(
                    'id_etablissement',
                    $user->id_etablissement
                );

            });

            $query->whereHas('classe', function ($q) use ($user) {

                $q->where(
                    'id_etablissement',
                    $user->id_etablissement
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre élève
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_eleve')) {

            $query->where(
                'id_eleve',
                $request->id_eleve
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre classe
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_classe')) {

            $query->where(
                'id_classe',
                $request->id_classe
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_presence')) {

            $query->whereDate(
                'date_presence',
                $request->date_presence
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre statut
        |--------------------------------------------------------------------------
        */

        if ($request->filled('statut')) {

            $query->where(
                'statut',
                $request->statut
            );
        }


        $presences = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Élèves disponibles
        |--------------------------------------------------------------------------
        */

        $elevesQuery = Eleve::orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom');

        if (!$this->estSuperAdministrateur($user)) {

            $elevesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $eleves = $elevesQuery->get();


        /*
        |--------------------------------------------------------------------------
        | Classes disponibles
        |--------------------------------------------------------------------------
        */

        $classesQuery = Classe::orderBy('libelle');

        if (!$this->estSuperAdministrateur($user)) {

            $classesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $classes = $classesQuery->get();


        return view(
            'presences.index',
            compact(
                'presences',
                'eleves',
                'classes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Première étape :
    |
    | Classe + date
    |
    | Le formulaire utilise GET pour charger les élèves.
    |
    */

    public function create(Request $request)
    {
        $this->authorize('create', Presence::class);

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Classes disponibles
        |--------------------------------------------------------------------------
        */

        $classesQuery = Classe::orderBy('libelle');

        if (!$this->estSuperAdministrateur($user)) {

            $classesQuery->where(
                'id_etablissement',
                $user->id_etablissement
            );
        }

        $classes = $classesQuery->get();


        /*
        |--------------------------------------------------------------------------
        | Aucun élève au départ
        |--------------------------------------------------------------------------
        */

        $eleves = collect();


        /*
        |--------------------------------------------------------------------------
        | Si une classe est sélectionnée
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_classe')) {

            $classe = Classe::findOrFail(
                $request->id_classe
            );


            /*
            |--------------------------------------------------------------------------
            | Vérification établissement
            |--------------------------------------------------------------------------
            */

            $this->verifierClasse(
                $classe,
                $user
            );


            /*
            |--------------------------------------------------------------------------
            | Récupération des élèves inscrits dans cette classe
            |--------------------------------------------------------------------------
            |
            | Nous n'utilisons pas ici une relation Eloquent obligatoire.
            | On passe directement par la table inscriptions.
            |
            */

            $eleves = Eleve::where(
                'id_etablissement',
                $classe->id_etablissement
            )
            ->whereExists(function ($query) use ($classe) {

                $query->select(DB::raw(1))
                    ->from('inscriptions')
                    ->whereColumn(
                        'inscriptions.id_eleve',
                        'eleves.id_eleve'
                    )
                    ->where(
                        'inscriptions.id_classe',
                        $classe->id_classe
                    );
            })
            ->orderBy('nom')
            ->orderBy('postnom')
            ->orderBy('prenom')
            ->get();
        }


        return view(
            'presences.create',
            compact(
                'classes',
                'eleves'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Enregistrement collectif de toute une classe.
    |
    */

    public function store(Request $request)
    {
        $this->authorize('create', Presence::class);


        /*
        |--------------------------------------------------------------------------
        | Validation générale
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'id_classe' => [
                'required',
                'integer',
                'exists:classes,id_classe',
            ],

            'date_presence' => [
                'required',
                'date',
            ],

            'presences' => [
                'required',
                'array',
                'min:1',
            ],

            'presences.*.statut' => [
                'required',
                'string',
                'max:50',
            ],

            'presences.*.motif' => [
                'nullable',
                'string',
                'max:255',
            ],

            'presences.*.observation' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Classe
        |--------------------------------------------------------------------------
        */

        $classe = Classe::findOrFail(
            $validated['id_classe']
        );

        $this->verifierClasse(
            $classe,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Enregistrement dans une transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $classe,
            $user,
            $request
        ) {

            foreach (
                $validated['presences']
                as $idEleve => $donnees
            ) {

                /*
                |--------------------------------------------------------------------------
                | Élève
                |--------------------------------------------------------------------------
                */

                $eleve = Eleve::findOrFail(
                    $idEleve
                );

                $this->verifierEleve(
                    $eleve,
                    $user
                );


                /*
                |--------------------------------------------------------------------------
                | Vérifier que l'élève est inscrit dans la classe
                |--------------------------------------------------------------------------
                */

                $estInscrit = DB::table('inscriptions')
                    ->where(
                        'id_eleve',
                        $eleve->id_eleve
                    )
                    ->where(
                        'id_classe',
                        $classe->id_classe
                    )
                    ->exists();

                abort_unless(
                    $estInscrit,
                    403,
                    'Cet élève n’est pas inscrit dans cette classe.'
                );


                /*
                |--------------------------------------------------------------------------
                | Vérifier si une présence existe déjà
                |--------------------------------------------------------------------------
                */

                $presenceExistante = Presence::where(
                    'id_eleve',
                    $eleve->id_eleve
                )
                ->where(
                    'id_classe',
                    $classe->id_classe
                )
                ->whereDate(
                    'date_presence',
                    $validated['date_presence']
                )
                ->first();


                /*
                |--------------------------------------------------------------------------
                | Si déjà enregistrée
                |--------------------------------------------------------------------------
                */

                if ($presenceExistante) {

                    $anciennesValeurs =
                        $presenceExistante->getAttributes();


                    $presenceExistante->update([

                        'statut' =>
                            $donnees['statut'],

                        'motif' =>
                            $donnees['motif'] ?? null,

                        'observation' =>
                            $donnees['observation'] ?? null,

                    ]);


                    JournalActivite::create([

                        'id_utilisateur' =>
                            auth()->id(),

                        'action' =>
                            'Modification d’une présence',

                        'table_concernee' =>
                            'presences',

                        'id_enregistrement' =>
                            $presenceExistante->id_presence,

                        'anciennes_valeurs' =>
                            json_encode(
                                $anciennesValeurs,
                                JSON_UNESCAPED_UNICODE
                            ),

                        'nouvelles_valeurs' =>
                            json_encode(
                                $presenceExistante->getAttributes(),
                                JSON_UNESCAPED_UNICODE
                            ),

                        'adresse_ip' =>
                            $request->ip(),

                        'navigateur' =>
                            $request->userAgent(),

                        'date_heure' =>
                            now(),

                    ]);

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Nouvelle présence
                |--------------------------------------------------------------------------
                */

                $presence = Presence::create([

                    'id_eleve' =>
                        $eleve->id_eleve,

                    'id_classe' =>
                        $classe->id_classe,

                    'date_presence' =>
                        $validated['date_presence'],

                    'statut' =>
                        $donnees['statut'],

                    'motif' =>
                        $donnees['motif'] ?? null,

                    'observation' =>
                        $donnees['observation'] ?? null,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Journalisation
                |--------------------------------------------------------------------------
                */

                JournalActivite::create([

                    'id_utilisateur' =>
                        auth()->id(),

                    'action' =>
                        'Ajout d’une présence',

                    'table_concernee' =>
                        'presences',

                    'id_enregistrement' =>
                        $presence->id_presence,

                    'anciennes_valeurs' =>
                        null,

                    'nouvelles_valeurs' =>
                        json_encode(
                            $presence->getAttributes(),
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
        });


        return redirect()
            ->route('presences.index')
            ->with(
                'success',
                'Les présences de la classe ont été enregistrées avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Presence $presence)
    {
        $this->authorize(
            'view',
            $presence
        );


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Vérification établissement
        |--------------------------------------------------------------------------
        */

        $this->verifierClasse(
            $presence->classe,
            $user
        );

        $this->verifierEleve(
            $presence->eleve,
            $user
        );


        $presence->load([
            'eleve',
            'classe',
        ]);


        return view(
            'presences.show',
            compact('presence')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | Modification collective.
    |
    | On prend une présence existante comme référence pour retrouver :
    |
    | - la classe
    | - la date
    |
    | Puis toutes les présences de cette classe/date sont affichées.
    |
    */

    public function edit(Presence $presence)
    {
        $this->authorize(
            'update',
            $presence
        );


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Classe
        |--------------------------------------------------------------------------
        */

        $classe = Classe::findOrFail(
            $presence->id_classe
        );


        $this->verifierClasse(
            $classe,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Élèves de la classe
        |--------------------------------------------------------------------------
        */

        $eleves = Eleve::where(
            'id_etablissement',
            $classe->id_etablissement
        )
        ->whereExists(function ($query) use ($classe) {

            $query->select(DB::raw(1))
                ->from('inscriptions')
                ->whereColumn(
                    'inscriptions.id_eleve',
                    'eleves.id_eleve'
                )
                ->where(
                    'inscriptions.id_classe',
                    $classe->id_classe
                );

        })
        ->orderBy('nom')
        ->orderBy('postnom')
        ->orderBy('prenom')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Présences existantes
        |--------------------------------------------------------------------------
        */

        $presences = Presence::where(
            'id_classe',
            $classe->id_classe
        )
        ->whereDate(
            'date_presence',
            $presence->date_presence
        )
        ->get()
        ->keyBy('id_eleve');


        return view(
            'presences.edit',
            compact(
                'presence',
                'classe',
                'eleves',
                'presences'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Modification collective de toutes les présences de la classe.
    |
    */

    public function update(
        Request $request,
        Presence $presence
    ) {

        $this->authorize(
            'update',
            $presence
        );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'presences' => [
                'required',
                'array',
                'min:1',
            ],

            'presences.*.statut' => [
                'required',
                'string',
                'max:50',
            ],

            'presences.*.motif' => [
                'nullable',
                'string',
                'max:255',
            ],

            'presences.*.observation' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Classe de la présence
        |--------------------------------------------------------------------------
        */

        $classe = Classe::findOrFail(
            $presence->id_classe
        );


        $this->verifierClasse(
            $classe,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Mise à jour collective
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $presence,
            $classe,
            $user,
            $request
        ) {

            foreach (
                $validated['presences']
                as $idEleve => $donnees
            ) {

                /*
                |--------------------------------------------------------------------------
                | Élève
                |--------------------------------------------------------------------------
                */

                $eleve = Eleve::findOrFail(
                    $idEleve
                );

                $this->verifierEleve(
                    $eleve,
                    $user
                );


                /*
                |--------------------------------------------------------------------------
                | Vérifier inscription
                |--------------------------------------------------------------------------
                */

                $estInscrit = DB::table('inscriptions')
                    ->where(
                        'id_eleve',
                        $eleve->id_eleve
                    )
                    ->where(
                        'id_classe',
                        $classe->id_classe
                    )
                    ->exists();

                abort_unless(
                    $estInscrit,
                    403,
                    'Cet élève n’est pas inscrit dans cette classe.'
                );


                /*
                |--------------------------------------------------------------------------
                | Chercher la présence
                |--------------------------------------------------------------------------
                */

                $presenceEleve = Presence::where(
                    'id_eleve',
                    $eleve->id_eleve
                )
                ->where(
                    'id_classe',
                    $classe->id_classe
                )
                ->whereDate(
                    'date_presence',
                    $presence->date_presence
                )
                ->first();


                /*
                |--------------------------------------------------------------------------
                | Créer si elle n'existe pas
                |--------------------------------------------------------------------------
                */

                if (!$presenceEleve) {

                    $presenceEleve = Presence::create([

                        'id_eleve' =>
                            $eleve->id_eleve,

                        'id_classe' =>
                            $classe->id_classe,

                        'date_presence' =>
                            $presence->date_presence,

                        'statut' =>
                            $donnees['statut'],

                        'motif' =>
                            $donnees['motif'] ?? null,

                        'observation' =>
                            $donnees['observation'] ?? null,

                    ]);


                    JournalActivite::create([

                        'id_utilisateur' =>
                            auth()->id(),

                        'action' =>
                            'Ajout d’une présence',

                        'table_concernee' =>
                            'presences',

                        'id_enregistrement' =>
                            $presenceEleve->id_presence,

                        'anciennes_valeurs' =>
                            null,

                        'nouvelles_valeurs' =>
                            json_encode(
                                $presenceEleve->getAttributes(),
                                JSON_UNESCAPED_UNICODE
                            ),

                        'adresse_ip' =>
                            $request->ip(),

                        'navigateur' =>
                            $request->userAgent(),

                        'date_heure' =>
                            now(),

                    ]);

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Anciennes valeurs
                |--------------------------------------------------------------------------
                */

                $anciennesValeurs =
                    $presenceEleve->getAttributes();


                /*
                |--------------------------------------------------------------------------
                | Modification
                |--------------------------------------------------------------------------
                */

                $presenceEleve->update([

                    'statut' =>
                        $donnees['statut'],

                    'motif' =>
                        $donnees['motif'] ?? null,

                    'observation' =>
                        $donnees['observation'] ?? null,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Journalisation
                |--------------------------------------------------------------------------
                */

                JournalActivite::create([

                    'id_utilisateur' =>
                        auth()->id(),

                    'action' =>
                        'Modification d’une présence',

                    'table_concernee' =>
                        'presences',

                    'id_enregistrement' =>
                        $presenceEleve->id_presence,

                    'anciennes_valeurs' =>
                        json_encode(
                            $anciennesValeurs,
                            JSON_UNESCAPED_UNICODE
                        ),

                    'nouvelles_valeurs' =>
                        json_encode(
                            $presenceEleve->getAttributes(),
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
        });


        return redirect()
            ->route('presences.index')
            ->with(
                'success',
                'Les présences de la classe ont été modifiées avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Presence $presence)
    {
        $this->authorize(
            'delete',
            $presence
        );


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Vérification établissement
        |--------------------------------------------------------------------------
        */

        $this->verifierClasse(
            $presence->classe,
            $user
        );

        $this->verifierEleve(
            $presence->eleve,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Anciennes valeurs
        |--------------------------------------------------------------------------
        */

        $anciennesValeurs =
            $presence->getAttributes();


        try {

            /*
            |--------------------------------------------------------------------------
            | Suppression
            |--------------------------------------------------------------------------
            */

            $presence->delete();


            /*
            |--------------------------------------------------------------------------
            | Journalisation
            |--------------------------------------------------------------------------
            */

            JournalActivite::create([

                'id_utilisateur' =>
                    auth()->id(),

                'action' =>
                    'Suppression d’une présence',

                'table_concernee' =>
                    'presences',

                'id_enregistrement' =>
                    $presence->id_presence,

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
                ->route('presences.index')
                ->with(
                    'success',
                    'Présence supprimée avec succès.'
                );


        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()
                ->route('presences.index')
                ->with(
                    'error',
                    'Cette présence ne peut pas être supprimée.'
                );
        }
    }
}
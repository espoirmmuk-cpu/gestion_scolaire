<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\JournalActivite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

class PresenceController extends Controller
{
    use AuthorizesRequests;

    /*
    |--------------------------------------------------------------------------
    | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rifier si l'utilisateur est super administrateur
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
    | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rifier qu'une classe appartient ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â  l'ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement
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
            'Cette classe nÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢appartient pas ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â  votre ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rifier qu'un ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve appartient ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â  l'ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement
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
            'Cet ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve nÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢appartient pas ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â  votre ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Liste des prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sences avec filtres.
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
        | SÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©curitÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement
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
        | Filtre ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve
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



        \Log::warning('PRESENCES CACHE DIAGNOSTIC', [
            'cache_default' => config('cache.default'),
            'cache_store' => get_class(Cache::store()->getStore()),
            'php_version' => PHP_VERSION,
            'php_sapi' => PHP_SAPI,
            'eleves_cache_avant' => get_debug_type(Cache::get('presences_eleves_' . ($this->estSuperAdministrateur($user) ? 'all' : $user->id_etablissement))),
            'classes_cache_avant' => get_debug_type(Cache::get('presences_classes_' . ($this->estSuperAdministrateur($user) ? 'all' : $user->id_etablissement))),
        ]);
        /*
        |--------------------------------------------------------------------------
        | ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â°lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ves disponibles
        |--------------------------------------------------------------------------
        */

        $eleves = Cache::remember(
            'presences_eleves_' . ($this->estSuperAdministrateur($user) ? 'all' : $user->id_etablissement),
            now()->addMinutes(10),
            function () use ($user) {
                $query = Eleve::orderBy('nom')
                    ->orderBy('postnom')
                    ->orderBy('prenom');

                if (!$this->estSuperAdministrateur($user)) {
                    $query->where('id_etablissement', $user->id_etablissement);
                }

                return $query->get()->map(fn ($eleve) => [
                    'id_eleve' => $eleve->id_eleve,
                    'nom' => $eleve->nom,
                    'postnom' => $eleve->postnom,
                    'prenom' => $eleve->prenom,
                ])->values()->all();
            }
        );

        $classes = Cache::remember(
            'presences_classes_' . ($this->estSuperAdministrateur($user) ? 'all' : $user->id_etablissement),
            now()->addMinutes(10),
            function () use ($user) {
                $query = Classe::orderBy('libelle');

                if (!$this->estSuperAdministrateur($user)) {
                    $query->where('id_etablissement', $user->id_etablissement);
                }

                return $query->get()->map(fn ($classe) => [
                    'id_classe' => $classe->id_classe,
                    'libelle' => $classe->libelle,
                ])->values()->all();
            }
        );

        \Log::info('PRESENCES DEBUG AVANT VIEW', [
            'user_id' => $user->id_utilisateur ?? null,
            'etablissement' => $user->id_etablissement ?? null,
            'eleves_type' => get_debug_type($eleves),
            'eleves_count' => is_countable($eleves) ? count($eleves) : null,
            'eleves_first_type' => is_iterable($eleves) ? get_debug_type(collect($eleves)->first()) : null,
            'eleves_first' => is_iterable($eleves) ? collect($eleves)->first() : null,
            'classes_type' => get_debug_type($classes),
            'classes_count' => is_countable($classes) ? count($classes) : null,
        ]);
        \Log::warning('PRESENCES INDEX AVANT RETURN VIEW', ['eleves_type' => get_debug_type($eleves), 'eleves_count' => is_countable($eleves) ? count($eleves) : null, 'eleves_first_type' => is_iterable($eleves) ? get_debug_type(collect($eleves)->first()) : null, 'classes_type' => get_debug_type($classes), 'classes_count' => is_countable($classes) ? count($classes) : null]);


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
    | PremiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨re ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tape :
    |
    | Classe + date
    |
    | Le formulaire utilise GET pour charger les ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ves.
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
        | Aucun ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve au dÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©part
        |--------------------------------------------------------------------------
        */

        $eleves = collect();


        /*
        |--------------------------------------------------------------------------
        | Si une classe est sÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lectionnÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©e
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id_classe')) {

            $classe = Classe::findOrFail(
                $request->id_classe
            );


            /*
            |--------------------------------------------------------------------------
            | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rification ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement
            |--------------------------------------------------------------------------
            */

            $this->verifierClasse(
                $classe,
                $user
            );


            /*
            |--------------------------------------------------------------------------
            | RÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©cupÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©ration des ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ves inscrits dans cette classe
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
        | Validation gÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rale
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
                | ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â°lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve
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
                | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rifier que l'ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve est inscrit dans la classe
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
                    'Cet ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve nÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢est pas inscrit dans cette classe.'
                );


                /*
                |--------------------------------------------------------------------------
                | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rifier si une prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence existe dÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©jÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â 
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
                | Si dÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©jÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â  enregistrÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©e
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
                            'Modification dÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢une prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence',

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
                | Nouvelle prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence
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
                        'Ajout dÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢une prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence',

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
                'Les prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sences de la classe ont ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© enregistrÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©es avec succÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨s.'
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
        | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rification ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement
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
    | On prend une prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence existante comme rÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©fÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rence pour retrouver :
    |
    | - la classe
    | - la date
    |
    | Puis toutes les prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sences de cette classe/date sont affichÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©es.
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
        | ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â°lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ves de la classe
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
        | PrÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sences existantes
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
    | Modification collective de toutes les prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sences de la classe.
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
        | Classe de la prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence
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
        | Mise ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â  jour collective
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
                | ÃƒÆ’Ã†â€™ÃƒÂ¢Ã¢â€šÂ¬Ã‚Â°lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve
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
                | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rifier inscription
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
                    'Cet ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©lÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨ve nÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢est pas inscrit dans cette classe.'
                );


                /*
                |--------------------------------------------------------------------------
                | Chercher la prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence
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
                | CrÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©er si elle n'existe pas
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
                            'Ajout dÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢une prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence',

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
                        'Modification dÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢une prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence',

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
                'Les prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sences de la classe ont ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â© modifiÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©es avec succÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨s.'
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
        | VÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©rification ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©tablissement
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
                    'Suppression dÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢une prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence',

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
                    'PrÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence supprimÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©e avec succÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â¨s.'
                );


        } catch (\Illuminate\Database\QueryException $e) {

            return redirect()
                ->route('presences.index')
                ->with(
                    'error',
                    'Cette prÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©sence ne peut pas ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Âªtre supprimÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â©e.'
                );
        }
    }
}
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\EleveController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\CategorieFraisController;
use App\Http\Controllers\TarifScolaireController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\PeriodeScolaireController;
use App\Http\Controllers\JournalActiviteController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\InfrastructureController;
use App\Http\Controllers\RecetteController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AffectationEnseignantController;

use App\Models\Eleve;
use App\Models\Classe;
use App\Models\JournalActivite;
use App\Models\Personnel;
use App\Models\Paiement;
use App\Models\Inscription;
use App\Models\TarifScolaire;
use App\Models\CategorieFrais;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Evaluation;
use App\Models\PeriodeScolaire;
use App\Models\Bulletin;
use App\Models\Etablissement;
use App\Models\User;
use App\Models\AnneeScolaire;
use App\Models\Presence;
use App\Models\AffectationEnseignant;

/*
|--------------------------------------------------------------------------
| Accueil
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Inscription
|--------------------------------------------------------------------------
*/
Route::resource('inscriptions', InscriptionController::class)
    ->parameters([
        'inscriptions' => 'inscription',
    ])
    ->middleware(['auth', 'etablissement']);
/*
|--------------------------------------------------------------------------
| Tableau de bord
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Tableau de bord
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Tableau de bord
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR CONNECTÉ
    |--------------------------------------------------------------------------
    */

    $utilisateur = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | STATISTIQUES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMINISTRATEUR
    |--------------------------------------------------------------------------
    |
    | Un utilisateur dont id_etablissement = NULL est considéré comme
    | administrateur général de la plateforme.
    |
    */

    if ($utilisateur->id_etablissement === null) {

        /*
        |--------------------------------------------------------------------------
        | Statistiques réservées à l'administration générale
        |--------------------------------------------------------------------------
        */

        $nombreEtablissements = Etablissement::count();

        $nombreUtilisateurs = User::count();


        /*
        |--------------------------------------------------------------------------
        | On ne calcule pas les statistiques scolaires pour le Super Admin
        |--------------------------------------------------------------------------
        */

        $nombreEleves = 0;

        $nombreClasses = 0;

        $nombreEnseignants = 0;

        $nombrePaiements = 0;

        $nombreInscriptions = 0;

        $nombreFrequentations = 0;
    }


    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR D'UN ÉTABLISSEMENT
    |--------------------------------------------------------------------------
    |
    | Toutes les statistiques scolaires sont filtrées avec
    | id_etablissement de l'utilisateur connecté.
    |
    */

    else {

        $idEtablissement = $utilisateur->id_etablissement;


        /*
        |--------------------------------------------------------------------------
        | 1. NOMBRE D'ÉLÈVES
        |--------------------------------------------------------------------------
        */

        $nombreEleves = Eleve::where(
            'id_etablissement',
            $idEtablissement
        )->count();


        /*
        |--------------------------------------------------------------------------
        | 2. NOMBRE DE CLASSES
        |--------------------------------------------------------------------------
        */

        $nombreClasses = Classe::where(
            'id_etablissement',
            $idEtablissement
        )->count();


        /*
        |--------------------------------------------------------------------------
        | 3. NOMBRE D'ENSEIGNANTS
        |--------------------------------------------------------------------------
        */

        $nombreEnseignants = Personnel::where(
            'id_etablissement',
            $idEtablissement
        )
        ->whereIn('fonction', [
            'ENSEIGNANT',
            'Enseignant',
            'enseignant'
        ])
        ->whereIn('statut', [
            'ACTIF',
            'ACTIVE',
            'Actif',
            'Active'
        ])
        ->count();


        /*
        |--------------------------------------------------------------------------
        | 4. NOMBRE DE PAIEMENTS
        |--------------------------------------------------------------------------
        |
        | Les paiements sont liés aux élèves par id_eleve.
        | On ne compte donc que les paiements des élèves de cette école.
        |
        */

        $nombrePaiements = Paiement::whereHas(
            'eleve',
            function ($query) use ($idEtablissement) {

                $query->where(
                    'id_etablissement',
                    $idEtablissement
                );
            }
        )->count();


        /*
        |--------------------------------------------------------------------------
        | 5. NOMBRE D'INSCRIPTIONS
        |--------------------------------------------------------------------------
        |
        | Les inscriptions sont liées aux élèves.
        | On ne compte que les inscriptions des élèves de cette école.
        |
        */

        $nombreInscriptions = Inscription::whereHas(
            'eleve',
            function ($query) use ($idEtablissement) {

                $query->where(
                    'id_etablissement',
                    $idEtablissement
                );
            }
        )->count();


        /*
        |--------------------------------------------------------------------------
        | 6. FRÉQUENTATIONS
        |--------------------------------------------------------------------------
        |
        | La fréquentation correspond ici au nombre d'enregistrements
        | présents dans la table presences pour les élèves de l'école.
        |
        */

        $nombreFrequentations = Presence::whereHas(
            'eleve',
            function ($query) use ($idEtablissement) {

                $query->where(
                    'id_etablissement',
                    $idEtablissement
                );
            }
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Valeurs réservées à l'administration
        |--------------------------------------------------------------------------
        */

        $nombreEtablissements = 0;

        $nombreUtilisateurs = 0;
    }


    /*
    |--------------------------------------------------------------------------
    | RETOUR DU TABLEAU DE BORD
    |--------------------------------------------------------------------------
    |
    | Les activités récentes ne sont plus chargées.
    |
    */

    return view('dashboard', compact(
        'nombreEtablissements',
        'nombreUtilisateurs',
        'nombreEleves',
        'nombreClasses',
        'nombreEnseignants',
        'nombrePaiements',
        'nombreInscriptions',
        'nombreFrequentations'
    ));

})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Élèves
|--------------------------------------------------------------------------
*/

Route::resource('eleves', EleveController::class)
    ->parameters([
        'eleves' => 'eleve',
    ])
    ->middleware(['auth', 'etablissement']);


/*
|--------------------------------------------------------------------------
| Classes
|--------------------------------------------------------------------------
*/

Route::resource('classes', ClasseController::class)
    ->parameters([
        'classes' => 'classe',
    ])
    ->middleware(['auth', 'etablissement']);

/*
|--------------------------------------------------------------------------
| Personnel
|--------------------------------------------------------------------------
*/

Route::resource('personnel', PersonnelController::class)
    ->parameters([
        'personnel' => 'personnel',
    ]);

Route::resource('annees-scolaires', AnneeScolaireController::class
);
    /*
|--------------------------------------------------------------------------
| Présences
|--------------------------------------------------------------------------
*/

Route::resource('presences', PresenceController::class)
    ->parameters([
        'presences' => 'presence',
    ])
    ->middleware([
        'auth',
        'etablissement',
    ]);

    Route::resource('infrastructures', InfrastructureController::class)
    ->parameters([
        'infrastructures' => 'infrastructure',
    ])
    ->middleware(['auth', 'etablissement']);

    Route::middleware(['auth', 'etablissement'])->group(function () {

    Route::resource('recettes', RecetteController::class);
    Route::resource('depenses', DepenseController::class);


});

Route::get(
    '/recettes/{recette}/recu',
    [RecetteController::class, 'recu']
)->name('recettes.recu');

Route::get(
    '/depenses/{depense}/bon-sortie',
    [DepenseController::class, 'bonSortie']
)->name('depenses.bon-sortie');

Route::get(
    '/depenses/{depense}/bon',
    [DepenseController::class, 'bon']
)->name('depenses.bon');



    /*
|--------------------------------------------------------------------------
| Présences
|--------------------------------------------------------------------------
*/

Route::get(
    '/presences',
    [PresenceController::class, 'index']
)
    ->name('presences.index')
    ->middleware(['auth', 'etablissement', 'permission:presences,voir']);

Route::get(
    '/presences/create',
    [PresenceController::class, 'create']
)
    ->name('presences.create')
    ->middleware(['auth', 'etablissement', 'permission:presences,ajouter']);

Route::post(
    '/presences',
    [PresenceController::class, 'store']
)
    ->name('presences.store')
    ->middleware(['auth', 'etablissement', 'permission:presences,ajouter']);

Route::get(
    '/presences/{presence}',
    [PresenceController::class, 'show']
)
    ->name('presences.show')
    ->middleware(['auth', 'etablissement', 'permission:presences,voir']);

Route::get(
    '/presences/{presence}/edit',
    [PresenceController::class, 'edit']
)
    ->name('presences.edit')
    ->middleware(['auth', 'etablissement', 'permission:presences,modifier']);

    Route::get('/caisse', [CaisseController::class, 'index'])
    ->name('caisse.index');

Route::put(
    '/presences/{presence}',
    [PresenceController::class, 'update']
)
    ->name('presences.update')
    ->middleware(['auth', 'etablissement', 'permission:presences,modifier']);

Route::patch(
    '/presences/{presence}',
    [PresenceController::class, 'update']
)
    ->name('presences.update.patch')
    ->middleware(['auth', 'etablissement', 'permission:presences,modifier']);

Route::delete(
    '/presences/{presence}',
    [PresenceController::class, 'destroy']
)
    ->name('presences.destroy')
    ->middleware(['auth', 'etablissement', 'permission:presences,supprimer']);

/*
|--------------------------------------------------------------------------
| Paiements
|--------------------------------------------------------------------------
*/

Route::get('/paiements', [PaiementController::class, 'index'])
->name('paiements.index')
->middleware('permission:paiements,voir');

Route::post('/paiements', [PaiementController::class, 'store'])
->name('paiements.store')
->middleware('permission:paiements,ajouter');

Route::get('/paiements/create', [PaiementController::class, 'create'])
->name('paiements.create')
->middleware('permission:paiements,ajouter');

Route::get('/paiements/eleve/{eleve}/frais', [PaiementController::class, 'fraisEleve'])
->name('paiements.eleve.frais')
->middleware('permission:paiements,voir');

Route::get('/paiements/{paiement}', [PaiementController::class, 'show'])
->name('paiements.show')
->middleware('permission:paiements,voir');

Route::get('/paiements/{paiement}/edit', [PaiementController::class, 'edit'])
->name('paiements.edit')
->middleware('permission:paiements,modifier');

Route::put('/paiements/{paiement}', [PaiementController::class, 'update'])
->name('paiements.update')
->middleware('permission:paiements,modifier');

Route::patch('/paiements/{paiement}', [PaiementController::class, 'update'])
->name('paiements.update.patch')
->middleware('permission:paiements,modifier');

Route::delete('/paiements/{paiement}', [PaiementController::class, 'destroy'])
->name('paiements.destroy')
->middleware('permission:paiements,supprimer');


Route::get(
    'paiements/eleve/{eleve}/frais',
    [PaiementController::class, 'fraisEleve']
)->name('paiements.eleve.frais');


Route::middleware('auth')->group(function () {

Route::get(
    '/rapports/bulletin',
    [RapportController::class, 'bulletinSelection']
)->name('rapports.bulletin.selection');

Route::get(
    '/rapports/bulletin/{eleve}',
    [RapportController::class, 'bulletin']
)->name('rapports.bulletin');

    Route::get('/rapports', [RapportController::class, 'index'])
        ->name('rapports.index');

    // Rapports
    Route::get('/rapports/annuel', [RapportController::class, 'annuel'])
        ->name('rapports.annuel');

   /*
|--------------------------------------------------------------------------
| Rapport mensuel
|--------------------------------------------------------------------------
*/

Route::get(
    '/rapports/mensuel',
    [RapportController::class, 'mensuel']
)->name('rapports.mensuel');

Route::get(
    '/rapports/mensuel/pdf',
    [RapportController::class, 'mensuelPdf']
)->name('rapports.mensuel.pdf');

Route::get(
    '/rapports/mensuel/imprimer',
    [RapportController::class, 'mensuelImprimer']
)->name('rapports.mensuel.imprimer');

Route::get(
    '/rapports/mensuel/excel',
    [RapportController::class, 'mensuelExcel']
)->name('rapports.mensuel.excel');

/*
|--------------------------------------------------------------------------
| RAPPORT MENSUEL
|--------------------------------------------------------------------------
*/

Route::get(
    '/rapports/mensuel',
    [RapportController::class, 'mensuel']
)->name('rapports.mensuel');

Route::get(
    '/rapports/mensuel/pdf',
    [RapportController::class, 'mensuelPdf']
)->name('rapports.mensuel.pdf');

Route::get(
    '/rapports/mensuel/imprimer',
    [RapportController::class, 'mensuelImprimer']
)->name('rapports.mensuel.imprimer');

Route::get(
    '/rapports/mensuel/excel',
    [RapportController::class, 'mensuelExcel']
)->name('rapports.mensuel.excel');


/*
|--------------------------------------------------------------------------
| RAPPORT STATISTIQUE
|--------------------------------------------------------------------------
*/

Route::get(
    '/rapports/statistique',
    [RapportController::class, 'statistique']
)->name('rapports.statistique');

Route::get(
    '/rapports/statistique/pdf',
    [RapportController::class, 'statistiquePdf']
)->name('rapports.statistique.pdf');

Route::get(
    '/rapports/statistique/imprimer',
    [RapportController::class, 'statistiqueImprimer']
)->name('rapports.statistique.imprimer');

Route::get(
    '/rapports/statistique/excel',
    [RapportController::class, 'statistiqueExcel']
)->name('rapports.statistique.excel');


    Route::get('/rapports/palmares', [RapportController::class, 'palmares'])
        ->name('rapports.palmares');

    Route::get('/rapports/bulletin/{eleve}', [RapportController::class, 'bulletin'])
        ->name('rapports.bulletin');

    Route::get('/rapports/frequentation', [RapportController::class, 'frequentation'])
        ->name('rapports.frequentation');

    Route::get(
        '/rapports/frequentation/pdf',
        [RapportController::class, 'frequentationPdf']
                )->name('rapports.frequentation.pdf');

    Route::get(
                '/rapports/frequentation/imprimer',
                [RapportController::class, 'frequentationImprimer']
            )->name('rapports.frequentation.imprimer');

    Route::get('/rapports/enseignants', [RapportController::class, 'enseignants'])
        ->name('rapports.enseignants');

        Route::get(
    '/rapports/enseignants/pdf',
    [RapportController::class, 'enseignantsPdf']
)->name('rapports.enseignants.pdf');

Route::get(
    '/rapports/enseignants/imprimer',
    [RapportController::class, 'enseignantsImprimer']
)->name('rapports.enseignants.imprimer');

Route::get(
    '/rapports/enseignants/excel',
    [RapportController::class, 'enseignantsExcel']
)->name('rapports.enseignants.excel');

    Route::get('/rapports/finances', [RapportController::class, 'finances'])
        ->name('rapports.finances');

        Route::get(
    '/rapports/finances/pdf',
    [RapportController::class, 'financesPdf']
)->name('rapports.finances.pdf');

Route::get(
    '/rapports/finances/imprimer',
    [RapportController::class, 'financesImprimer']
)->name('rapports.finances.imprimer');

Route::get(
    '/rapports/finances/excel',
    [RapportController::class, 'financesExcel']
)->name('rapports.finances.excel');

    Route::get('/rapports/inventaire', [RapportController::class, 'inventaire'])
        ->name('rapports.inventaire');

        Route::get(
    '/rapports/inventaire/pdf',
    [RapportController::class, 'inventairePdf']
)->name('rapports.inventaire.pdf');

Route::get(
    '/rapports/inventaire/imprimer',
    [RapportController::class, 'inventaireImprimer']
)->name('rapports.inventaire.imprimer');

Route::get(
    '/rapports/inventaire/excel',
    [RapportController::class, 'inventaireExcel']
)->name('rapports.inventaire.excel');

    Route::get('/rapports/examens-nationaux', [RapportController::class, 'examensNationaux'])
        ->name('rapports.examens-nationaux');

        // Rapport annuel
Route::get(
    '/rapports/annuel/pdf',
    [RapportController::class, 'annuelPdf']
)->name('rapports.annuel.pdf');

Route::get(
    '/rapports/annuel/imprimer',
    [RapportController::class, 'annuelImprimer']
)->name('rapports.annuel.imprimer');

Route::get(
    '/rapports/annuel/excel',
    [RapportController::class, 'annuelExcel']
)->name('rapports.annuel.excel');

Route::get('/rapports/annuel/pdf', [RapportController::class, 'annuelPdf'])
    ->name('rapports.annuel.pdf');

Route::get('/rapports/annuel/imprimer', [RapportController::class, 'annuelImprimer'])
    ->name('rapports.annuel.imprimer');

Route::get('/rapports/annuel/excel', [RapportController::class, 'annuelExcel'])
    ->name('rapports.annuel.excel');

});



Route::get(
    '/rapports/examens-nationaux/pdf',
    [RapportController::class, 'examensNationauxPdf']
)->name('rapports.examens-nationaux.pdf');

Route::get(
    '/rapports/examens-nationaux/imprimer',
    [RapportController::class, 'examensNationauxImprimer']
)->name('rapports.examens-nationaux.imprimer');

Route::get(
    '/rapports/examens-nationaux/excel',
    [RapportController::class, 'examensNationauxExcel']
)->name('rapports.examens-nationaux.excel');

Route::get('/rapports/palmares/pdf', [RapportController::class, 'palmaresPdf'])
    ->name('rapports.palmares.pdf');

Route::get('/rapports/palmares/excel', [RapportController::class, 'palmaresExcel'])
    ->name('rapports.palmares.excel');

/*
|--------------------------------------------------------------------------
| AFFECTATIONS DES ENSEIGNANTS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/affectations-enseignants',
        [AffectationEnseignantController::class, 'index']
    )->name('affectations-enseignants.index');

    Route::get(
        '/affectations-enseignants/create',
        [AffectationEnseignantController::class, 'create']
    )->name('affectations-enseignants.create');

    Route::post(
        '/affectations-enseignants',
        [AffectationEnseignantController::class, 'store']
    )->name('affectations-enseignants.store');

    Route::get(
        '/affectations-enseignants/{id}',
        [AffectationEnseignantController::class, 'show']
    )->name('affectations-enseignants.show');

    Route::get(
        '/affectations-enseignants/{id}/edit',
        [AffectationEnseignantController::class, 'edit']
    )->name('affectations-enseignants.edit');

    Route::put(
        '/affectations-enseignants/{id}',
        [AffectationEnseignantController::class, 'update']
    )->name('affectations-enseignants.update');

    Route::delete(
        '/affectations-enseignants/{id}',
        [AffectationEnseignantController::class, 'destroy']
    )->name('affectations-enseignants.destroy');

});

/*
|--------------------------------------------------------------------------
| Journal des activités
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Journal des activités
|--------------------------------------------------------------------------
*/

Route::resource('journaux-activites', JournalActiviteController::class)
    ->parameters([
        'journaux-activites' => 'journal',
    ]);

Route::get(
    '/journal-activites',
    [JournalActiviteController::class, 'index']
)->name('journal-activites.index');

Route::get(
    '/journal-activites/{journal}',
    [JournalActiviteController::class, 'show']
)->name('journal-activites.show');

/*
|--------------------------------------------------------------------------
| Bulletins
|--------------------------------------------------------------------------
*/

Route::get(
    'bulletins',
    [BulletinController::class, 'index']
)->name('bulletins.index')
  ->middleware(['auth', 'etablissement']);

Route::get(
    'bulletins/{eleve}',
    [BulletinController::class, 'show']
)->name('bulletins.show')
  ->middleware(['auth', 'etablissement']);

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/


Route::resource('categories-frais', CategorieFraisController::class)
    ->parameters([
        'categories-frais' => 'categorieFrais',
    ]);

Route::resource('tarifs-scolaires', TarifScolaireController::class)
    ->parameters([
        'tarifs-scolaires' => 'tarifScolaire',
    ]);
/*
|--------------------------------------------------------------------------
| Matieres
|--------------------------------------------------------------------------
*/
Route::resource('matieres', MatiereController::class)
    ->parameters([
        'matieres' => 'matiere'
    ])
    ->middleware(['auth', 'etablissement']);

    /*
|--------------------------------------------------------------------------
| Notes
|--------------------------------------------------------------------------
*/

Route::resource('notes', NoteController::class)
    ->parameters([
        'notes' => 'note'
    ])
    ->middleware(['auth', 'etablissement']);
/*
|--------------------------------------------------------------------------
| Évaluations
|--------------------------------------------------------------------------
*/

Route::resource('evaluations', EvaluationController::class)
    ->parameters([
        'evaluations' => 'evaluation',
    ])
    ->middleware(['auth', 'etablissement']);

Route::resource('periodes-scolaires', PeriodeScolaireController::class)
    ->parameters([
        'periodes-scolaires' => 'periodeScolaire',
    ])
    ->middleware(['auth', 'etablissement']);
    
/*
|--------------------------------------------------------------------------
| Établissements
|--------------------------------------------------------------------------
*/

Route::resource('etablissements', EtablissementController::class)
    ->parameters([
        'etablissements' => 'etablissement',
    ])
    ->middleware(['auth']);

Route::middleware(['auth'])->group(function () {

    Route::resource('roles', RoleController::class);

});
/*
|--------------------------------------------------------------------------
| Utilisateurs
|--------------------------------------------------------------------------
*/

Route::resource('utilisateurs', UserController::class)
    ->parameters([
        'utilisateurs' => 'utilisateur',
    ]);

/*
|--------------------------------------------------------------------------
| Notes
|--------------------------------------------------------------------------
*/

Route::post(
    'evaluations/{evaluation}/notes',
    [NoteController::class, 'store']
)->name('evaluations.notes.store');

Route::put(
    'notes/{note}',
    [NoteController::class, 'update']
)->name('notes.update');

Route::delete(
    'notes/{note}',
    [NoteController::class, 'destroy']
)->name('notes.destroy');

Route::post(
    '/evaluations/{evaluation}/notes',
    [EvaluationController::class, 'enregistrerNotes']
)->name('evaluations.notes.enregistrer');

Route::resource('responsables', ResponsableController::class);

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'send'])
    ->name('contact.send');

require __DIR__.'/auth.php';

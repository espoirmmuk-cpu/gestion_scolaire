<x-app-layout>

    <!-- ================================================================
         EN-TÊTE
    ================================================================= -->

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Tableau de bord
                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    @if(auth()->user()->id_etablissement === null)

                        Administration générale de la plateforme

                    @else

                        Gestion de votre établissement scolaire

                    @endif

                </p>

            </div>

        </div>

    </x-slot>


    <!-- ================================================================
         CONTENU PRINCIPAL
    ================================================================= -->

    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- ========================================================
                MESSAGE DE BIENVENUE DÉFILANT
            ========================================================= -->

            <div class="bg-white rounded-xl shadow-sm p-6 mb-8 overflow-hidden">

                <div class="flex items-center">

                    <!-- ICÔNE FIXE -->
                    <div class="flex-shrink-0">

                        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">

                            <span class="text-2xl">
                                👋
                            </span>

                        </div>

                    </div>


                    <!-- ZONE DU MESSAGE -->
                    <div class="ml-4 flex-1 overflow-hidden">

                        <div class="relative overflow-hidden whitespace-nowrap">

                            <div class="inline-block animate-marquee">

                                <h3 class="text-xl font-bold text-gray-800 inline">

                                    Bienvenue,
                                    {{ Auth::user()->nom }}

                                </h3>

                                <span class="mx-4 text-gray-400">
                                    •
                                </span>

                                <p class="text-gray-500 inline">

                                    @if(auth()->user()->id_etablissement === null)

                                        Vous êtes connecté en tant que
                                        <strong>Super Administrateur</strong>.

                                    @else

                                        Heureux de vous revoir sur votre espace
                                        de gestion scolaire.

                                    @endif

                                </p>

                                <span class="mx-8 text-blue-400">
                                    ✦
                                </span>

                                <span class="text-gray-500">

                                    Nous vous souhaitons une excellente journée
                                    dans la gestion de votre établissement.

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ========================================================
                ANIMATION DU MESSAGE
            ========================================================= -->

            <style>

                @keyframes marquee {

                    0% {
                        transform: translateX(100%);
                    }

                    100% {
                        transform: translateX(-100%);
                    }

                }

                .animate-marquee {

                    animation: marquee 18s linear infinite;

                }

            </style>



            <!-- ========================================================
                 ========================================================
                 SUPER ADMINISTRATEUR
                 ========================================================
            ========================================================= -->

            @if(auth()->user()->id_etablissement === null)


                <!-- ====================================================
                     STATISTIQUES DE LA PLATEFORME
                ===================================================== -->

                <div class="mb-8">

                    <div class="mb-4">

                        <h3 class="text-lg font-bold text-gray-800">
                            Statistiques de la plateforme
                        </h3>

                        <p class="text-sm text-gray-500">
                            Vue générale de la plateforme.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">


                        <!-- =================================================
                             ÉTABLISSEMENTS
                        ================================================== -->

                        <div class="bg-white rounded-xl shadow-sm p-6">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-sm font-medium text-gray-500">
                                        Établissements
                                    </p>

                                    <p class="text-3xl font-bold text-gray-800 mt-2">
                                        {{ $nombreEtablissements ?? 0 }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-2">
                                        Établissements enregistrés
                                    </p>

                                </div>


                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">

                                    <span class="text-2xl">
                                        🏫
                                    </span>

                                </div>

                            </div>

                        </div>



                        <!-- =================================================
                             UTILISATEURS
                        ================================================== -->

                        <div class="bg-white rounded-xl shadow-sm p-6">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-sm font-medium text-gray-500">
                                        Utilisateurs
                                    </p>

                                    <p class="text-3xl font-bold text-gray-800 mt-2">
                                        {{ $nombreUtilisateurs ?? 0 }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-2">
                                        Comptes utilisateurs
                                    </p>

                                </div>


                                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">

                                    <span class="text-2xl">
                                        👤
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- ====================================================
                     ADMINISTRATION DE LA PLATEFORME
                ===================================================== -->

                <div class="mb-8">

                    <div class="mb-4">

                        <h3 class="text-lg font-bold text-gray-800">
                            Administration de la plateforme
                        </h3>

                        <p class="text-sm text-gray-500">
                            Gestion générale du système.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">


                        <!-- =================================================
                             ÉTABLISSEMENTS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_etablissements'))

                            <a href="{{ route('etablissements.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            🏫
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Établissements
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les établissements
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif



                        <!-- =================================================
                             UTILISATEURS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_utilisateurs'))

                            <a href="{{ route('utilisateurs.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            👤
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Utilisateurs
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les utilisateurs
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif



                        <!-- =================================================
                             RÔLES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_roles'))

                            @if(Route::has('roles.index'))

                                <a href="{{ route('roles.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                🔐
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Rôles
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les rôles
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif



                        <!-- =================================================
                             JOURNAL
                        ================================================== -->

                        @if(
                            auth()->user()->aLaPermission('voir_journaux') ||
                            auth()->user()->aLaPermission('gerer_activites')
                        )

                            @if(Route::has('journaux-activites.index'))

                                <a href="{{ route('journaux-activites.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                📋
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Journal des activités
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Consulter les activités
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif

                    </div>

                </div>



            <!-- ========================================================
                 FIN SUPER ADMINISTRATEUR
            ========================================================= -->

            @else


                <!-- ========================================================
                     ========================================================
                     UTILISATEUR D'UN ÉTABLISSEMENT
                     ========================================================
                ========================================================= -->


                <!-- ====================================================
                     STATISTIQUES DE MON ÉTABLISSEMENT
                ===================================================== -->

                <div class="mb-8">

                    <div class="mb-4">

                        <h3 class="text-lg font-bold text-gray-800">
                            Statistiques de mon établissement
                        </h3>

                        <p class="text-sm text-gray-500">
                            Situation actuelle de votre établissement scolaire.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">


                        <!-- =================================================
                             ÉLÈVES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_eleves'))

                            <div class="bg-white rounded-xl shadow-sm p-6">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm font-medium text-gray-500">
                                            Élèves
                                        </p>

                                        <p class="text-3xl font-bold text-gray-800 mt-2">
                                            {{ $nombreEleves ?? 0 }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-2">
                                            Élèves de l'établissement
                                        </p>

                                    </div>


                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">

                                        <span class="text-2xl">
                                            🎓
                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endif



                        <!-- =================================================
                             CLASSES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_classes'))

                            <div class="bg-white rounded-xl shadow-sm p-6">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm font-medium text-gray-500">
                                            Classes
                                        </p>

                                        <p class="text-3xl font-bold text-gray-800 mt-2">
                                            {{ $nombreClasses ?? 0 }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-2">
                                            Classes de l'établissement
                                        </p>

                                    </div>


                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">

                                        <span class="text-2xl">
                                            🏫
                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endif



                        <!-- =================================================
                             ENSEIGNANTS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_enseignants'))

                            <div class="bg-white rounded-xl shadow-sm p-6">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm font-medium text-gray-500">
                                            Enseignants
                                        </p>

                                        <p class="text-3xl font-bold text-gray-800 mt-2">
                                            {{ $nombreEnseignants ?? 0 }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-2">
                                            Enseignants actifs
                                        </p>

                                    </div>


                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">

                                        <span class="text-2xl">
                                            👨‍🏫
                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endif

                        <!-- =================================================
                             PAIEMENTS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_paiements'))

                            <div class="bg-white rounded-xl shadow-sm p-6">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm font-medium text-gray-500">
                                            Paiements
                                        </p>

                                        <p class="text-3xl font-bold text-gray-800 mt-2">
                                            {{ $nombrePaiements ?? 0 }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-2">
                                            Paiements enregistrés
                                        </p>

                                    </div>


                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">

                                        <span class="text-2xl">
                                            💰
                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endif

                        <!-- =================================================
                             INSCRIPTIONS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_inscriptions'))

                            <div class="bg-white rounded-xl shadow-sm p-6">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm font-medium text-gray-500">
                                            Inscriptions
                                        </p>

                                        <p class="text-3xl font-bold text-gray-800 mt-2">
                                            {{ $nombreInscriptions ?? 0 }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-2">
                                            Inscriptions de l'établissement
                                        </p>

                                    </div>


                                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">

                                        <span class="text-2xl">
                                            📝
                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endif

                        <!-- =================================================
                             PRÉSENCES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_presences'))

                            @if(Route::has('presences.index'))

                                <div class="bg-white rounded-xl shadow-sm p-6">

                                    <div class="flex items-center justify-between">

                                        <div>

                                            <p class="text-sm font-medium text-gray-500">
                                                Présences
                                            </p>

                                            <p class="text-3xl font-bold text-gray-800 mt-2">
                                                {{ $nombrePresences ?? 0 }}
                                            </p>

                                            <p class="text-xs text-gray-400 mt-2">
                                                Présences enregistrées
                                            </p>

                                        </div>

                                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">

                                            <span class="text-2xl">
                                                🕘
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        @endif

                    </div>

                </div>


                <!-- ====================================================
                     GESTION SCOLAIRE
                ===================================================== -->

                <div class="mb-8">

                    <div class="mb-4">

                        <h3 class="text-lg font-bold text-gray-800">
                            Gestion scolaire
                        </h3>

                        <p class="text-sm text-gray-500">
                            Accédez rapidement aux principales fonctionnalités.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- =================================================
                             CLASSES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_classes'))

                            <a href="{{ route('classes.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            🏫
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Classes
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les classes
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif
                       
                        <!-- =================================================
                             ÉLÈVES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_eleves'))

                            <a href="{{ route('eleves.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            🎓
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Élèves
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les élèves
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif



                        <!-- =================================================
                             INSCRIPTIONS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_inscriptions'))

                            <a href="{{ route('inscriptions.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            📝
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Inscriptions
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les inscriptions
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif


                        <!-- =================================================
                             PRÉSENCES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_presences'))

                            @if(Route::has('presences.index'))

                                <a href="{{ route('presences.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                🕘
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Présences
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les présences
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif


                        <!-- =================================================
                             MATIÈRES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_matieres'))

                            <a href="{{ route('matieres.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            📚
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Matières
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les matières
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif

                        <!-- =================================================
                             ÉVALUATIONS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_evaluations'))

                            <a href="{{ route('evaluations.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            📝
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Évaluations
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les évaluations
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif

                        
                       

                        <!-- =================================================
                             NOTES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_notes'))

                            <a href="{{ route('notes.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            📊
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Notes
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les notes
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif



                        <!-- =================================================
                             ENSEIGNANTS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_enseignants'))

                            @if(Route::has('affectations-enseignants.index'))

                                <a href="{{ route('affectations-enseignants.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                👨‍🏫
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Affectation des enseignants
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les affectations
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif




                        <!-- =================================================
                             BULLETINS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_bulletins'))

                            <a href="{{ route('bulletins.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            📋
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Bulletins
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les bulletins
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif




                        <!-- =================================================
                             CATEGORIE DES FRAIS SCOLAIRES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_frais'))

                            @if(Route::has('categories-frais.index'))

                                <a href="{{ route('categories-frais.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                💵
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Catégorie des frais scolaires
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer la catégorie des frais scolaires
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif

                        <!-- =================================================
                             FRAIS SCOLAIRES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_frais'))

                            @if(Route::has('tarifs-scolaires.index'))

                                <a href="{{ route('tarifs-scolaires.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                💵
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Frais scolaires / Tarifs
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les frais scolaires
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif



                        <!-- =================================================
                             PAIEMENTS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_paiements'))

                            <a href="{{ route('paiements.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            💰
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Paiements
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer les paiements
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif



                        <!-- =================================================
                             RECETTES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_recettes'))

                            @if(Route::has('recettes.index'))

                                <a href="{{ route('recettes.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                💵
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Recettes
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les recettes
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif



                        <!-- =================================================
                             DÉPENSES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_depenses'))

                            @if(Route::has('depenses.index'))

                                <a href="{{ route('depenses.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                💸
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Dépenses
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les dépenses
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif


                        
                        <!-- =================================================
                             PERSONNEL
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_personnel'))

                            <a href="{{ route('personnel.index') }}"
                               class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                <div class="flex items-center">

                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">

                                        <span class="text-2xl">
                                            👨‍🏫
                                        </span>

                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-semibold text-gray-800">
                                            Personnel
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Gérer le personnel
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @endif



                        <!-- =================================================
                             ANNEES SCOLAIRES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_annees_scolaires'))

                            @if(Route::has('annees-scolaires.index'))

                                <a href="{{ route('annees-scolaires.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                📅
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Années scolaires
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les années scolaires
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif


                        <!-- =================================================
                             PÉRIODES SCOLAIRES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_annees_scolaires'))

                            @if(Route::has('periodes-scolaires.index'))

                                <a href="{{ route('periodes-scolaires.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                📅
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Périodes scolaires
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les périodes scolaires
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif



                        <!-- =================================================
                             INVENTAIRE
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_inventaire'))

                            @if(Route::has('inventaire.index'))

                                <a href="{{ route('inventaire.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                📦
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Inventaire
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer l'inventaire
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif



                        <!-- =================================================
                             INFRASTRUCTURES
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('gerer_infrastructures'))

                            @if(Route::has('infrastructures.index'))

                                <a href="{{ route('infrastructures.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                🏗️
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Infrastructures
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Gérer les infrastructures
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif



                        <!-- =================================================
                             RAPPORTS
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('voir_rapports'))

                            @if(Route::has('rapports.index'))

                                <a href="{{ route('rapports.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                📈
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Rapports
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Consulter les rapports
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif

                        <!-- =================================================
                            JOURNAL DES ACTIVITÉS
                        ================================================== -->

                        @if(
                            auth()->user()->aLaPermission('voir_journaux') ||
                            auth()->user()->aLaPermission('gerer_activites')
                        )

                            @if(Route::has('journaux-activites.index'))

                                <a href="{{ route('journaux-activites.index') }}"
                                class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                📋
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Journal des activités
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Consulter toutes les activités
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif

                        <!-- =================================================
                             EXPORTATION
                        ================================================== -->

                        @if(auth()->user()->aLaPermission('exporter_donnees'))

                            @if(Route::has('exports.index'))

                                <a href="{{ route('exports.index') }}"
                                   class="group bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition duration-200">

                                    <div class="flex items-center">

                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">

                                            <span class="text-2xl">
                                                📤
                                            </span>

                                        </div>

                                        <div class="ml-4">

                                            <h4 class="font-semibold text-gray-800">
                                                Exporter les données
                                            </h4>

                                            <p class="text-sm text-gray-500 mt-1">
                                                Exporter les données
                                            </p>

                                        </div>

                                    </div>

                                </a>

                            @endif

                        @endif

                    </div>

                </div>


            @endif


        </div>

    </div>

</x-app-layout>
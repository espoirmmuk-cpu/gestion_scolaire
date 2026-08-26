<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Détail de l'affectation - Gestion Scolaire</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="bg-gray-100 min-h-screen">


    <!-- =========================================================
         EN-TÊTE
    ========================================================== -->

    <header class="bg-white shadow-sm">

        <div class="max-w-7xl mx-auto px-6 py-4">

            <div class="flex items-center justify-between">

                <div>

                    <h1 class="text-xl font-bold text-gray-800">
                        Affectation d'un enseignant
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Détails de l'affectation
                    </p>

                </div>


                <!-- RETOUR -->

                <a href="{{ route('affectations-enseignants.index') }}"
                   class="inline-flex items-center px-4 py-2
                          bg-gray-100 text-gray-700 rounded-lg
                          hover:bg-gray-200
                          transition duration-200">

                    <span class="mr-2">
                        ←
                    </span>

                    Retour

                </a>

            </div>

        </div>

    </header>



    <!-- =========================================================
         CONTENU
    ========================================================== -->

    <main class="py-10">

        <div class="max-w-5xl mx-auto px-6">


            <!-- =====================================================
                 MESSAGE SUCCÈS
            ====================================================== -->

            @if(session('success'))

                <div class="mb-6 bg-green-100 border border-green-300
                            text-green-700 px-4 py-3 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif



            <!-- =====================================================
                 CARTE PRINCIPALE
            ====================================================== -->

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">


                <!-- TITRE -->

                <div class="px-8 py-6 border-b border-gray-200">

                    <h2 class="text-2xl font-bold text-gray-800">

                        Détails de l'affectation

                    </h2>

                    <p class="text-sm text-gray-500 mt-1">

                        Informations concernant l'enseignant et son
                        affectation.

                    </p>

                </div>



                <!-- =================================================
                     INFORMATIONS
                ================================================== -->

                <div class="p-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <!-- =================================================
                             ENSEIGNANT
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                Enseignant
                            </p>

                            <p class="text-lg font-semibold text-gray-800">

                                {{ $affectation->enseignant_nom }}

                                @if($affectation->enseignant_postnom)
                                    {{ $affectation->enseignant_postnom }}
                                @endif

                                @if($affectation->enseignant_prenom)
                                    {{ $affectation->enseignant_prenom }}
                                @endif

                            </p>

                        </div>



                        <!-- =================================================
                             MATRICULE
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                Matricule
                            </p>

                            <p class="text-lg font-semibold text-gray-800">

                                {{ $affectation->matricule }}

                            </p>

                        </div>



                        <!-- =================================================
                             CLASSE
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                Classe
                            </p>

                            <p class="text-lg font-semibold text-gray-800">

                                {{ $affectation->classe_libelle }}

                            </p>

                        </div>



                        <!-- =================================================
                             MATIÈRE
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                Matière
                            </p>

                            <p class="text-lg font-semibold text-gray-800">

                                {{ $affectation->matiere_libelle }}

                            </p>

                        </div>



                        <!-- =================================================
                             ANNÉE SCOLAIRE
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                Année scolaire
                            </p>

                            <p class="text-lg font-semibold text-gray-800">

                                {{ $affectation->annee_scolaire_libelle }}

                            </p>

                        </div>



                        <!-- =================================================
                             TITULAIRE
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                Statut de titularité
                            </p>

                            @if($affectation->est_titulaire)

                                <span class="inline-flex items-center
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold
                                             bg-green-100 text-green-700">

                                    ✓ Titulaire

                                </span>

                            @else

                                <span class="inline-flex items-center
                                             px-3 py-1 rounded-full
                                             text-sm font-semibold
                                             bg-gray-100 text-gray-600">

                                    Non titulaire

                                </span>

                            @endif

                        </div>



                        <!-- =================================================
                             ID AFFECTATION
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                N° Affectation
                            </p>

                            <p class="text-lg font-semibold text-gray-800">

                                #{{ $affectation->id_affectation }}

                            </p>

                        </div>



                        <!-- =================================================
                             ÉTABLISSEMENT
                        ================================================== -->

                        <div class="border border-gray-200 rounded-lg p-5">

                            <p class="text-sm text-gray-500 mb-1">
                                Établissement
                            </p>

                            <p class="text-lg font-semibold text-gray-800">

                                {{ $affectation->id_etablissement }}

                            </p>

                        </div>

                    </div>



                    <!-- =================================================
                         DATES
                    ================================================== -->

                    <div class="mt-8 border-t border-gray-200 pt-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-4">

                            Informations supplémentaires

                        </h3>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            <!-- DATE CRÉATION -->

                            <div>

                                <p class="text-sm text-gray-500">
                                    Date de création
                                </p>

                                <p class="font-medium text-gray-700 mt-1">

                                    @if($affectation->date_creation)

                                        {{ \Carbon\Carbon::parse($affectation->date_creation)->format('d/m/Y H:i') }}

                                    @else

                                        Non renseignée

                                    @endif

                                </p>

                            </div>



                            <!-- DATE MODIFICATION -->

                            <div>

                                <p class="text-sm text-gray-500">
                                    Dernière modification
                                </p>

                                <p class="font-medium text-gray-700 mt-1">

                                    @if($affectation->date_modification)

                                        {{ \Carbon\Carbon::parse($affectation->date_modification)->format('d/m/Y H:i') }}

                                    @else

                                        Aucune modification

                                    @endif

                                </p>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     ACTIONS
                ================================================== -->

                <div class="px-8 py-5 bg-gray-50 border-t border-gray-200">

                    <div class="flex flex-col sm:flex-row
                                sm:justify-end gap-3">


                        <!-- MODIFIER -->

                        <a href="{{ route(
                            'affectations-enseignants.edit',
                            $affectation->id_affectation
                        ) }}"
                           class="inline-flex items-center justify-center
                                  px-5 py-2.5
                                  bg-blue-600 text-white
                                  font-semibold rounded-lg
                                  hover:bg-blue-700
                                  transition duration-200">

                            ✏️

                            <span class="ml-2">
                                Modifier
                            </span>

                        </a>



                        <!-- SUPPRIMER -->

                        <form method="POST"
                              action="{{ route(
                                  'affectations-enseignants.destroy',
                                  $affectation->id_affectation
                              ) }}"
                              onsubmit="return confirm(
                                  'Voulez-vous vraiment supprimer cette affectation ?'
                              );">

                            @csrf

                            @method('DELETE')

                            <button type="submit"
                                    class="w-full inline-flex
                                           items-center justify-center
                                           px-5 py-2.5
                                           bg-red-600 text-white
                                           font-semibold rounded-lg
                                           hover:bg-red-700
                                           transition duration-200">

                                🗑️

                                <span class="ml-2">
                                    Supprimer
                                </span>

                            </button>

                        </form>



                        <!-- RETOUR -->

                        <a href="{{ route(
                            'affectations-enseignants.index'
                        ) }}"
                           class="inline-flex items-center justify-center
                                  px-5 py-2.5
                                  bg-gray-200 text-gray-700
                                  font-semibold rounded-lg
                                  hover:bg-gray-300
                                  transition duration-200">

                            ←

                            <span class="ml-2">
                                Retour à la liste
                            </span>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </main>



    <!-- =========================================================
         PIED DE PAGE
    ========================================================== -->

    <footer class="bg-white border-t mt-10">

        <div class="max-w-7xl mx-auto px-6 py-6">

            <div class="text-center text-sm text-gray-500">

                © {{ date('Y') }} Gestion Scolaire.
                Tous droits réservés.

            </div>

        </div>

    </footer>


</body>

</html>
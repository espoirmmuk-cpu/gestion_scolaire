<x-app-layout>

<!-- ================================================================
     EN-TÊTE
================================================================= -->

<x-slot name="header">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Affectation des enseignants
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gérer l'affectation des enseignants aux classes et aux matières.
            </p>

        </div>

        <!-- BOUTON AJOUTER -->

        @if(auth()->user()->aLaPermission('gerer_enseignants'))

            @if(Route::has('affectations-enseignants.create'))

                <a href="{{ route('affectations-enseignants.create') }}"
                   class="inline-flex items-center justify-center px-5 py-2.5
                          bg-blue-600 text-white font-semibold rounded-lg
                          shadow-sm hover:bg-blue-700 transition duration-200">

                    <span class="mr-2 text-lg">
                        ➕
                    </span>

                    Nouvelle affectation

                </a>

            @endif

        @endif

    </div>

</x-slot>


<!-- ================================================================
     CONTENU PRINCIPAL
================================================================= -->

<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        <!-- ========================================================
             MESSAGE DE SUCCÈS
        ========================================================= -->

        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-300
                        text-green-700 px-5 py-4 rounded-lg shadow-sm">

                <div class="flex items-center">

                    <span class="text-xl mr-3">
                        ✅
                    </span>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            </div>

        @endif


        <!-- ========================================================
             MESSAGE D'ERREUR
        ========================================================= -->

        @if(session('error'))

            <div class="mb-6 bg-red-100 border border-red-300
                        text-red-700 px-5 py-4 rounded-lg shadow-sm">

                <div class="flex items-center">

                    <span class="text-xl mr-3">
                        ⚠️
                    </span>

                    <span>
                        {{ session('error') }}
                    </span>

                </div>

            </div>

        @endif


        <!-- ========================================================
             ERREURS DE VALIDATION
        ========================================================= -->

        @if($errors->any())

            <div class="mb-6 bg-red-100 border border-red-300
                        text-red-700 px-5 py-4 rounded-lg shadow-sm">

                <p class="font-semibold mb-2">
                    Des erreurs sont survenues :
                </p>

                <ul class="list-disc ml-5 text-sm">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- ========================================================
             BLOC PRINCIPAL
        ========================================================= -->

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            <!-- ====================================================
                 EN-TÊTE DU TABLEAU
            ===================================================== -->

            <div class="px-6 py-5 border-b border-gray-200">

                <div class="flex flex-col sm:flex-row sm:items-center
                            sm:justify-between gap-3">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Liste des affectations
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Liste des enseignants affectés aux différentes classes et matières.
                        </p>

                    </div>


                    <!-- NOMBRE D'AFFECTATIONS -->

                    <div class="inline-flex items-center px-4 py-2
                                bg-blue-50 text-blue-700 rounded-lg">

                        <span class="mr-2">
                            📋
                        </span>

                        <span class="font-semibold">
                            {{ $affectations->count() }}
                        </span>

                        <span class="ml-1 text-sm">
                            affectation(s)
                        </span>

                    </div>

                </div>

            </div>


            <!-- ====================================================
                 TABLEAU
            ===================================================== -->

            @if($affectations->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <!-- ENSEIGNANT -->

                                <th class="px-6 py-4 text-left text-xs
                                           font-semibold text-gray-500
                                           uppercase tracking-wider">
                                    Enseignant
                                </th>


                                <!-- CLASSE -->

                                <th class="px-6 py-4 text-left text-xs
                                           font-semibold text-gray-500
                                           uppercase tracking-wider">
                                    Classe
                                </th>


                                <!-- MATIÈRE -->

                                <th class="px-6 py-4 text-left text-xs
                                           font-semibold text-gray-500
                                           uppercase tracking-wider">
                                    Matière
                                </th>


                                <!-- ANNÉE SCOLAIRE -->

                                <th class="px-6 py-4 text-left text-xs
                                           font-semibold text-gray-500
                                           uppercase tracking-wider">
                                    Année scolaire
                                </th>


                                <!-- TITULAIRE -->

                                <th class="px-6 py-4 text-center text-xs
                                           font-semibold text-gray-500
                                           uppercase tracking-wider">
                                    Titulaire
                                </th>


                                <!-- ACTIONS -->

                                <th class="px-6 py-4 text-right text-xs
                                           font-semibold text-gray-500
                                           uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($affectations as $affectation)

                                <tr class="hover:bg-gray-50 transition duration-150">


                                    <!-- ==================================================
                                         ENSEIGNANT
                                    =================================================== -->

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="flex items-center">

                                            <div class="w-10 h-10 bg-green-100
                                                        rounded-full flex items-center
                                                        justify-center flex-shrink-0">

                                                <span class="text-lg">
                                                    👨‍🏫
                                                </span>

                                            </div>

                                            <div class="ml-3">

                                                <div class="text-sm font-semibold text-gray-800">

                                                    @if($affectation->personnel)

                                                        {{ $affectation->personnel->nom }}

                                                        @if($affectation->personnel->postnom)
                                                            {{ $affectation->personnel->postnom }}
                                                        @endif

                                                        @if($affectation->personnel->prenom)
                                                            {{ $affectation->personnel->prenom }}
                                                        @endif

                                                    @else

                                                        Enseignant introuvable

                                                    @endif

                                                </div>

                                                @if($affectation->personnel?->matricule)

                                                    <div class="text-xs text-gray-500 mt-1">

                                                        Matricule :
                                                        {{ $affectation->personnel->matricule }}

                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    <!-- ==================================================
                                         CLASSE
                                    =================================================== -->

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="text-sm font-medium text-gray-800">

                                            {{ $affectation->classe->libelle
                                                ?? 'Classe introuvable' }}

                                        </div>

                                    </td>


                                    <!-- ==================================================
                                         MATIÈRE
                                    =================================================== -->

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="text-sm font-medium text-gray-800">

                                            {{ $affectation->matiere->libelle
                                                ?? 'Matière introuvable' }}

                                        </div>

                                    </td>


                                    <!-- ==================================================
                                         ANNÉE SCOLAIRE
                                    =================================================== -->

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="text-sm font-medium text-gray-800">

                                            {{ $affectation->anneeScolaire->libelle
                                                ?? 'Année scolaire introuvable' }}

                                        </div>

                                    </td>


                                    <!-- ==================================================
                                         TITULAIRE
                                    =================================================== -->

                                    <td class="px-6 py-4 whitespace-nowrap text-center">

                                        @if($affectation->est_titulaire)

                                            <span class="inline-flex items-center
                                                         px-3 py-1 rounded-full
                                                         text-xs font-semibold
                                                         bg-green-100 text-green-700">

                                                ✓ Oui

                                            </span>

                                        @else

                                            <span class="inline-flex items-center
                                                         px-3 py-1 rounded-full
                                                         text-xs font-semibold
                                                         bg-gray-100 text-gray-600">

                                                Non

                                            </span>

                                        @endif

                                    </td>


                                    <!-- ==================================================
                                         ACTIONS
                                    =================================================== -->

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="flex items-center justify-end gap-2">


                                            <!-- MODIFIER -->

                                            @if(auth()->user()->aLaPermission('gerer_enseignants'))

                                                @if(Route::has('affectations-enseignants.edit'))

                                                    <a href="{{ route(
                                                        'affectations-enseignants.edit',
                                                        $affectation->id_affectation
                                                    ) }}"
                                                       title="Modifier"
                                                       class="inline-flex items-center justify-center
                                                              w-9 h-9 bg-blue-100 text-blue-600
                                                              rounded-lg hover:bg-blue-200
                                                              transition duration-200">

                                                        ✏️

                                                    </a>

                                                @endif

                                            @endif


                                            <!-- SUPPRIMER -->

                                            @if(auth()->user()->aLaPermission('gerer_enseignants'))

                                                @if(Route::has('affectations-enseignants.destroy'))

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
                                                                title="Supprimer"
                                                                class="inline-flex items-center
                                                                       justify-center w-9 h-9
                                                                       bg-red-100 text-red-600
                                                                       rounded-lg hover:bg-red-200
                                                                       transition duration-200">

                                                            🗑️

                                                        </button>

                                                    </form>

                                                @endif

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <!-- ====================================================
                     PAGINATION
                ===================================================== -->

                @if(method_exists($affectations, 'links'))

                    <div class="px-6 py-4 border-t border-gray-200">

                        {{ $affectations->links() }}

                    </div>

                @endif


            @else


                <!-- ====================================================
                     AUCUNE AFFECTATION
                ===================================================== -->

                <div class="px-6 py-16 text-center">

                    <div class="w-16 h-16 mx-auto bg-gray-100
                                rounded-full flex items-center
                                justify-center">

                        <span class="text-3xl">
                            👨‍🏫
                        </span>

                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-gray-800">
                        Aucune affectation
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Aucune affectation d'enseignant n'a encore été enregistrée.
                    </p>


                    @if(auth()->user()->aLaPermission('gerer_enseignants'))

                        @if(Route::has('affectations-enseignants.create'))

                            <a href="{{ route('affectations-enseignants.create') }}"
                               class="inline-flex items-center mt-5
                                      px-5 py-2.5 bg-blue-600
                                      text-white font-semibold rounded-lg
                                      hover:bg-blue-700 transition duration-200">

                                <span class="mr-2">
                                    ➕
                                </span>

                                Ajouter une affectation

                            </a>

                        @endif

                    @endif

                </div>

            @endif

        </div>

    </div>

</div>

</x-app-layout>
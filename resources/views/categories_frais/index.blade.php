<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Catégories de frais
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gestion des différents types de frais scolaires.
            </p>

        </div>


        <a href="{{ route('categories-frais.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <span class="mr-2">
                ➕
            </span>

            Nouvelle catégorie

        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Message de succès --}}

        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        {{-- Message d'erreur --}}

        @if(session('error'))

            <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête du tableau --}}

            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Liste des catégories
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $categories->count() }} catégorie(s) enregistrée(s).
                </p>

            </div>


            @if($categories->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Libellé
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($categories as $categorie)

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                        {{ $categorie->id_categorie_frais }}

                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="flex items-center">

                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">

                                                <span class="text-xl">
                                                    💰
                                                </span>

                                            </div>

                                            <div class="ml-3">

                                                <p class="text-sm font-semibold text-gray-800">

                                                    {{ $categorie->libelle }}

                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-500">

                                        {{ $categorie->description ?: 'Aucune description' }}

                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap text-center">

                                        @if($categorie->statut === 'ACTIVE')

                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">

                                                ACTIVE

                                            </span>

                                        @else

                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">

                                                INACTIVE

                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">

                                        <div class="flex justify-end items-center gap-2">


                                            {{-- Modifier --}}

                                            <a href="{{ route('categories-frais.edit', $categorie) }}"
                                               class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">

                                                ✏️

                                            </a>


                                            {{-- Supprimer --}}

                                            <form action="{{ route('categories-frais.destroy', $categorie) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">

                                                    🗑️

                                                </button>

                                            </form>


                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-12 text-center">

                    <div class="text-5xl mb-4">
                        💰
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        Aucune catégorie de frais
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Commencez par créer votre première catégorie.
                    </p>

                    <a href="{{ route('categories-frais.create') }}"
                       class="inline-flex items-center mt-5 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">

                        ➕ Ajouter une catégorie

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


</x-app-layout>

<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Tarifs scolaires
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gestion des frais scolaires par année, classe et catégorie.
            </p>

        </div>


        <a href="{{ route('tarifs-scolaires.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

            <span class="mr-2">➕</span>

            Nouveau tarif

        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Succès --}}

        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        {{-- Erreur --}}

        @if(session('error'))

            <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête --}}

            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Liste des tarifs
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $tarifs->count() }} tarif(s) enregistré(s).
                </p>

            </div>


            @if($tarifs->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    #
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Année scolaire
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Classe
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Catégorie
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Montant
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Devise
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($tarifs as $tarif)

                                <tr class="hover:bg-gray-50 transition">


                                    {{-- ID --}}

                                    <td class="px-6 py-4 text-sm text-gray-500">

                                        {{ $tarif->id_tarif }}

                                    </td>


                                    {{-- Année --}}

                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">

                                        {{ $tarif->anneeScolaire->libelle ?? '—' }}

                                    </td>


                                    {{-- Classe --}}

                                    <td class="px-6 py-4 text-sm text-gray-700">

                                        {{ $tarif->classe->libelle ?? '—' }}

                                    </td>


                                    {{-- Catégorie --}}

                                    <td class="px-6 py-4">

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">

                                            💰
                                            {{ $tarif->categorieFrais->libelle ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- Montant --}}

                                    <td class="px-6 py-4 text-right">

                                        <span class="font-bold text-gray-800">

                                            {{ number_format($tarif->montant, 2, ',', ' ') }}

                                        </span>

                                    </td>


                                    {{-- Devise --}}

                                    <td class="px-6 py-4 text-center">

                                        <span class="text-sm font-semibold text-gray-600">

                                            {{ $tarif->devise }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="px-6 py-4">

                                        <div class="flex justify-end gap-2">


                                            <a href="{{ route('tarifs-scolaires.edit', $tarif) }}"
                                               class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">

                                                ✏️

                                            </a>


                                            <form action="{{ route('tarifs-scolaires.destroy', $tarif) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer ce tarif ?');">

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
                        Aucun tarif scolaire
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Commencez par créer votre premier tarif.
                    </p>

                    <a href="{{ route('tarifs-scolaires.create') }}"
                       class="inline-flex items-center mt-5 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">

                        ➕ Ajouter un tarif

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


</x-app-layout>

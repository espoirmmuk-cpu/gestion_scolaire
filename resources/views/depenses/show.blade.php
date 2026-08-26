<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Détail de la dépense
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Consultation des informations de la dépense
                </p>

            </div>


            <div class="flex items-center gap-2">

                @can('update', $depense)

                    <a href="{{ route('depenses.edit', $depense) }}"
                       class="px-4 py-2 bg-gray-600 text-white
                              rounded-lg hover:bg-gray-700 transition">

                        Modifier

                    </a>

                @endcan


                <a href="{{ route('depenses.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700
                          rounded-lg hover:bg-gray-300 transition">

                    Retour

                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


            {{-- Message succès --}}

            @if (session('success'))

                <div class="mb-6 bg-green-100 border border-green-300
                            text-green-700 px-4 py-3 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            <div class="bg-white shadow-sm rounded-xl overflow-hidden">


                {{-- En-tête --}}

                <div class="px-6 py-5 border-b border-gray-200">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-800">

                                {{ $depense->categorie }}

                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                Dépense n° {{ $depense->id_depense }}

                            </p>

                        </div>


                        <div class="text-right">

                            <div class="text-2xl font-bold text-gray-800">

                                {{ number_format(
                                    $depense->montant,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                                {{ $depense->devise }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Informations --}}

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- Date --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Date de la dépense
                            </p>

                            <p class="mt-1 text-gray-900">

                                {{ $depense->date_depense
                                    ? \Carbon\Carbon::parse(
                                        $depense->date_depense
                                    )->format('d/m/Y à H:i')
                                    : '-' }}

                            </p>

                        </div>


                        {{-- Catégorie --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Catégorie
                            </p>

                            <p class="mt-1 text-gray-900">
                                {{ $depense->categorie }}
                            </p>

                        </div>


                        {{-- Montant --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Montant
                            </p>

                            <p class="mt-1 text-gray-900 font-semibold">

                                {{ number_format(
                                    $depense->montant,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                                {{ $depense->devise }}

                            </p>

                        </div>


                        {{-- Devise --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Devise
                            </p>

                            <p class="mt-1">

                                <span class="inline-flex px-2.5 py-1
                                             rounded-full
                                             bg-gray-100 text-gray-700
                                             text-xs font-semibold">

                                    {{ $depense->devise }}

                                </span>

                            </p>

                        </div>


                        {{-- Année scolaire --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Année scolaire
                            </p>

                            <p class="mt-1 text-gray-900">

                                {{ $depense->anneeScolaire->libelle
                                    ?? $depense->anneeScolaire->annee
                                    ?? '-' }}

                            </p>

                        </div>


                        {{-- Établissement --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Établissement
                            </p>

                            <p class="mt-1 text-gray-900">

                                {{ $depense->etablissement->nom ?? '-' }}

                            </p>

                        </div>


                        {{-- Utilisateur --}}

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Enregistré par
                            </p>

                            <p class="mt-1 text-gray-900">

                                {{ $depense->utilisateur->name
                                    ?? $depense->utilisateur->nom
                                    ?? '-' }}

                            </p>

                        </div>


                    </div>


                    {{-- Description --}}

                    <div class="mt-8 pt-6 border-t border-gray-200">

                        <p class="text-sm font-medium text-gray-500 mb-2">
                            Description
                        </p>

                        @if ($depense->description)

                            <div class="bg-gray-50 rounded-lg p-4 text-gray-700">

                                {{ $depense->description }}

                            </div>

                        @else

                            <p class="text-gray-400 italic">
                                Aucune description.
                            </p>

                        @endif

                    </div>


                    {{-- Actions --}}

                    <div class="mt-8 pt-6 border-t border-gray-200">

                        <div class="flex items-center justify-between">


                            <a href="{{ route('depenses.index') }}"
                               class="px-5 py-2.5 bg-gray-200 text-gray-700
                                      rounded-lg hover:bg-gray-300 transition">

                                ← Retour à la liste

                            </a>


                            <div class="flex items-center gap-2">

                                @can('update', $depense)

                                    <a href="{{ route('depenses.edit', $depense) }}"
                                       class="px-5 py-2.5 bg-gray-600 text-white
                                              rounded-lg hover:bg-gray-700 transition">

                                        Modifier

                                    </a>

                                @endcan


                                @can('delete', $depense)

                                    <form method="POST"
                                          action="{{ route('depenses.destroy', $depense) }}"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette dépense ?');">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-5 py-2.5 bg-red-600 text-white
                                                   rounded-lg hover:bg-red-700 transition">

                                            Supprimer

                                        </button>

                                    </form>

                                @endcan

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
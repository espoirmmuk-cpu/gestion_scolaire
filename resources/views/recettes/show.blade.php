<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails de la recette
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Consultation des informations de la recette
            </p>

        </div>

        <div class="flex gap-2">

            @can('update', $recette)

                <a href="{{ route('recettes.edit', $recette) }}"
                   class="px-4 py-2 bg-gray-600 text-white
                          rounded-lg hover:bg-gray-700">

                    Modifier

                </a>

            @endcan

            <a href="{{ route('recettes.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700
                      rounded-lg hover:bg-gray-300">

                Retour

            </a>

        </div>

    </div>

</x-slot>


<div class="py-6 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-sm rounded-xl overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Informations de la recette
                </h3>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- Date --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Date de la recette
                        </p>

                        <p class="mt-1 text-gray-900">

                            {{ $recette->date_recette
                                ? \Carbon\Carbon::parse(
                                    $recette->date_recette
                                )->format('d/m/Y')
                                : '-' }}

                        </p>

                    </div>


                    {{-- Établissement --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Établissement
                        </p>

                        <p class="mt-1 text-gray-900">

                            {{ $recette->etablissement->nom ?? '-' }}

                        </p>

                    </div>


                    {{-- Année scolaire --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Année scolaire
                        </p>

                        <p class="mt-1 text-gray-900">

                            {{ $recette->anneeScolaire->libelle
                                ?? $recette->anneeScolaire->annee
                                ?? '-' }}

                        </p>

                    </div>


                    {{-- Source --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Source
                        </p>

                        <p class="mt-1 text-gray-900 font-medium">

                            {{ $recette->source }}

                        </p>

                    </div>


                    {{-- Montant --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Montant
                        </p>

                        <p class="mt-1 text-gray-900 font-semibold text-lg">

                            {{ number_format(
                                $recette->montant,
                                2,
                                ',',
                                ' '
                            ) }}

                            {{ $recette->devise }}

                        </p>

                    </div>


                    {{-- Devise --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Devise
                        </p>

                        <p class="mt-1">

                            <span
                                class="inline-flex px-3 py-1
                                       rounded-full
                                       bg-gray-100 text-gray-700
                                       text-sm font-semibold">

                                {{ $recette->devise }}

                            </span>

                        </p>

                    </div>


                    {{-- Utilisateur --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Enregistrée par
                        </p>

                        <p class="mt-1 text-gray-900">

                            {{ $recette->utilisateur->name ?? '-' }}

                        </p>

                    </div>


                    {{-- Identifiant --}}

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            N° de recette
                        </p>

                        <p class="mt-1 text-gray-900">

                            #{{ $recette->id_recette }}

                        </p>

                    </div>

                </div>


                {{-- Description --}}

                <div class="mt-6 pt-6 border-t border-gray-200">

                    <p class="text-sm font-medium text-gray-500 mb-2">
                        Description
                    </p>

                    @if ($recette->description)

                        <div class="bg-gray-50 rounded-lg p-4
                                    text-gray-700">

                            {{ $recette->description }}

                        </div>

                    @else

                        <p class="text-gray-400">
                            Aucune description.
                        </p>

                    @endif

                </div>


                {{-- Actions --}}

                <div class="mt-6 pt-6 border-t border-gray-200
                            flex justify-between items-center">

                    <a href="{{ route('recettes.index') }}"
                       class="px-5 py-2.5 bg-gray-200
                              text-gray-700 rounded-lg
                              hover:bg-gray-300">

                        Retour à la liste

                    </a>


                    @can('update', $recette)

                        <a href="{{ route('recettes.edit', $recette) }}"
                           class="px-5 py-2.5 bg-gray-600
                                  text-white rounded-lg
                                  hover:bg-gray-700">

                            Modifier la recette

                        </a>

                    @endcan

                </div>

            </div>

        </div>

    </div>

</div>

</x-app-layout>
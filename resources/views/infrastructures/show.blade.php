<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Détails de l'infrastructure
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informations détaillées sur l'infrastructure
                </p>

            </div>


            <div class="flex gap-2">

                @can('update', $infrastructure)

                    <a href="{{ route('infrastructures.edit', $infrastructure) }}"
                       class="px-4 py-2
                              bg-gray-600 text-white
                              rounded-lg hover:bg-gray-700">

                        Modifier

                    </a>

                @endcan


                <a href="{{ route('infrastructures.index') }}"
                   class="px-4 py-2
                          bg-gray-200 text-gray-700
                          rounded-lg hover:bg-gray-300">

                    Retour

                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-6 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


            @if(session('success'))

                <div class="mb-5 px-4 py-3
                            bg-green-100 border border-green-300
                            text-green-800 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            <div class="bg-white shadow-sm rounded-xl overflow-hidden">


                {{-- En-tête --}}

                <div class="px-6 py-5 border-b border-gray-200">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-xl font-semibold text-gray-800">

                                {{ $infrastructure->designation }}

                            </h3>

                            @if($infrastructure->type)

                                <p class="text-sm text-gray-500 mt-1">

                                    {{ $infrastructure->type }}

                                </p>

                            @endif

                        </div>


                        {{-- État --}}

                        <div>

                            @switch($infrastructure->etat)

                                @case('BON')

                                    <span class="inline-flex px-3 py-1
                                                 rounded-full
                                                 bg-green-100 text-green-700
                                                 text-sm font-semibold">

                                        BON

                                    </span>

                                    @break


                                @case('MOYEN')

                                    <span class="inline-flex px-3 py-1
                                                 rounded-full
                                                 bg-yellow-100 text-yellow-700
                                                 text-sm font-semibold">

                                        MOYEN

                                    </span>

                                    @break


                                @case('A_REHABILITER')

                                    <span class="inline-flex px-3 py-1
                                                 rounded-full
                                                 bg-orange-100 text-orange-700
                                                 text-sm font-semibold">

                                        À RÉHABILITER

                                    </span>

                                    @break


                                @case('HORS_SERVICE')

                                    <span class="inline-flex px-3 py-1
                                                 rounded-full
                                                 bg-red-100 text-red-700
                                                 text-sm font-semibold">

                                        HORS SERVICE

                                    </span>

                                    @break


                                @default

                                    <span class="inline-flex px-3 py-1
                                                 rounded-full
                                                 bg-gray-100 text-gray-700
                                                 text-sm font-semibold">

                                        {{ $infrastructure->etat }}

                                    </span>

                            @endswitch

                        </div>

                    </div>

                </div>


                {{-- Informations --}}

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- Désignation --}}

                        <div>

                            <p class="text-sm text-gray-500">
                                Désignation
                            </p>

                            <p class="mt-1 font-medium text-gray-900">

                                {{ $infrastructure->designation }}

                            </p>

                        </div>


                        {{-- Type --}}

                        <div>

                            <p class="text-sm text-gray-500">
                                Type
                            </p>

                            <p class="mt-1 font-medium text-gray-900">

                                {{ $infrastructure->type ?? '-' }}

                            </p>

                        </div>


                        {{-- Quantité --}}

                        <div>

                            <p class="text-sm text-gray-500">
                                Quantité
                            </p>

                            <p class="mt-1 font-medium text-gray-900">

                                {{ $infrastructure->quantite }}

                            </p>

                        </div>


                        {{-- Localisation --}}

                        <div>

                            <p class="text-sm text-gray-500">
                                Localisation
                            </p>

                            <p class="mt-1 font-medium text-gray-900">

                                {{ $infrastructure->localisation ?? '-' }}

                            </p>

                        </div>


                        {{-- Établissement --}}

                        @if($infrastructure->etablissement)

                            <div>

                                <p class="text-sm text-gray-500">
                                    Établissement
                                </p>

                                <p class="mt-1 font-medium text-gray-900">

                                    {{ $infrastructure->etablissement->nom }}

                                </p>

                            </div>

                        @endif


                    </div>


                    {{-- Observation --}}

                    <div class="mt-8">

                        <p class="text-sm text-gray-500 mb-2">
                            Observation
                        </p>

                        <div class="bg-gray-50 rounded-lg p-4 text-gray-700">

                            @if($infrastructure->observation)

                                {!! nl2br(e($infrastructure->observation)) !!}

                            @else

                                <span class="text-gray-400">
                                    Aucune observation.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Actions --}}

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">

                    <div class="flex justify-between items-center">


                        <a href="{{ route('infrastructures.index') }}"
                           class="px-4 py-2
                                  bg-white border border-gray-300
                                  text-gray-700 rounded-lg
                                  hover:bg-gray-100">

                            ← Retour à la liste

                        </a>


                        <div class="flex gap-2">

                            @can('update', $infrastructure)

                                <a href="{{ route('infrastructures.edit', $infrastructure) }}"
                                   class="px-4 py-2
                                          bg-gray-600 text-white
                                          rounded-lg hover:bg-gray-700">

                                    Modifier

                                </a>

                            @endcan


                            @can('delete', $infrastructure)

                                <form method="POST"
                                      action="{{ route('infrastructures.destroy', $infrastructure) }}"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette infrastructure ?');">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="px-4 py-2
                                               bg-gray-200 text-gray-700
                                               rounded-lg hover:bg-gray-300">

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

</x-app-layout>
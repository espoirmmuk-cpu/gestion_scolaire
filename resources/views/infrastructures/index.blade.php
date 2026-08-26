<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestion des infrastructures
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des bâtiments, équipements et infrastructures de l'établissement
                </p>

            </div>


            @can('create', App\Models\Infrastructure::class)

                <a href="{{ route('infrastructures.create') }}"
                   class="inline-flex items-center px-5 py-2.5
                          bg-gray-600 text-white rounded-lg
                          hover:bg-gray-700 transition">

                    <span class="mr-2 text-lg">+</span>

                    Nouvelle infrastructure

                </a>

            @endcan

        </div>

    </x-slot>


    <div class="py-6 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- MESSAGES --}}
            {{-- ========================================================= --}}

            @if(session('success'))

                <div class="mb-5 px-4 py-3
                            bg-green-100 border border-green-300
                            text-green-800 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="mb-5 px-4 py-3
                            bg-red-100 border border-red-300
                            text-red-800 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            @if($errors->any())

                <div class="mb-5 px-4 py-3
                            bg-red-100 border border-red-300
                            text-red-800 rounded-lg">

                    <div class="font-semibold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </div>

                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- ========================================================= --}}
            {{-- RECHERCHE ET FILTRES --}}
            {{-- ========================================================= --}}

            <div class="bg-white shadow-sm rounded-xl mb-6">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Rechercher une infrastructure
                    </h3>


                    <form method="GET"
                          action="{{ route('infrastructures.index') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">


                            {{-- Recherche --}}

                            <div class="lg:col-span-2">

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Recherche
                                </label>

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Désignation, type, localisation..."
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                            </div>


                            {{-- Type --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Type
                                </label>

                                <select
                                    name="type"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500">

                                    <option value="">
                                        Tous les types
                                    </option>

                                    @foreach($types as $type)

                                        <option value="{{ $type }}"
                                            @selected(request('type') == $type)>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- État --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    État
                                </label>

                                <select
                                    name="etat"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500">

                                    <option value="">
                                        Tous les états
                                    </option>

                                    @foreach($etats as $etat)

                                        <option value="{{ $etat }}"
                                            @selected(request('etat') == $etat)>

                                            @switch($etat)

                                                @case('BON')
                                                    Bon
                                                    @break

                                                @case('MOYEN')
                                                    Moyen
                                                    @break

                                                @case('A_REHABILITER')
                                                    À réhabiliter
                                                    @break

                                                @case('HORS_SERVICE')
                                                    Hors service
                                                    @break

                                                @default
                                                    {{ $etat }}

                                            @endswitch

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Boutons --}}

                        <div class="mt-5 flex flex-wrap gap-3">

                            <button
                                type="submit"
                                class="px-5 py-2.5
                                       bg-gray-700 text-white
                                       rounded-lg
                                       hover:bg-gray-800 transition">

                                Rechercher

                            </button>


                            @if(request()->hasAny([
                                'search',
                                'type',
                                'etat'
                            ]))

                                <a
                                    href="{{ route('infrastructures.index') }}"
                                    class="px-5 py-2.5
                                           bg-gray-200 text-gray-700
                                           rounded-lg
                                           hover:bg-gray-300 transition">

                                    Réinitialiser

                                </a>

                            @endif

                        </div>

                    </form>

                </div>

            </div>



            {{-- ========================================================= --}}
            {{-- TABLEAU --}}
            {{-- ========================================================= --}}

            <div class="bg-white shadow-sm rounded-xl overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-200">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-800">
                                Liste des infrastructures
                            </h3>

                            <p class="text-sm text-gray-500">

                                {{ $infrastructures->total() }}

                                infrastructure(s)

                            </p>

                        </div>

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs
                                           font-semibold text-gray-600 uppercase">

                                    Désignation

                                </th>


                                <th class="px-6 py-3 text-left text-xs
                                           font-semibold text-gray-600 uppercase">

                                    Type

                                </th>


                                <th class="px-6 py-3 text-left text-xs
                                           font-semibold text-gray-600 uppercase">

                                    Quantité

                                </th>


                                <th class="px-6 py-3 text-left text-xs
                                           font-semibold text-gray-600 uppercase">

                                    État

                                </th>


                                <th class="px-6 py-3 text-left text-xs
                                           font-semibold text-gray-600 uppercase">

                                    Localisation

                                </th>


                                @if(
                                    auth()->user()->id_etablissement === null &&
                                    auth()->user()->aLeRole('Administrateur')
                                )

                                    <th class="px-6 py-3 text-left text-xs
                                               font-semibold text-gray-600 uppercase">

                                        Établissement

                                    </th>

                                @endif


                                <th class="px-6 py-3 text-right text-xs
                                           font-semibold text-gray-600 uppercase">

                                    Actions

                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-200">


                            @forelse($infrastructures as $infrastructure)

                                <tr class="hover:bg-gray-50">


                                    {{-- Désignation --}}

                                    <td class="px-6 py-4">

                                        <div class="text-sm font-semibold text-gray-900">

                                            {{ $infrastructure->designation }}

                                        </div>


                                        @if($infrastructure->observation)

                                            <div class="text-xs text-gray-500 mt-1">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $infrastructure->observation,
                                                    60
                                                ) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="text-sm text-gray-700">

                                            {{ $infrastructure->type ?? '-' }}

                                        </div>

                                    </td>


                                    {{-- Quantité --}}

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span class="text-sm font-semibold text-gray-900">

                                            {{ $infrastructure->quantite }}

                                        </span>

                                    </td>


                                    {{-- État --}}

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @switch($infrastructure->etat)

                                            @case('BON')

                                                <span class="inline-flex px-2.5 py-1
                                                             rounded-full
                                                             bg-green-100 text-green-700
                                                             text-xs font-semibold">

                                                    BON

                                                </span>

                                                @break


                                            @case('MOYEN')

                                                <span class="inline-flex px-2.5 py-1
                                                             rounded-full
                                                             bg-yellow-100 text-yellow-700
                                                             text-xs font-semibold">

                                                    MOYEN

                                                </span>

                                                @break


                                            @case('A_REHABILITER')

                                                <span class="inline-flex px-2.5 py-1
                                                             rounded-full
                                                             bg-orange-100 text-orange-700
                                                             text-xs font-semibold">

                                                    À RÉHABILITER

                                                </span>

                                                @break


                                            @case('HORS_SERVICE')

                                                <span class="inline-flex px-2.5 py-1
                                                             rounded-full
                                                             bg-red-100 text-red-700
                                                             text-xs font-semibold">

                                                    HORS SERVICE

                                                </span>

                                                @break


                                            @default

                                                <span class="inline-flex px-2.5 py-1
                                                             rounded-full
                                                             bg-gray-100 text-gray-700
                                                             text-xs font-semibold">

                                                    {{ $infrastructure->etat }}

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Localisation --}}

                                    <td class="px-6 py-4">

                                        <div class="text-sm text-gray-700">

                                            {{ $infrastructure->localisation ?? '-' }}

                                        </div>

                                    </td>


                                    {{-- Établissement --}}

                                    @if(
                                        auth()->user()->id_etablissement === null &&
                                        auth()->user()->aLeRole('Administrateur')
                                    )

                                        <td class="px-6 py-4">

                                            <div class="text-sm text-gray-700">

                                                {{ $infrastructure->etablissement->nom ?? '-' }}

                                            </div>

                                        </td>

                                    @endif


                                    {{-- Actions --}}

                                    <td class="px-6 py-4 whitespace-nowrap text-right">

                                        <div class="flex justify-end gap-2">


                                            @can('view', $infrastructure)

                                                <a
                                                    href="{{ route(
                                                        'infrastructures.show',
                                                        $infrastructure
                                                    ) }}"
                                                    class="px-3 py-1.5
                                                           bg-gray-100 text-gray-700
                                                           rounded-lg
                                                           hover:bg-gray-200
                                                           text-sm">

                                                    Voir

                                                </a>

                                            @endcan


                                            @can('update', $infrastructure)

                                                <a
                                                    href="{{ route(
                                                        'infrastructures.edit',
                                                        $infrastructure
                                                    ) }}"
                                                    class="px-3 py-1.5
                                                           bg-gray-600 text-white
                                                           rounded-lg
                                                           hover:bg-gray-700
                                                           text-sm">

                                                    Modifier

                                                </a>

                                            @endcan


                                            @can('delete', $infrastructure)

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'infrastructures.destroy',
                                                        $infrastructure
                                                    ) }}"
                                                    onsubmit="return confirm(
                                                        'Voulez-vous vraiment supprimer cette infrastructure ?'
                                                    );">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="px-3 py-1.5
                                                               bg-gray-200 text-gray-700
                                                               rounded-lg
                                                               hover:bg-gray-300
                                                               text-sm">

                                                        Supprimer

                                                    </button>

                                                </form>

                                            @endcan

                                        </div>

                                    </td>


                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="{{
                                            (
                                                auth()->user()->id_etablissement === null &&
                                                auth()->user()->aLeRole('Administrateur')
                                            )
                                            ? 7
                                            : 6
                                        }}"
                                        class="px-6 py-12 text-center">

                                        <div class="text-gray-400 text-4xl mb-3">
                                            🏫
                                        </div>

                                        <p class="text-gray-600 font-medium">

                                            Aucune infrastructure trouvée.

                                        </p>

                                        <p class="text-sm text-gray-400 mt-1">

                                            Commencez par enregistrer une nouvelle infrastructure.

                                        </p>


                                        @can('create', App\Models\Infrastructure::class)

                                            <a
                                                href="{{ route('infrastructures.create') }}"
                                                class="inline-block mt-5
                                                       px-5 py-2.5
                                                       bg-gray-600 text-white
                                                       rounded-lg
                                                       hover:bg-gray-700">

                                                Ajouter une infrastructure

                                            </a>

                                        @endcan

                                    </td>

                                </tr>

                            @endforelse


                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}

                @if($infrastructures->hasPages())

                    <div class="px-6 py-4 border-t border-gray-200">

                        {{ $infrastructures->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
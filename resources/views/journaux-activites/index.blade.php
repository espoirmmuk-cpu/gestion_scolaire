<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Journal des activités
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Historique des actions effectuées dans l'application
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg
                      hover:bg-gray-700 transition">

                Retour au tableau de bord

            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- MESSAGES --}}
            {{-- ========================================================= --}}

            @if(session('success'))

                <div class="mb-6 p-4 bg-green-100 border border-green-300
                            text-green-800 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="mb-6 p-4 bg-red-100 border border-red-300
                            text-red-800 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- FILTRES --}}
            {{-- ========================================================= --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-800">
                                Rechercher dans le journal
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Utilisez les filtres pour retrouver une activité.
                            </p>

                        </div>

                    </div>


                    <form method="GET"
                          action="{{ route('journaux-activites.index') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2
                                    lg:grid-cols-3 xl:grid-cols-6 gap-4">


                            {{-- Action --}}

                            <div>

                                <label for="action"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Action

                                </label>

                                <input
                                    type="text"
                                    name="action"
                                    id="action"
                                    value="{{ request('action') }}"
                                    placeholder="Ex. Modification"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                            </div>


                            {{-- Table --}}

                            <div>

                                <label for="table_concernee"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Élément

                                </label>

                                <select
                                    name="table_concernee"
                                    id="table_concernee"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                                    <option value="">
                                        Toutes
                                    </option>

                                    @foreach($tables as $table)

                                        <option
                                            value="{{ $table }}"
                                            @selected(request('table_concernee') == $table)
                                        >

                                            {{ ucfirst(str_replace('_', ' ', $table)) }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Utilisateur --}}

                            <div>

                                <label for="id_utilisateur"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Utilisateur

                                </label>

                                <select
                                    name="id_utilisateur"
                                    id="id_utilisateur"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                                    <option value="">
                                        Tous
                                    </option>

                                    @foreach($utilisateurs as $utilisateur)

                                        <option
                                            value="{{ $utilisateur->id }}"
                                            @selected(
                                                request('id_utilisateur') == $utilisateur->id
                                            )
                                        >

                                            {{ $utilisateur->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Date début --}}

                            <div>

                                <label for="date_debut"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Du

                                </label>

                                <input
                                    type="date"
                                    name="date_debut"
                                    id="date_debut"
                                    value="{{ request('date_debut') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                            </div>


                            {{-- Date fin --}}

                            <div>

                                <label for="date_fin"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Au

                                </label>

                                <input
                                    type="date"
                                    name="date_fin"
                                    id="date_fin"
                                    value="{{ request('date_fin') }}"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                            </div>


                            {{-- Boutons --}}

                            <div class="flex items-end gap-2">

                                <button
                                    type="submit"
                                    class="flex-1 px-4 py-2 bg-gray-600
                                           text-white rounded-lg
                                           hover:bg-gray-700 transition"
                                >

                                    Rechercher

                                </button>


                                <a
                                    href="{{ route('journaux-activites.index') }}"
                                    class="px-4 py-2 bg-gray-200
                                           text-gray-700 rounded-lg
                                           hover:bg-gray-300 transition"
                                >

                                    Réinitialiser

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- TABLEAU --}}
            {{-- ========================================================= --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">


                    {{-- En-tête --}}

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="text-xl font-bold text-gray-800">
                                Activités enregistrées
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                {{ $activites->total() }}
                                activité(s) enregistrée(s)

                            </p>

                        </div>

                    </div>


                    @if($activites->count() > 0)


                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>

                                        <th class="px-4 py-3 text-left text-xs
                                                   font-semibold text-gray-600 uppercase">

                                            Date / Heure

                                        </th>


                                        <th class="px-4 py-3 text-left text-xs
                                                   font-semibold text-gray-600 uppercase">

                                            Utilisateur

                                        </th>


                                        <th class="px-4 py-3 text-left text-xs
                                                   font-semibold text-gray-600 uppercase">

                                            Action

                                        </th>


                                        <th class="px-4 py-3 text-left text-xs
                                                   font-semibold text-gray-600 uppercase">

                                            Élément

                                        </th>


                                        <th class="px-4 py-3 text-left text-xs
                                                   font-semibold text-gray-600 uppercase">

                                            Enregistrement

                                        </th>


                                        <th class="px-4 py-3 text-left text-xs
                                                   font-semibold text-gray-600 uppercase">

                                            Adresse IP

                                        </th>


                                        <th class="px-4 py-3 text-center text-xs
                                                   font-semibold text-gray-600 uppercase">

                                            Détails

                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="bg-white divide-y divide-gray-200">


                                    @foreach($activites as $activite)

                                        <tr class="hover:bg-gray-50">


                                            {{-- Date / Heure --}}

                                            <td class="px-4 py-4 whitespace-nowrap">

                                                <p class="text-sm font-semibold text-gray-800">

                                                    @if($activite->date_heure)

                                                        {{ $activite->date_heure->format('d/m/Y') }}

                                                    @else

                                                        —

                                                    @endif

                                                </p>

                                                <p class="text-xs text-gray-500">

                                                    @if($activite->date_heure)

                                                        {{ $activite->date_heure->format('H:i:s') }}

                                                    @endif

                                                </p>

                                            </td>


                                            {{-- Utilisateur --}}

                                            <td class="px-4 py-4">

                                                @if($activite->utilisateur)

                                                    <p class="text-sm font-semibold text-gray-800">

                                                        {{ $activite->utilisateur->name }}

                                                    </p>

                                                    @if($activite->utilisateur->email)

                                                        <p class="text-xs text-gray-500">

                                                            {{ $activite->utilisateur->email }}

                                                        </p>

                                                    @endif

                                                @else

                                                    <span class="text-sm text-gray-400">

                                                        Utilisateur inconnu

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Action --}}

                                            <td class="px-4 py-4">

                                                @php

                                                    $action = strtolower(
                                                        $activite->action ?? ''
                                                    );

                                                @endphp


                                                @if(str_contains($action, 'suppression'))

                                                    <span class="inline-flex items-center
                                                                 px-2.5 py-1 rounded-full
                                                                 text-xs font-semibold
                                                                 bg-red-100 text-red-800">

                                                        {{ $activite->action }}

                                                    </span>

                                                @elseif(str_contains($action, 'modification'))

                                                    <span class="inline-flex items-center
                                                                 px-2.5 py-1 rounded-full
                                                                 text-xs font-semibold
                                                                 bg-yellow-100 text-yellow-800">

                                                        {{ $activite->action }}

                                                    </span>

                                                @elseif(str_contains($action, 'ajout'))

                                                    <span class="inline-flex items-center
                                                                 px-2.5 py-1 rounded-full
                                                                 text-xs font-semibold
                                                                 bg-green-100 text-green-800">

                                                        {{ $activite->action }}

                                                    </span>

                                                @else

                                                    <span class="inline-flex items-center
                                                                 px-2.5 py-1 rounded-full
                                                                 text-xs font-semibold
                                                                 bg-gray-100 text-gray-800">

                                                        {{ $activite->action ?? '—' }}

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Élément --}}

                                            <td class="px-4 py-4">

                                                <span class="text-sm text-gray-700">

                                                    {{ ucfirst(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $activite->table_concernee
                                                        )
                                                    ) }}

                                                </span>

                                            </td>


                                            {{-- ID enregistrement --}}

                                            <td class="px-4 py-4">

                                                <span class="inline-flex items-center
                                                             px-2.5 py-1 rounded-lg
                                                             bg-gray-100 text-gray-700
                                                             text-sm font-medium">

                                                    #{{ $activite->id_enregistrement }}

                                                </span>

                                            </td>


                                            {{-- Adresse IP --}}

                                            <td class="px-4 py-4">

                                                <span class="text-sm text-gray-600">

                                                    {{ $activite->adresse_ip ?? '—' }}

                                                </span>

                                            </td>


                                            {{-- Détails --}}

                                            <td class="px-4 py-4 text-center">

                                                <a
                                                    href="{{ route(
                                                        'journaux-activites.show',
                                                        $activite->id_journal
                                                    ) }}"
                                                    class="inline-flex items-center
                                                           px-3 py-2 bg-gray-600
                                                           text-white rounded-lg
                                                           hover:bg-gray-700
                                                           text-sm transition"
                                                >

                                                    Voir

                                                </a>

                                            </td>


                                        </tr>

                                    @endforeach


                                </tbody>

                            </table>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAGINATION --}}
                        {{-- ================================================= --}}

                        <div class="mt-6">

                            {{ $activites->links() }}

                        </div>


                    @else


                        <div class="text-center py-12">

                            <div class="text-gray-400 mb-3">

                                <svg
                                    class="mx-auto h-12 w-12"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"
                                    />

                                </svg>

                            </div>


                            <h4 class="text-lg font-semibold text-gray-700">

                                Aucune activité trouvée

                            </h4>


                            <p class="text-sm text-gray-500 mt-1">

                                Aucune activité ne correspond aux critères sélectionnés.

                            </p>


                        </div>


                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
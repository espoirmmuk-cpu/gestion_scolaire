<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Gestion des présences
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Suivi des présences, absences et autres statuts des élèves.
                </p>
            </div>

            @can('create', App\Models\Presence::class)

                <a
                    href="{{ route('presences.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Ajouter une présence

                </a>

            @endcan

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ========================================================= --}}
            {{-- MESSAGES --}}
            {{-- ========================================================= --}}

            @if (session('success'))

                <div
                    class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
                >
                    <div class="flex items-center gap-2">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>
                </div>

            @endif


            @if (session('error'))

                <div
                    class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                >
                    <div class="flex items-center gap-2">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>
                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- FILTRES --}}
            {{-- ========================================================= --}}

            <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">

                    <h3 class="text-base font-semibold text-gray-800">
                        Filtrer les présences
                    </h3>

                </div>


                <form
                    method="GET"
                    action="{{ route('presences.index') }}"
                    class="p-6"
                >

                    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">


                        {{-- Élève --}}
                        <div>

                            <label
                                for="id_eleve"
                                class="mb-1.5 block text-sm font-medium text-gray-700"
                            >
                                Élève
                            </label>

                            <select
                                name="id_eleve"
                                id="id_eleve"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    Tous les élèves
                                </option>

                                @foreach ($eleves as $eleve)

                                    <option
                                        value="{{ $eleve->id_eleve }}"
                                        @selected(request('id_eleve') == $eleve->id_eleve)
                                    >
                                        {{ trim($eleve->nom . ' ' . ($eleve->postnom ?? '') . ' ' . ($eleve->prenom ?? '')) }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Classe --}}
                        <div>

                            <label
                                for="id_classe"
                                class="mb-1.5 block text-sm font-medium text-gray-700"
                            >
                                Classe
                            </label>

                            <select
                                name="id_classe"
                                id="id_classe"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    Toutes les classes
                                </option>

                                @foreach ($classes as $classe)

                                    <option
                                        value="{{ $classe->id_classe }}"
                                        @selected(request('id_classe') == $classe->id_classe)
                                    >
                                        {{ $classe->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Date --}}
                        <div>

                            <label
                                for="date_presence"
                                class="mb-1.5 block text-sm font-medium text-gray-700"
                            >
                                Date
                            </label>

                            <input
                                type="date"
                                name="date_presence"
                                id="date_presence"
                                value="{{ request('date_presence') }}"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- Statut --}}
                        <div>

                            <label
                                for="statut"
                                class="mb-1.5 block text-sm font-medium text-gray-700"
                            >
                                Statut
                            </label>

                            <select
                                name="statut"
                                id="statut"
                                class="block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    Tous les statuts
                                </option>

                                <option
                                    value="PRESENT"
                                    @selected(request('statut') === 'PRESENT')
                                >
                                    Présent
                                </option>

                                <option
                                    value="ABSENT"
                                    @selected(request('statut') === 'ABSENT')
                                >
                                    Absent
                                </option>

                                <option
                                    value="RETARD"
                                    @selected(request('statut') === 'RETARD')
                                >
                                    Retard
                                </option>

                                <option
                                    value="JUSTIFIE"
                                    @selected(request('statut') === 'JUSTIFIE')
                                >
                                    Justifié
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="mt-5 flex flex-wrap items-center gap-3">

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m2.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>

                            Rechercher

                        </button>


                        @if (
                            request()->filled('id_eleve') ||
                            request()->filled('id_classe') ||
                            request()->filled('date_presence') ||
                            request()->filled('statut')
                        )

                            <a
                                href="{{ route('presences.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-600 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-600"
                            >

                                Réinitialiser

                            </a>

                        @endif

                    </div>

                </form>

            </div>


            {{-- ========================================================= --}}
            {{-- TABLEAU --}}
            {{-- ========================================================= --}}

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-6 py-4">

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <h3 class="text-base font-semibold text-gray-800">
                                Liste des présences
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $presences->total() }}
                                {{ $presences->total() > 1 ? 'enregistrements' : 'enregistrement' }}
                            </p>

                        </div>

                    </div>

                </div>


                @if ($presences->count() > 0)

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Date
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Élève
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Classe
                                    </th>

                                    <th
                                        class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Statut
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Motif
                                    </th>

                                    <th
                                        class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500"
                                    >
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200 bg-white">

                                @foreach ($presences as $presence)

                                    <tr class="transition hover:bg-gray-50">

                                        {{-- Date --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">

                                            {{ $presence->date_presence?->format('d/m/Y') }}

                                        </td>


                                        {{-- Élève --}}
                                        <td class="px-6 py-4">

                                            @if ($presence->eleve)

                                                <div class="font-medium text-gray-900">

                                                    {{ trim(
                                                        $presence->eleve->nom . ' ' .
                                                        ($presence->eleve->postnom ?? '') . ' ' .
                                                        ($presence->eleve->prenom ?? '')
                                                    ) }}

                                                </div>

                                                @if ($presence->eleve->matricule)

                                                    <div class="mt-1 text-xs text-gray-500">

                                                        {{ $presence->eleve->matricule }}

                                                    </div>

                                                @endif

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    Élève introuvable
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Classe --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">

                                            {{ $presence->classe?->libelle ?? 'Classe introuvable' }}

                                        </td>


                                        {{-- Statut --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-center">

                                            @php

                                                $statut = strtoupper($presence->statut ?? '');

                                                $classesStatut = match ($statut) {

                                                    'PRESENT', 'PRÉSENT' =>
                                                        'bg-green-100 text-green-800',

                                                    'ABSENT' =>
                                                        'bg-red-100 text-red-800',

                                                    'RETARD' =>
                                                        'bg-yellow-100 text-yellow-800',

                                                    'JUSTIFIE', 'JUSTIFIÉ' =>
                                                        'bg-blue-100 text-blue-800',

                                                    default =>
                                                        'bg-gray-100 text-gray-800',
                                                };

                                                $libelleStatut = match ($statut) {

                                                    'PRESENT', 'PRÉSENT' =>
                                                        'Présent',

                                                    'ABSENT' =>
                                                        'Absent',

                                                    'RETARD' =>
                                                        'Retard',

                                                    'JUSTIFIE', 'JUSTIFIÉ' =>
                                                        'Justifié',

                                                    default =>
                                                        $presence->statut ?? 'Non défini',
                                                };

                                            @endphp

                                            <span
                                                class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $classesStatut }}"
                                            >
                                                {{ $libelleStatut }}
                                            </span>

                                        </td>


                                        {{-- Motif --}}
                                        <td class="max-w-xs px-6 py-4 text-sm text-gray-600">

                                            @if ($presence->motif)

                                                <span
                                                    class="line-clamp-2"
                                                    title="{{ $presence->motif }}"
                                                >
                                                    {{ $presence->motif }}
                                                </span>

                                            @else

                                                <span class="text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-right">

                                            <div class="flex items-center justify-end gap-2">


                                                @can('view', $presence)

                                                    <a
                                                        href="{{ route('presences.show', $presence) }}"
                                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-600 transition hover:bg-gray-50 hover:text-indigo-600"
                                                        title="Voir"
                                                    >

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                            />

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                            />
                                                        </svg>

                                                    </a>

                                                @endcan


                                                @can('update', $presence)

                                                    <a
                                                        href="{{ route('presences.edit', $presence) }}"
                                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-600 transition hover:bg-gray-50 hover:text-indigo-600"
                                                        title="Modifier"
                                                    >

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"
                                                            />

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                                                            />

                                                        </svg>

                                                    </a>

                                                @endcan


                                                @can('delete', $presence)

                                                    <form
                                                        action="{{ route('presences.destroy', $presence) }}"
                                                        method="POST"
                                                        class="inline"
                                                        onsubmit="return confirm('Voulez-vous vraiment supprimer cette présence ?');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white p-2 text-red-600 transition hover:bg-red-50"
                                                            title="Supprimer"
                                                        >

                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"
                                                                />

                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M10 11v6M14 11v6M4 7h16M9 7V4h6v3"
                                                                />

                                                            </svg>

                                                        </button>

                                                    </form>

                                                @endcan

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}

                    @if ($presences->hasPages())

                        <div class="border-t border-gray-200 px-6 py-4">

                            {{ $presences->links() }}

                        </div>

                    @endif


                @else

                    {{-- Aucun résultat --}}

                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14 3v5h5"
                                />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-lg font-semibold text-gray-900">
                            Aucune présence trouvée
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                            Aucune présence ne correspond aux critères sélectionnés.
                        </p>


                        @can('create', App\Models\Presence::class)

                            <a
                                href="{{ route('presences.create') }}"
                                class="mt-6 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>

                                Ajouter une présence

                            </a>

                        @endcan

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
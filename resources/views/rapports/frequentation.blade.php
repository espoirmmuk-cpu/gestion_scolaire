<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Fiche de fréquentation
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Consultez les présences et absences des élèves.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ================================================= --}}
            {{-- FILTRES --}}
            {{-- ================================================= --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                <div class="flex items-center mb-6">

                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">

                        <span class="text-2xl">
                            🕐
                        </span>

                    </div>

                    <div class="ml-4">

                        <h1 class="text-xl font-bold text-gray-800">
                            Fiche de fréquentation
                        </h1>

                        <p class="text-sm text-gray-500">
                            Sélectionnez l'année, la période et la classe.
                        </p>

                    </div>

                </div>


                <form
                    method="GET"
                    action="{{ route('rapports.frequentation') }}"
                >

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                        {{-- Année --}}
                        <div>

                            <label
                                for="annee"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Année scolaire
                            </label>

                            <select
                                id="annee"
                                name="annee"
                                onchange="this.form.submit()"
                                class="w-full rounded-lg border-gray-300"
                            >

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                @foreach($annees as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        {{ request('annee') == $annee->id_annee_scolaire ? 'selected' : '' }}
                                    >
                                        {{ $annee->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Période --}}
                        <div>

                            <label
                                for="periode"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Période
                            </label>

                            <select
                                id="periode"
                                name="periode"
                                class="w-full rounded-lg border-gray-300"
                            >

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                @foreach($periodes as $periode)

                                    <option
                                        value="{{ $periode->id_periode }}"
                                        {{ request('periode') == $periode->id_periode ? 'selected' : '' }}
                                    >
                                        {{ $periode->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Classe --}}
                        <div>

                            <label
                                for="classe"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Classe
                            </label>

                            <select
                                id="classe"
                                name="classe"
                                class="w-full rounded-lg border-gray-300"
                            >

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                @foreach($classes as $classe)

                                    <option
                                        value="{{ $classe->id_classe }}"
                                        {{ request('classe') == $classe->id_classe ? 'selected' : '' }}
                                    >
                                        {{ $classe->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                   <div class="mt-6 flex justify-between items-center">

                        <a
                            href="{{ route('rapports.index') }}"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                        >
                            ← Retour aux rapports
                        </a>


                        <div class="flex gap-3">

                            {{-- Afficher --}}
                            <button
                                type="submit"
                                class="px-6 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                            >
                                🔎 Afficher
                            </button>

                        </div>

                    </div>

                </form>

                @if(
                        request()->filled('annee') &&
                        request()->filled('periode') &&
                        request()->filled('classe')
                    )

                        <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end gap-3">

                            {{-- PDF --}}
                            <a
                                href="{{ route('rapports.frequentation.pdf', request()->query()) }}"
                                class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700"
                            >
                                📄 PDF
                            </a>


                            {{-- Imprimer --}}
                            <a
                                href="{{ route('rapports.frequentation.imprimer', request()->query()) }}"
                                target="_blank"
                                class="inline-flex items-center px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                            >
                                🖨️ Imprimer
                            </a>

                        </div>

                    @endif

            </div>


            {{-- ================================================= --}}
            {{-- RÉSULTATS --}}
            {{-- ================================================= --}}

            @if($frequentation->count() > 0)

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-6 overflow-hidden">


                    <div class="p-6 border-b border-gray-200">

                        <h2 class="text-lg font-bold text-gray-800">
                            Fréquentation des élèves
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">

                            {{ $anneeSelectionnee->libelle ?? '' }}

                            @if($periodeSelectionnee)
                                — {{ $periodeSelectionnee->libelle }}
                            @endif

                            @if($classeSelectionnee)
                                — {{ $classeSelectionnee->libelle }}
                            @endif

                        </p>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        N°
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Matricule
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Élève
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                        Jours
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                        Présences
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                        Absences
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                        Taux
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($frequentation as $index => $eleve)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $eleve->matricule }}
                                        </td>

                                        <td class="px-6 py-4 text-sm font-medium text-gray-800">

                                            {{ $eleve->nom }}
                                            {{ $eleve->postnom }}
                                            {{ $eleve->prenom }}

                                        </td>

                                        <td class="px-6 py-4 text-sm text-center">
                                            {{ $eleve->total_jours }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-center">
                                            {{ $eleve->presents }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-center">
                                            {{ $eleve->absents }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-center font-semibold">
                                            {{ number_format($eleve->taux, 2, ',', ' ') }} %
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @elseif(
                request()->filled('annee') &&
                request()->filled('periode') &&
                request()->filled('classe')
            )

                <div class="mt-6 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-5">

                    Aucune donnée de fréquentation trouvée pour les critères sélectionnés.

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
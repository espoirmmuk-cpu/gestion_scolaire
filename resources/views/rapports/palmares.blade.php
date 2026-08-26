<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Palmarès des élèves
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Classement des élèves selon leurs résultats scolaires
            </p>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- En-tête --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <div class="flex items-center justify-between">

                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            🏆 Palmarès des élèves
                        </h1>

                        <p class="text-gray-500 mt-1">
                            Sélectionnez l'année scolaire, la période et la classe.
                        </p>
                    </div>

                    <a href="{{ route('rapports.index') }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        ← Retour aux rapports
                    </a>

                </div>

            </div>


            {{-- Formulaire de recherche --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <form method="GET"
                      action="{{ route('rapports.palmares') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                        {{-- Année scolaire --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Année scolaire
                            </label>

                            <select
                                name="annee"
                                id="annee"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                                onchange="this.form.submit()">

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                @foreach($annees as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        {{ request('annee') == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                        {{ $annee->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Période --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Période scolaire
                            </label>

                            <select
                                name="periode"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                @foreach($periodes as $periode)

                                    <option
                                        value="{{ $periode->id_periode }}"
                                        {{ request('periode') == $periode->id_periode ? 'selected' : '' }}>

                                        {{ $periode->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Classe --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Classe
                            </label>

                            <select
                                name="classe"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                @foreach($classes as $classe)

                                    <option
                                        value="{{ $classe->id_classe }}"
                                        {{ request('classe') == $classe->id_classe ? 'selected' : '' }}>

                                        {{ $classe->libelle }}

                                        @if($classe->option_classe)
                                            — {{ $classe->option_classe }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    <div class="mt-6 flex justify-end">

                        <button
                            type="submit"
                            class="px-6 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-medium">

                            🔎 Afficher le palmarès

                        </button>

                    </div>

                    <div class="flex gap-3">

                        <a
                            href="{{ route('rapports.palmares.pdf', request()->query()) }}"
                            class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                            📄 PDF
                        </a>

                        <a
                            href="{{ route('rapports.palmares.excel', request()->query()) }}"
                            class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                            📊 Excel
                        </a>

                    </div>

                </form>

            </div>


            {{-- Informations de sélection --}}
            @if($palmares->count() > 0)

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>
                            <p class="text-sm text-gray-500">
                                Année scolaire
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $anneeSelectionnee->libelle ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Période
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $periodeSelectionnee->libelle ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Classe
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $classeSelectionnee->libelle ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Résultats --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                    <div class="px-6 py-5 border-b border-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="text-lg font-semibold text-gray-800">
                                    Palmarès
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $palmares->count() }}
                                    élève(s) classé(s)
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Rang
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Matricule
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Élève
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                        Sexe
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                                        Moyenne
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                                        Pourcentage
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                        Décision
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($palmares as $eleve)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <span class="font-bold text-gray-800">
                                                {{ $eleve->rang ?? '-' }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">

                                            {{ $eleve->matricule }}

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="font-medium text-gray-900">

                                                {{ $eleve->nom }}
                                                {{ $eleve->postnom }}
                                                {{ $eleve->prenom }}

                                            </div>

                                        </td>


                                        <td class="px-6 py-4 text-center text-sm text-gray-600">

                                            {{ $eleve->sexe }}

                                        </td>


                                        <td class="px-6 py-4 text-right font-semibold text-gray-800">

                                            {{ number_format((float) $eleve->moyenne, 2, ',', ' ') }}

                                        </td>


                                        <td class="px-6 py-4 text-right font-semibold text-gray-800">

                                            {{ number_format((float) $eleve->pourcentage, 2, ',', ' ') }} %

                                        </td>


                                        <td class="px-6 py-4 text-center">

                                            @if($eleve->decision)

                                                <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">

                                                    {{ $eleve->decision }}

                                                </span>

                                            @else

                                                <span class="text-gray-400">
                                                    -
                                                </span>

                                            @endif

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

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">

                    <div class="text-5xl mb-4">
                        📋
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Aucun bulletin trouvé
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Aucun résultat ne correspond aux critères sélectionnés.
                    </p>

                </div>

            @else

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">

                    <div class="text-5xl mb-4">
                        🏆
                    </div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Aucun palmarès sélectionné
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Sélectionnez une année scolaire, une période
                        et une classe pour afficher le classement.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
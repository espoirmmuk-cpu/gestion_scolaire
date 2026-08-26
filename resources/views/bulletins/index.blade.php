<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Bulletins scolaires
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Consulter les bulletins des élèves
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                Retour

            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- ===================================================== --}}
            {{-- FILTRES --}}
            {{-- ===================================================== --}}

            <div class="bg-white rounded-xl shadow-sm mb-6">

                <div class="p-6">

                    <div class="mb-5">

                        <h3 class="text-lg font-bold text-gray-800">
                            Sélection du bulletin
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Sélectionnez l'année scolaire et la classe.
                        </p>

                    </div>


                    <form method="GET"
                          action="{{ route('bulletins.index') }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                            {{-- Année scolaire --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">

                                    Année scolaire

                                </label>

                                <select name="id_annee_scolaire"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500
                                               focus:ring-gray-500"
                                        required>

                                    <option value="">
                                        -- Sélectionner --
                                    </option>

                                    @foreach($anneesScolaires as $annee)

                                        <option value="{{ $annee->id_annee_scolaire }}"
                                            {{ request('id_annee_scolaire') == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                            {{ $annee->libelle }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Période --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-2">

                                    Période

                                </label>

                                <select name="id_periode"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500
                                               focus:ring-gray-500"
                                        required>

                                    <option value="">
                                        -- Sélectionner --
                                    </option>

                                    @foreach($periodes as $periode)

                                        <option value="{{ $periode->id_periode }}"
                                            {{ request('id_periode') == $periode->id_periode ? 'selected' : '' }}>

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

                                <select name="id_classe"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500
                                               focus:ring-gray-500"
                                        required>

                                    <option value="">
                                        -- Sélectionner --
                                    </option>

                                    @foreach($classes as $classe)

                                        <option value="{{ $classe->id_classe }}"
                                            {{ request('id_classe') == $classe->id_classe ? 'selected' : '' }}>

                                            {{ $classe->libelle }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <div class="mt-5 flex justify-end">

                            <button type="submit"
                                    class="px-5 py-2.5 bg-gray-600 text-white
                                           rounded-lg hover:bg-gray-800
                                           transition">

                                🔎 Rechercher les élèves

                            </button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- LISTE DES ÉLÈVES --}}
            {{-- ===================================================== --}}

            @if(request()->filled('id_annee_scolaire') &&
                request()->filled('id_classe'))

                <div class="bg-white rounded-xl shadow-sm">

                    <div class="p-6">


                        <div class="flex items-center justify-between mb-5">

                            <div>

                                <h3 class="text-lg font-bold text-gray-800">

                                    Élèves

                                </h3>

                                <p class="text-sm text-gray-500 mt-1">

                                    Sélectionnez un élève pour consulter son bulletin.

                                </p>

                            </div>


                            <div class="text-sm text-gray-500">

                                {{ $eleves->count() }} élève(s)

                            </div>

                        </div>


                        @if($eleves->count() > 0)

                            <div class="overflow-x-auto">

                                <table class="min-w-full divide-y divide-gray-200">

                                    <thead class="bg-gray-50">

                                        <tr>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">

                                                #

                                            </th>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">

                                                Matricule

                                            </th>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">

                                                Élève

                                            </th>

                                            <th class="px-4 py-3 text-center text-xs
                                                       font-semibold text-gray-600 uppercase">

                                                Action

                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody class="bg-white divide-y divide-gray-200">

                                        @foreach($eleves as $index => $eleve)

                                            <tr class="hover:bg-gray-50">

                                                <td class="px-4 py-4 text-sm text-gray-500">

                                                    {{ $index + 1 }}

                                                </td>


                                                <td class="px-4 py-4 text-sm text-gray-700">

                                                    {{ $eleve->matricule ?? '—' }}

                                                </td>


                                                <td class="px-4 py-4">

                                                    <div class="font-semibold text-gray-800">

                                                        {{ $eleve->nom }}
                                                        {{ $eleve->postnom }}
                                                        {{ $eleve->prenom }}

                                                    </div>

                                                </td>


                                                <td class="px-4 py-4 text-center">

                                                   <a href="{{ route('bulletins.show', [
                                                            'eleve' => $eleve->id_eleve,
                                                            'id_annee_scolaire' => request('id_annee_scolaire'),
                                                            'id_periode' => request('id_periode'),
                                                            'id_classe' => request('id_classe'),
                                                        ]) }}"
                                                        class="inline-flex items-center px-4 py-2
                                                            bg-gray-600 text-white rounded-lg
                                                            hover:bg-gray-800 text-sm">

                                                        Voir le bulletin

                                                    </a>

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="text-center py-10">

                                <div class="text-4xl mb-3">
                                    📄
                                </div>

                                <p class="text-gray-500">

                                    Aucun élève inscrit dans cette classe
                                    pour cette année scolaire.

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
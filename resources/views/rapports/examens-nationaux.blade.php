<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="font-semibold text-xl text-gray-800">
                Statistiques des examens nationaux
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Analyse statistique des résultats des examens.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- FILTRE ANNÉE --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <form
                    method="GET"
                    action="{{ route('rapports.examens-nationaux') }}"
                >

                    <div class="flex flex-col md:flex-row gap-4 items-end">

                        <div class="flex-1">

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Année scolaire
                            </label>

                            <select
                                name="annee"
                                class="w-full rounded-lg border-gray-300"
                            >

                                @foreach($annees as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        @selected($anneeId == $annee->id_annee_scolaire)
                                    >
                                        {{ $annee->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <button
                            type="submit"
                            class="px-6 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                        >
                            🔎 Afficher
                        </button>

                    </div>

                </form>

            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">

                <div class="flex justify-end gap-3 flex-wrap">

                    <a
                        href="{{ route('rapports.examens-nationaux.pdf', request()->query()) }}"
                        class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    >
                        📄 PDF
                    </a>

                    <a
                        href="{{ route('rapports.examens-nationaux.excel', request()->query()) }}"
                        class="px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-green-700"
                    >
                        📊 Excel
                    </a>

                    <a
                        href="{{ route('rapports.examens-nationaux.imprimer', request()->query()) }}"
                        target="_blank"
                        class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        🖨️ Imprimer
                    </a>

                </div>

            </div>

            {{-- STATISTIQUES GÉNÉRALES --}}

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">


                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Candidats
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ $nombreCandidats }}
                    </p>

                </div>


                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Admis
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ $nombreAdmis }}
                    </p>

                </div>


                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Échecs
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ $nombreEchecs }}
                    </p>

                </div>


                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Taux de réussite
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ number_format($tauxReussite, 2, ',', ' ') }} %
                    </p>

                </div>

            </div>


            {{-- MOYENNE --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <div class="flex justify-between items-center">

                    <div>

                        <h3 class="font-semibold text-gray-800">
                            Moyenne générale
                        </h3>

                        <p class="text-sm text-gray-500">
                            Moyenne normalisée sur 20
                        </p>

                    </div>

                    <div class="text-3xl font-bold text-gray-800">

                        {{ number_format($moyenneGenerale, 2, ',', ' ') }}/20

                    </div>

                </div>

            </div>


            {{-- STATISTIQUES PAR CLASSE --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">

                <div class="p-5 border-b border-gray-200">

                    <h3 class="font-semibold text-gray-800">
                        Résultats par classe
                    </h3>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">
                                    Classe
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Candidats
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Admis
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Échecs
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Taux réussite
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            @forelse($statistiquesClasses as $stat)

                                <tr>

                                    <td class="px-5 py-3 text-sm font-medium">
                                        {{ $stat->classe }}
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ $stat->candidats }}
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ $stat->admis }}
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ $stat->echecs }}
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ number_format($stat->taux, 2, ',', ' ') }} %
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="px-5 py-8 text-center text-gray-500"
                                    >
                                        Aucun résultat d'examen trouvé.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- STATISTIQUES PAR MATIÈRE --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200">

                <div class="p-5 border-b border-gray-200">

                    <h3 class="font-semibold text-gray-800">
                        Résultats par matière
                    </h3>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">
                                    Matière
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Candidats
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Moyenne
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Meilleure note
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">
                                    Note minimale
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            @forelse($statistiquesMatieres as $stat)

                                <tr>

                                    <td class="px-5 py-3 text-sm font-medium">
                                        {{ $stat->matiere }}
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ $stat->candidats }}
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ number_format($stat->moyenne, 2, ',', ' ') }}/20
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ number_format($stat->meilleure, 2, ',', ' ') }}/20
                                    </td>

                                    <td class="px-5 py-3 text-sm text-center">
                                        {{ number_format($stat->minimale, 2, ',', ' ') }}/20
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="px-5 py-8 text-center text-gray-500"
                                    >
                                        Aucun résultat disponible.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>
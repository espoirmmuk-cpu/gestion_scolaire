<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- EN-TÊTE --}}
            <div class="flex justify-between items-center mb-6">

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Rapport statistique
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Année scolaire :
                        <strong>{{ $anneeScolaire->libelle }}</strong>
                    </p>
                </div>

                <div class="flex gap-2">
                    <a
                        href="{{ route('rapports.statistique.pdf') }}"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
                        Exporter PDF
                    </a>
                    <button
                        type="button"
                        onclick="window.print()"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Imprimer
                    </button>

                </div>

            </div>


            {{-- INFORMATIONS ÉTABLISSEMENT --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Informations générales
                    </h3>
                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <span class="text-gray-500">
                                Établissement :
                            </span>

                            <strong class="text-gray-800">
                                {{ $etablissement->nom ?? 'Non renseigné' }}
                            </strong>
                        </div>

                        <div>
                            <span class="text-gray-500">
                                Année scolaire :
                            </span>

                            <strong class="text-gray-800">
                                {{ $anneeScolaire->libelle }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>


            {{-- EFFECTIFS --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Effectifs des élèves
                    </h3>
                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <div class="border rounded-lg p-5 text-center">
                            <div class="text-3xl font-bold text-gray-800">
                                {{ $nombreEleves }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">
                                Total élèves
                            </div>
                        </div>


                        <div class="border rounded-lg p-5 text-center">
                            <div class="text-3xl font-bold text-gray-800">
                                {{ $nombreGarcons }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">
                                Garçons
                            </div>
                        </div>


                        <div class="border rounded-lg p-5 text-center">
                            <div class="text-3xl font-bold text-gray-800">
                                {{ $nombreFilles }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">
                                Filles
                            </div>
                        </div>


                        <div class="border rounded-lg p-5 text-center">
                            <div class="text-3xl font-bold text-gray-800">
                                {{ $nombreClasses }}
                            </div>

                            <div class="text-sm text-gray-500 mt-1">
                                Classes
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- RÉPARTITION PAR CLASSE --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Répartition des élèves par classe
                    </h3>
                </div>

                <div class="p-6 overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr class="bg-gray-100">

                                <th class="border px-4 py-3 text-left">
                                    Classe
                                </th>

                                <th class="border px-4 py-3 text-left">
                                    Option
                                </th>

                                <th class="border px-4 py-3 text-center">
                                    Effectif
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($effectifsClasses as $classe)

                                <tr>

                                    <td class="border px-4 py-3">
                                        {{ $classe->libelle }}
                                    </td>

                                    <td class="border px-4 py-3">
                                        {{ $classe->option_classe ?? '-' }}
                                    </td>

                                    <td class="border px-4 py-3 text-center font-semibold">
                                        {{ $classe->total }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="border px-4 py-6 text-center text-gray-500">

                                        Aucun élève inscrit.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PERSONNEL --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Personnel
                    </h3>
                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">

                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombrePersonnel }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Personnel total
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreEnseignants }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Enseignants
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreAutrePersonnel }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Autre personnel
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- SCOLARITÉ --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Scolarité
                    </h3>
                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">

                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreInscriptions }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Inscriptions
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreEleves }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Élèves
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreClasses }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Classes
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- FRÉQUENTATION --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Fréquentation scolaire
                    </h3>
                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-center">

                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombrePresences }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Présences
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreAbsences }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Absences
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $totalFrequentation }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Total
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $tauxPresence }} %
                            </div>

                            <div class="text-sm text-gray-500">
                                Taux de présence
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RÉSULTATS SCOLAIRES --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">
                        Résultats scolaires
                    </h3>
                </div>

                <div class="p-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-center">

                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreEvaluations }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Évaluations
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreNotes }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Notes
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $moyenneNotes }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Moyenne générale
                            </div>

                        </div>


                        <div class="border rounded-lg p-5">

                            <div class="text-3xl font-bold">
                                {{ $nombreReussites }}
                            </div>

                            <div class="text-sm text-gray-500">
                                Réussites
                            </div>

                        </div>

                    </div>


                    <div class="mt-5 text-center">

                        <span class="text-gray-500">
                            Échecs :
                        </span>

                        <strong>
                            {{ $nombreEchecs }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- SITUATION FINANCIÈRE --}}
            <div class="bg-white rounded-lg shadow-sm border mb-6">

                <div class="px-6 py-4 border-b">

                    <h3 class="font-semibold text-gray-800">
                        Situation financière
                    </h3>

                </div>

                <div class="p-6 overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr class="bg-gray-100">

                                <th class="border px-4 py-3 text-left">
                                    Devise
                                </th>

                                <th class="border px-4 py-3 text-right">
                                    Recettes
                                </th>

                                <th class="border px-4 py-3 text-right">
                                    Dépenses
                                </th>

                                <th class="border px-4 py-3 text-right">
                                    Solde
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td class="border px-4 py-3 font-semibold">
                                    USD
                                </td>

                                <td class="border px-4 py-3 text-right">
                                    {{ number_format($recettesUSD, 2, '.', ',') }}
                                </td>

                                <td class="border px-4 py-3 text-right">
                                    {{ number_format($depensesUSD, 2, '.', ',') }}
                                </td>

                                <td class="border px-4 py-3 text-right font-semibold">
                                    {{ number_format($soldeUSD, 2, '.', ',') }}
                                </td>

                            </tr>


                            <tr>

                                <td class="border px-4 py-3 font-semibold">
                                    CDF
                                </td>

                                <td class="border px-4 py-3 text-right">
                                    {{ number_format($recettesCDF, 2, '.', ',') }}
                                </td>

                                <td class="border px-4 py-3 text-right">
                                    {{ number_format($depensesCDF, 2, '.', ',') }}
                                </td>

                                <td class="border px-4 py-3 text-right font-semibold">
                                    {{ number_format($soldeCDF, 2, '.', ',') }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PIED DU RAPPORT --}}
            <div class="text-center text-sm text-gray-500 py-4">

                Rapport statistique généré le
                {{ now()->format('d/m/Y à H:i') }}

            </div>

        </div>
    </div>


    {{-- IMPRESSION --}}
    <style>
        @media print {

            nav,
            header,
            footer,
            button {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }

            .border {
                border: 1px solid #ccc !important;
            }

            .mb-6 {
                margin-bottom: 20px !important;
            }

        }
    </style>

</x-app-layout>
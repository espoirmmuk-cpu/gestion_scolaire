<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Détails de la période scolaire
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Informations détaillées sur cette période scolaire.
            </p>

        </div>


        <a href="{{ route('periodes-scolaires.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">

            ← Retour

        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


        {{-- Message de succès --}}

        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
                {{ session('success') }}
            </div>

        @endif


        {{-- Informations générales --}}

        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">


            <div class="px-6 py-5 border-b border-gray-100">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            {{ $periodeScolaire->libelle }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Informations de la période
                        </p>

                    </div>


                    <div class="flex items-center gap-2">

                        <a href="{{ route('periodes-scolaires.edit', $periodeScolaire) }}"
                           class="px-4 py-2 bg-yellow-100 text-yellow-700 font-semibold rounded-lg hover:bg-yellow-200 transition">

                            Modifier

                        </a>


                        <form action="{{ route('periodes-scolaires.destroy', $periodeScolaire) }}"
                              method="POST"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette période scolaire ?');">

                            @csrf

                            @method('DELETE')

                            <button type="submit"
                                    class="px-4 py-2 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200 transition">

                                Supprimer

                            </button>

                        </form>

                    </div>

                </div>

            </div>


            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                    {{-- Année scolaire --}}

                    <div class="bg-gray-50 rounded-lg p-5">

                        <p class="text-xs font-semibold text-gray-400 uppercase">
                            Année scolaire
                        </p>

                        <p class="text-lg font-bold text-gray-800 mt-2">

                            {{ $periodeScolaire->anneeScolaire->libelle ?? '—' }}

                        </p>

                    </div>


                    {{-- Date début --}}

                    <div class="bg-gray-50 rounded-lg p-5">

                        <p class="text-xs font-semibold text-gray-400 uppercase">
                            Date de début
                        </p>

                        <p class="text-lg font-bold text-gray-800 mt-2">

                            {{ $periodeScolaire->date_debut
                                ? $periodeScolaire->date_debut->format('d/m/Y')
                                : '—'
                            }}

                        </p>

                    </div>


                    {{-- Date fin --}}

                    <div class="bg-gray-50 rounded-lg p-5">

                        <p class="text-xs font-semibold text-gray-400 uppercase">
                            Date de fin
                        </p>

                        <p class="text-lg font-bold text-gray-800 mt-2">

                            {{ $periodeScolaire->date_fin
                                ? $periodeScolaire->date_fin->format('d/m/Y')
                                : '—'
                            }}

                        </p>

                    </div>


                </div>

            </div>

        </div>


        {{-- Évaluations de la période --}}

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Évaluations
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Les évaluations associées à cette période scolaire.
                </p>

            </div>


            @if($periodeScolaire->evaluations->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Évaluation
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Matière
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Classe
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Note maximale
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($periodeScolaire->evaluations as $evaluation)

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4">

                                        <p class="font-semibold text-gray-800">
                                            {{ $evaluation->libelle }}
                                        </p>

                                        @if($evaluation->type_evaluation)

                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $evaluation->type_evaluation }}
                                            </p>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $evaluation->matiere->libelle ?? '—' }}
                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $evaluation->classe->libelle ?? '—' }}
                                    </td>


                                    <td class="px-6 py-4 text-sm font-semibold text-gray-700">

                                        {{ number_format((float) $evaluation->note_maximale, 2, ',', ' ') }}

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        <a href="{{ route('evaluations.show', $evaluation) }}"
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">

                                            Voir

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-10 text-center">

                    <div class="text-4xl mb-3">
                        📝
                    </div>

                    <p class="font-medium text-gray-600">
                        Aucune évaluation pour cette période.
                    </p>

                    <p class="text-sm text-gray-400 mt-1">
                        Les évaluations créées pour cette période apparaîtront ici.
                    </p>

                </div>

            @endif

        </div>


    </div>

</div>

</x-app-layout>

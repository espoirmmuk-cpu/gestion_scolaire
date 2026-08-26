<x-app-layout>

    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- En-tête --}}
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Détail des présences
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Consultation des présences de la classe
                    </p>
                </div>

                <div class="flex gap-3">

                    <a href="{{ route('presences.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        ← Retour
                    </a>

                    <a href="{{ route('presences.edit', $presence) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                        Modifier
                    </a>

                </div>

            </div>


            {{-- Informations générales --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 mb-6">

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div>
                            <p class="text-sm text-gray-500">
                                Classe
                            </p>

                            <p class="mt-1 text-lg font-semibold text-gray-800">
                                {{ $presence->classe->libelle ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Date de présence
                            </p>

                            <p class="mt-1 text-lg font-semibold text-gray-800">
                                {{ $presence->date_presence
                                    ? $presence->date_presence->format('d/m/Y')
                                    : '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Élève de référence
                            </p>

                            <p class="mt-1 text-lg font-semibold text-gray-800">
                                {{ $presence->eleve->nom ?? '' }}
                                {{ $presence->eleve->postnom ?? '' }}
                                {{ $presence->eleve->prenom ?? '' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Détail de la présence sélectionnée --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-200">

                <div class="px-6 py-4 border-b border-gray-200">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Présence
                    </h2>

                </div>


                <div class="p-6">

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Élève
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Statut
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Motif
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Observation
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                <tr>

                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-900">

                                            {{ $presence->eleve->nom ?? '' }}

                                            {{ $presence->eleve->postnom ?? '' }}

                                            {{ $presence->eleve->prenom ?? '' }}

                                        </div>

                                        @if($presence->eleve && $presence->eleve->matricule)

                                            <div class="text-sm text-gray-500">

                                                {{ $presence->eleve->matricule }}

                                            </div>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4">

                                        @php
                                            $statut = strtoupper($presence->statut ?? '');
                                        @endphp

                                        @if($statut === 'PRESENT' || $statut === 'PRÉSENT')

                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700">
                                                Présent
                                            </span>

                                        @elseif($statut === 'ABSENT')

                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700">
                                                Absent
                                            </span>

                                        @elseif($statut === 'RETARD')

                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Retard
                                            </span>

                                        @elseif($statut === 'EXCUSE' || $statut === 'EXCUSÉ')

                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-700">
                                                Excusé
                                            </span>

                                        @else

                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-700">
                                                {{ $presence->statut ?? '—' }}
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4 text-gray-700">

                                        {{ $presence->motif ?: '—' }}

                                    </td>


                                    <td class="px-6 py-4 text-gray-700">

                                        {{ $presence->observation ?: '—' }}

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
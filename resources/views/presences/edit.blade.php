<x-app-layout>

    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- En-tête --}}
            <div class="mb-6">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>

                        <h1 class="text-2xl font-bold text-gray-800">
                            Modifier les présences
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Modifier les présences de toute la classe
                        </p>

                    </div>


                    <a href="{{ route('presences.index') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

                        ← Retour

                    </a>

                </div>

            </div>


            {{-- Informations classe --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 mb-6">

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>

                            <label class="block text-sm font-medium text-gray-500">
                                Classe
                            </label>

                            <div class="mt-1 text-lg font-semibold text-gray-800">

                                {{ $classe->libelle }}

                            </div>

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-gray-500">
                                Date
                            </label>

                            <div class="mt-1 text-lg font-semibold text-gray-800">

                                {{ $presence->date_presence
                                    ? $presence->date_presence->format('d/m/Y')
                                    : '—' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Messages --}}
            @if ($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">

                    <div class="font-semibold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </div>

                    <ul class="list-disc list-inside text-sm">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Formulaire --}}
            <form method="POST"
                  action="{{ route('presences.update', $presence) }}">

                @csrf

                @method('PUT')


                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

                    {{-- En-tête tableau --}}
                    <div class="px-6 py-4 border-b border-gray-200">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                            <div>

                                <h2 class="text-lg font-semibold text-gray-800">
                                    Présences des élèves
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Modifiez le statut de chaque élève.
                                </p>

                            </div>


                            <div class="text-sm text-gray-500">

                                {{ $eleves->count() }} élève(s)

                            </div>

                        </div>

                    </div>


                    {{-- Tableau --}}
                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        #
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Matricule
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Élève
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Statut
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Motif
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                        Observation
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($eleves as $index => $eleve)

                                    @php

                                        $presenceEleve =
                                            $presences->get($eleve->id_eleve);

                                    @endphp


                                    <tr class="hover:bg-gray-50">

                                        {{-- Numéro --}}
                                        <td class="px-4 py-4 text-sm text-gray-500">

                                            {{ $index + 1 }}

                                        </td>


                                        {{-- Matricule --}}
                                        <td class="px-4 py-4 text-sm font-medium text-gray-700">

                                            {{ $eleve->matricule ?? '—' }}

                                        </td>


                                        {{-- Élève --}}
                                        <td class="px-4 py-4">

                                            <div class="font-medium text-gray-900">

                                                {{ $eleve->nom }}
                                                {{ $eleve->postnom }}
                                                {{ $eleve->prenom }}

                                            </div>

                                        </td>


                                        {{-- Statut --}}
                                        <td class="px-4 py-4">

                                            <select
                                                name="presences[{{ $eleve->id_eleve }}][statut]"
                                                class="w-full min-w-[150px] rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                                <option value="PRESENT"
                                                    @selected(
                                                        old(
                                                            "presences.{$eleve->id_eleve}.statut",
                                                            $presenceEleve->statut ?? ''
                                                        ) === 'PRESENT'
                                                    )>
                                                    Présent
                                                </option>

                                                <option value="ABSENT"
                                                    @selected(
                                                        old(
                                                            "presences.{$eleve->id_eleve}.statut",
                                                            $presenceEleve->statut ?? ''
                                                        ) === 'ABSENT'
                                                    )>
                                                    Absent
                                                </option>

                                                <option value="RETARD"
                                                    @selected(
                                                        old(
                                                            "presences.{$eleve->id_eleve}.statut",
                                                            $presenceEleve->statut ?? ''
                                                        ) === 'RETARD'
                                                    )>
                                                    Retard
                                                </option>

                                                <option value="EXCUSE"
                                                    @selected(
                                                        old(
                                                            "presences.{$eleve->id_eleve}.statut",
                                                            $presenceEleve->statut ?? ''
                                                        ) === 'EXCUSE'
                                                    )>
                                                    Excusé
                                                </option>

                                            </select>

                                        </td>


                                        {{-- Motif --}}
                                        <td class="px-4 py-4">

                                            <input
                                                type="text"
                                                name="presences[{{ $eleve->id_eleve }}][motif]"
                                                value="{{ old(
                                                    "presences.{$eleve->id_eleve}.motif",
                                                    $presenceEleve->motif ?? ''
                                                ) }}"
                                                class="w-full min-w-[180px] rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                                                placeholder="Motif">

                                        </td>


                                        {{-- Observation --}}
                                        <td class="px-4 py-4">

                                            <input
                                                type="text"
                                                name="presences[{{ $eleve->id_eleve }}][observation]"
                                                value="{{ old(
                                                    "presences.{$eleve->id_eleve}.observation",
                                                    $presenceEleve->observation ?? ''
                                                ) }}"
                                                class="w-full min-w-[220px] rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                                                placeholder="Observation">

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6"
                                            class="px-6 py-10 text-center text-gray-500">

                                            Aucun élève inscrit dans cette classe.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Boutons --}}
                    <div class="p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">

                        <a href="{{ route('presences.index') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

                            Annuler

                        </a>


                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

                            Enregistrer les modifications

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Liste des enseignants
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Liste du personnel enseignant de l'établissement.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- En-tête --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <div class="flex items-center">

                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">

                        <span class="text-2xl">
                            👨‍🏫
                        </span>

                    </div>

                    <div class="ml-4">

                        <h1 class="text-xl font-bold text-gray-800">
                            Liste des enseignants
                        </h1>

                        <p class="text-sm text-gray-500">
                            {{ $enseignants->count() }} enseignant(s)
                        </p>

                    </div>

                </div>


                {{-- Boutons --}}

                <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end gap-3">

                    <a
                        href="{{ route('rapports.enseignants.pdf') }}"
                        class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    >
                        📄 PDF
                    </a>


                    <a
                        href="{{ route('rapports.enseignants.excel') }}"
                        class="px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-green-700"
                    >
                        📊 Excel
                    </a>


                    <a
                        href="{{ route('rapports.enseignants.imprimer') }}"
                        target="_blank"
                        class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        🖨️ Imprimer
                    </a>

                </div>

            </div>


            {{-- Tableau --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    N°
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Matricule
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Nom complet
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                    Sexe
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Qualification
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Téléphone
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Email
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Engagement
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                    Statut
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse($enseignants as $index => $enseignant)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $enseignant->matricule }}
                                    </td>

                                    <td class="px-4 py-4 text-sm font-medium text-gray-800">

                                        {{ $enseignant->nom }}
                                        {{ $enseignant->postnom }}
                                        {{ $enseignant->prenom }}

                                    </td>

                                    <td class="px-4 py-4 text-sm text-center">
                                        {{ $enseignant->sexe }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $enseignant->qualification }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $enseignant->telephone }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $enseignant->email }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-700">
                                        {{ $enseignant->date_engagement }}
                                    </td>

                                    <td class="px-4 py-4 text-center">

                                        <span class="px-2 py-1 text-xs rounded-full
                                            {{ strtoupper($enseignant->statut) === 'ACTIF'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-gray-100 text-gray-700' }}">

                                            {{ $enseignant->statut }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="9"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        Aucun enseignant trouvé.
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
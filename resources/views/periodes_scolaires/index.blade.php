<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Périodes scolaires
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gestion des périodes scolaires par année académique.
            </p>
        </div>

        <a href="{{ route('periodes-scolaires.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            + Ajouter une période
        </a>
    </div>
</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Message de succès --}}
        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
                {{ session('success') }}
            </div>

        @endif


        {{-- Message d'erreur --}}
        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                {{ session('error') }}
            </div>

        @endif


        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            {{-- En-tête --}}
            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Liste des périodes scolaires
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Les périodes disponibles pour les évaluations.
                </p>

            </div>


            {{-- Tableau --}}
            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                #
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Année scolaire
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Période
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Date début
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Date fin
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="bg-white divide-y divide-gray-100">

                        @forelse($periodes as $periode)

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $periode->id_periode }}
                                </td>


                                <td class="px-6 py-4 whitespace-nowrap">

                                    <span class="text-sm font-semibold text-gray-800">
                                        {{ $periode->anneeScolaire->libelle ?? '—' }}
                                    </span>

                                </td>


                                <td class="px-6 py-4 whitespace-nowrap">

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-700">
                                        {{ $periode->libelle }}
                                    </span>

                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">

                                    {{ $periode->date_debut
                                        ? $periode->date_debut->format('d/m/Y')
                                        : '—'
                                    }}

                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">

                                    {{ $periode->date_fin
                                        ? $periode->date_fin->format('d/m/Y')
                                        : '—'
                                    }}

                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Voir --}}
                                        <a href="{{ route('periodes-scolaires.show', $periode) }}"
                                           class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                            Voir
                                        </a>


                                        {{-- Modifier --}}
                                        <a href="{{ route('periodes-scolaires.edit', $periode) }}"
                                           class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                                            Modifier
                                        </a>


                                        {{-- Supprimer --}}
                                        <form action="{{ route('periodes-scolaires.destroy', $periode) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette période scolaire ?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                                Supprimer
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="px-6 py-12 text-center">

                                    <div class="text-4xl mb-3">
                                        📅
                                    </div>

                                    <p class="font-medium text-gray-600">
                                        Aucune période scolaire enregistrée.
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        Commencez par ajouter une période scolaire.
                                    </p>

                                    <div class="mt-4">

                                        <a href="{{ route('periodes-scolaires.create') }}"
                                           class="inline-flex px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                            + Ajouter une période
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pied de tableau --}}
            @if($periodes->count() > 0)

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">

                    <p class="text-xs text-gray-400">
                        {{ $periodes->count() }}
                        période(s) scolaire(s) enregistrée(s).
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

</x-app-layout>

<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="font-semibold text-xl text-gray-800">
                Situation financière
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Synthèse des recettes, dépenses et paiements.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- Sélection année --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <form method="GET"
                      action="{{ route('rapports.finances') }}">

                    <div class="flex items-end gap-4">

                        <div class="flex-1">

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Année scolaire
                            </label>

                            <select
                                name="annee"
                                class="w-full rounded-lg border-gray-300"
                                required
                            >

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                @foreach($annees as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        @selected(request('annee') == $annee->id_annee_scolaire)
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


            @if($anneeSelectionnee)

                {{-- Cartes statistiques --}}

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                    <div class="bg-white rounded-xl shadow-sm border p-6">

                        <p class="text-sm text-gray-500">
                            Total recettes
                        </p>

                        <p class="text-2xl font-bold text-green-600 mt-2">
                            {{ number_format($totalRecettes, 2, ',', ' ') }}
                        </p>

                    </div>


                    <div class="bg-white rounded-xl shadow-sm border p-6">

                        <p class="text-sm text-gray-500">
                            Total dépenses
                        </p>

                        <p class="text-2xl font-bold text-red-600 mt-2">
                            {{ number_format($totalDepenses, 2, ',', ' ') }}
                        </p>

                    </div>


                    <div class="bg-white rounded-xl shadow-sm border p-6">

                        <p class="text-sm text-gray-500">
                            Solde
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ number_format($solde, 2, ',', ' ') }}
                        </p>

                    </div>

                </div>


                {{-- Actions --}}

                <div class="bg-white rounded-xl shadow-sm border p-5 mb-6">

                    <div class="flex justify-end gap-3">

                        <a
                            href="{{ route('rapports.finances.pdf', ['annee' => request('annee')]) }}"
                            class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700"
                        >
                            📄 PDF
                        </a>

                        <a
                            href="{{ route('rapports.finances.excel', ['annee' => request('annee')]) }}"
                            class="px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-green-700"
                        >
                            📊 Excel
                        </a>

                        <a
                            href="{{ route('rapports.finances.imprimer', ['annee' => request('annee')]) }}"
                            target="_blank"
                            class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                        >
                            🖨️ Imprimer
                        </a>

                    </div>

                </div>


                {{-- Recettes --}}

                <div class="bg-white rounded-xl shadow-sm border overflow-hidden mb-6">

                    <div class="p-5 border-b">

                        <h3 class="font-semibold text-gray-800">
                            Recettes
                        </h3>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs">
                                        Date
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs">
                                        Source
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs">
                                        Description
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs">
                                        Montant
                                    </th>

                                    <th class="px-4 py-3 text-center text-xs">
                                        Devise
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y">

                                @forelse($recettes as $recette)

                                    <tr>

                                        <td class="px-4 py-3 text-sm">
                                            {{ $recette->date_recette }}
                                        </td>

                                        <td class="px-4 py-3 text-sm">
                                            {{ $recette->source }}
                                        </td>

                                        <td class="px-4 py-3 text-sm">
                                            {{ $recette->description }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-right">
                                            {{ number_format($recette->montant, 2, ',', ' ') }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-center">
                                            {{ $recette->devise }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="px-6 py-8 text-center text-gray-500">

                                            Aucune recette enregistrée.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- Dépenses --}}

                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

                    <div class="p-5 border-b">

                        <h3 class="font-semibold text-gray-800">
                            Dépenses
                        </h3>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs">
                                        Date
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs">
                                        Catégorie
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs">
                                        Description
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs">
                                        Montant
                                    </th>

                                    <th class="px-4 py-3 text-center text-xs">
                                        Devise
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y">

                                @forelse($depenses as $depense)

                                    <tr>

                                        <td class="px-4 py-3 text-sm">
                                            {{ $depense->date_depense }}
                                        </td>

                                        <td class="px-4 py-3 text-sm">
                                            {{ $depense->categorie }}
                                        </td>

                                        <td class="px-4 py-3 text-sm">
                                            {{ $depense->description }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-right">
                                            {{ number_format($depense->montant, 2, ',', ' ') }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-center">
                                            {{ $depense->devise }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="px-6 py-8 text-center text-gray-500">

                                            Aucune dépense enregistrée.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
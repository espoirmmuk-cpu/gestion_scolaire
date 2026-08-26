<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800">
                Inventaire des biens
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Liste des biens et équipements de l'établissement.
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- FILTRES --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <form
                    method="GET"
                    action="{{ route('rapports.inventaire') }}"
                >

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">


                        {{-- Catégorie --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie
                            </label>

                            <select
                                name="categorie"
                                class="w-full rounded-lg border-gray-300"
                            >

                                <option value="">
                                    Toutes les catégories
                                </option>

                                @foreach($categories as $categorie)

                                    <option
                                        value="{{ $categorie->id_categorie }}"
                                        @selected(request('categorie') == $categorie->id_categorie)
                                    >
                                        {{ $categorie->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- État --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                État
                            </label>

                            <select
                                name="etat"
                                class="w-full rounded-lg border-gray-300"
                            >

                                <option value="">
                                    Tous les états
                                </option>

                                <option
                                    value="BON"
                                    @selected(request('etat') == 'BON')
                                >
                                    Bon
                                </option>

                                <option
                                    value="MOYEN"
                                    @selected(request('etat') == 'MOYEN')
                                >
                                    Moyen
                                </option>

                                <option
                                    value="MAUVAIS"
                                    @selected(request('etat') == 'MAUVAIS')
                                >
                                    Mauvais
                                </option>

                                <option
                                    value="HORS_SERVICE"
                                    @selected(request('etat') == 'HORS_SERVICE')
                                >
                                    Hors service
                                </option>

                            </select>

                        </div>


                        {{-- Bouton --}}

                        <div>

                            <button
                                type="submit"
                                class="w-full px-6 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                            >
                                🔎 Afficher
                            </button>

                        </div>

                    </div>

                </form>

            </div>


            {{-- STATISTIQUES --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">


                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <p class="text-sm text-gray-500">
                        Nombre de biens
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ $totalBiens }}
                    </p>

                </div>


                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <p class="text-sm text-gray-500">
                        Quantité totale
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-2">
                        {{ number_format($totalQuantite, 0, ',', ' ') }}
                    </p>

                </div>

            </div>


            {{-- ACTIONS --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">

                <div class="flex justify-end gap-3 flex-wrap">

                    <a
                        href="{{ route('rapports.inventaire.pdf', request()->query()) }}"
                        class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    >
                        📄 PDF
                    </a>

                    <a
                        href="{{ route('rapports.inventaire.excel', request()->query()) }}"
                        class="px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-green-700"
                    >
                        📊 Excel
                    </a>

                    <a
                        href="{{ route('rapports.inventaire.imprimer', request()->query()) }}"
                        target="_blank"
                        class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        🖨️ Imprimer
                    </a>

                </div>

            </div>


            {{-- TABLEAU --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="p-5 border-b border-gray-200">

                    <h3 class="font-semibold text-gray-800">
                        Liste des biens
                    </h3>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">
                                    N°
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">
                                    Désignation
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">
                                    Catégorie
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">
                                    Quantité
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">
                                    Date acquisition
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">
                                    État
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">
                                    Localisation
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">
                                    Responsable
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            @forelse($biens as $index => $bien)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-3 text-sm">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                        {{ $bien->designation }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        {{ $bien->categorie ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-center">
                                        {{ $bien->quantite }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        {{ $bien->date_acquisition ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        {{ $bien->etat ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        {{ $bien->localisation ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        {{ $bien->responsable ?? '—' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="px-6 py-10 text-center text-gray-500"
                                    >
                                        Aucun bien trouvé dans l'inventaire.
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
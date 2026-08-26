<x-app-layout>

<x-slot name="header">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Gestion des matières
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gérer les matières, leurs coefficients et leurs statuts.
            </p>
        </div>

        <a href="{{ route('matieres.create') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

            + Ajouter une matière

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


        {{-- Recherche et filtres --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">

            <form action="{{ route('matieres.index') }}"
                  method="GET">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Recherche --}}
                    <div>

                        <label for="search"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Recherche

                        </label>

                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Code ou libellé..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>


                    {{-- Statut --}}
                    <div>

                        <label for="statut"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Statut

                        </label>

                        <select
                            name="statut"
                            id="statut"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                            <option value="">
                                Tous les statuts
                            </option>

                            <option value="ACTIVE"
                                {{ request('statut') === 'ACTIVE' ? 'selected' : '' }}>
                                ACTIVE
                            </option>

                            <option value="INACTIVE"
                                {{ request('statut') === 'INACTIVE' ? 'selected' : '' }}>
                                INACTIVE
                            </option>

                        </select>

                    </div>


                    {{-- Boutons --}}
                    <div class="flex items-end gap-2">

                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                        >

                            🔎 Rechercher

                        </button>

                        <a
                            href="{{ route('matieres.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        >

                            Réinitialiser

                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- Tableau --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Liste des matières
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $matieres->count() }} matière(s) enregistrée(s).
                </p>

            </div>


            @if($matieres->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Code
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Matière
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Coefficient
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Statut
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-100">

                            @foreach($matieres as $matiere)

                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Code --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span class="font-semibold text-gray-800">
                                            {{ $matiere->code }}
                                        </span>

                                    </td>


                                    {{-- Libellé --}}
                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-800">
                                            {{ $matiere->libelle }}
                                        </div>

                                    </td>


                                    {{-- Coefficient --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">

                                        <span class="font-semibold text-gray-700">
                                            {{ number_format($matiere->coefficient, 2, ',', ' ') }}
                                        </span>

                                    </td>


                                    {{-- Statut --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">

                                        @if($matiere->statut === 'ACTIVE')

                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">

                                                ACTIVE

                                            </span>

                                        @else

                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">

                                                INACTIVE

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right">

                                        <div class="flex justify-end items-center gap-2">

                                            {{-- Voir --}}
                                            <a
                                                href="{{ route('matieres.show', $matiere) }}"
                                                class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm"
                                            >

                                                Voir

                                            </a>


                                            {{-- Modifier --}}
                                            <a
                                                href="{{ route('matieres.edit', $matiere) }}"
                                                class="px-3 py-1.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm"
                                            >

                                                Modifier

                                            </a>


                                            {{-- Supprimer --}}
                                            <form
                                                action="{{ route('matieres.destroy', $matiere) }}"
                                                method="POST"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette matière ?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm"
                                                >

                                                    Supprimer

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100">

                  

                </div>

            @else

                {{-- Aucune matière --}}
                <div class="px-6 py-12 text-center">

                    <div class="text-gray-400 text-5xl mb-4">
                        📚
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        Aucune matière trouvée
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Aucune matière ne correspond aux critères de recherche.
                    </p>

                    <a
                        href="{{ route('matieres.create') }}"
                        class="inline-block mt-5 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                    >

                        + Ajouter une matière

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

</x-app-layout>
<x-app-layout>

```
<x-slot name="header">

    <div class="flex justify-between items-center">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800">
                Gestion des classes
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Liste des classes de l'établissement
            </p>
        </div>

        <a href="{{ route('classes.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            + Ajouter une classe
        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Message de succès --}}
        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        {{-- Recherche et filtres --}}
        <div class="bg-white shadow-sm rounded-lg mb-6">

            <form method="GET"
                  action="{{ route('classes.index') }}">

                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        {{-- Recherche --}}
                        <div class="md:col-span-1">

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Recherche
                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Classe ou option..."
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                        </div>


                        {{-- Niveau --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Niveau
                            </label>

                            <select
                                name="niveau"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                <option value="">
                                    Tous les niveaux
                                </option>

                                @foreach($niveaux as $niveau)

                                    <option
                                        value="{{ $niveau->id_niveau }}"
                                        {{ request('niveau') == $niveau->id_niveau ? 'selected' : '' }}>

                                        {{ $niveau->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Année scolaire --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Année scolaire
                            </label>

                            <select
                                name="annee_scolaire"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                <option value="">
                                    Toutes les années
                                </option>

                                @foreach($anneesScolaires as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        {{ request('annee_scolaire') == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                        {{ $annee->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Statut --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Statut
                            </label>

                            <select
                                name="statut"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                <option value="">
                                    Tous les statuts
                                </option>

                                <option value="ACTIVE"
                                    {{ request('statut') === 'ACTIVE' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="INACTIVE"
                                    {{ request('statut') === 'INACTIVE' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Boutons --}}
                    <div class="mt-5 flex justify-end gap-3">

                        <a href="{{ route('classes.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Réinitialiser
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                            Rechercher
                        </button>

                    </div>

                </div>

            </form>

        </div>


        {{-- Liste --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">

            <div class="p-6">

                <div class="flex justify-between items-center mb-6">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Liste des classes
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $classes->total() }} classe(s)
                        </p>

                    </div>

                </div>


                @if($classes->count() > 0)

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Classe
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Niveau
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Année scolaire
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Option
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Capacité
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Statut
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach($classes as $classe)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <span class="font-semibold text-gray-800">
                                                {{ $classe->libelle }}
                                            </span>

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">

                                            {{ $classe->niveau->libelle ?? '-' }}

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">

                                            {{ $classe->anneeScolaire->libelle ?? '-' }}

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">

                                            {{ $classe->option_classe ?? '-' }}

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700">

                                            {{ $classe->capacite }}

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap text-center">

                                            @if($classe->statut === 'ACTIVE')

                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                    ACTIVE
                                                </span>

                                            @else

                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                    INACTIVE
                                                </span>

                                            @endif

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap text-center">

                                            <div class="flex justify-center gap-2">

                                                <a href="{{ route('classes.show', $classe) }}"
                                                   class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
                                                    Voir
                                                </a>

                                                <a href="{{ route('classes.edit', $classe) }}"
                                                   class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                                                    Modifier
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('classes.destroy', $classe) }}"
                                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette classe ?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 text-sm">
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
                    <div class="mt-6">

                        {{ $classes->links() }}

                    </div>


                @else

                    <div class="text-center py-12">

                        <div class="text-5xl mb-4">
                            🎓
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Aucune classe enregistrée
                        </h3>

                        <p class="text-gray-500 mt-2">
                            Commencez par ajouter votre première classe.
                        </p>

                        <a href="{{ route('classes.create') }}"
                           class="inline-block mt-5 px-5 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                            Ajouter une classe
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
```

</x-app-layout>

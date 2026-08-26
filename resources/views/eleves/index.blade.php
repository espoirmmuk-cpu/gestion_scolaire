<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">

            <div>
                <h2 class="font-semibold text-2xl text-gray-800">
                    Gestion des élèves
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Liste des élèves inscrits
                </p>
            </div>

            <a href="{{ route('eleves.create') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                + Ajouter un élève
            </a>

        </div>
        
    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))

                <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>

            @endif
                {{-- Recherche et filtres --}}

<div class="bg-white shadow-sm rounded-lg mb-5">


<div class="p-5">

    <form method="GET" action="{{ route('eleves.index') }}">

        <div class="flex flex-col lg:flex-row gap-3">

            {{-- Recherche --}}
            <div class="flex-1">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Rechercher par matricule, nom, postnom ou prénom..."
                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                >

            </div>

            {{-- Sexe --}}
            <div class="w-full lg:w-40">

                <select
                    name="sexe"
                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                    <option value="">Tous les sexes</option>

                    <option value="M" {{ request('sexe') === 'M' ? 'selected' : '' }}>
                        Masculin
                    </option>

                    <option value="F" {{ request('sexe') === 'F' ? 'selected' : '' }}>
                        Féminin
                    </option>

                </select>

            </div>

            {{-- Statut --}}
            <div class="w-full lg:w-40">

                <select
                    name="statut"
                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                    <option value="">Tous les statuts</option>

                    <option value="ACTIF" {{ request('statut') === 'ACTIF' ? 'selected' : '' }}>
                        Actif
                    </option>

                    <option value="INACTIF" {{ request('statut') === 'INACTIF' ? 'selected' : '' }}>
                        Inactif
                    </option>

                </select>

            </div>

            {{-- Bouton rechercher --}}
            <button
                type="submit"
                class="px-5 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

                Rechercher

            </button>

            {{-- Réinitialiser --}}
            @if(request()->hasAny(['search', 'sexe', 'statut']))

                <a href="{{ route('eleves.index') }}"
                   class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center">

                    Réinitialiser

                </a>

            @endif

        </div>

    </form>

</div>


</div>


            <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                <div class="p-6">

                    <div class="flex justify-between items-center mb-6">

                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Liste des élèves
                            </h3>

                            <p class="text-sm text-gray-500">
                                {{ $eleves->count() }} élève(s)
                            </p>
                        </div>

                    </div>


                    @if($eleves->count() > 0)

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Matricule
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nom
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Prénom
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Sexe
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Téléphone
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Statut
                                        </th>
                                        
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach($eleves as $eleve)

                                        <tr class="hover:bg-gray-50">

                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                {{ $eleve->matricule }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                {{ $eleve->nom }}
                                                {{ $eleve->postnom }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                {{ $eleve->prenom }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                {{ $eleve->sexe }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                                {{ $eleve->telephone ?? '-' }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">

                                            @if($eleve->statut === 'ACTIF')

                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                        ACTIF
                                                    </span>

                                                @else

                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                        INACTIF
                                                    </span>

                                                @endif
<td class="px-4 py-4 whitespace-nowrap">

    <div class="flex items-center gap-2">

        {{-- Voir --}}
        <a href="{{ route('eleves.show', $eleve) }}"
           class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
            Voir
        </a>

        {{-- Modifier --}}
        <a href="{{ route('eleves.edit', $eleve) }}"
           class="px-3 py-1.5 text-sm bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
            Modifier
        </a>

        {{-- Supprimer --}}
        <form method="POST"
              action="{{ route('eleves.destroy', $eleve) }}"
              onsubmit="return confirm('Voulez-vous vraiment supprimer cet élève ?');">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="px-3 py-1.5 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                Supprimer
            </button>

        </form>

    </div>

</td>


                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-12">

                            <div class="text-5xl mb-4">
                                🎓
                            </div>

                            <h3 class="text-lg font-semibold text-gray-800">
                                Aucun élève enregistré
                            </h3>

                            <p class="text-gray-500 mt-2">
                                Commencez par ajouter le premier élève.
                            </p>

                            <a href="{{ route('eleves.create') }}"
                               class="inline-block mt-5 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">

                                Ajouter un élève

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
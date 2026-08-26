<x-app-layout>


<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800">
                Personnel / Enseignants
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gestion du personnel de l'établissement
            </p>
        </div>

        <a href="{{ route('personnel.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            + Ajouter un enseignant
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
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">

            <form method="GET" action="{{ route('personnel.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Recherche --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Recherche
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nom, matricule, téléphone..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                    </div>


                    {{-- Sexe --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Sexe
                        </label>

                        <select
                            name="sexe"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                Tous
                            </option>

                            <option value="M" {{ request('sexe') === 'M' ? 'selected' : '' }}>
                                Masculin
                            </option>

                            <option value="F" {{ request('sexe') === 'F' ? 'selected' : '' }}>
                                Féminin
                            </option>

                        </select>

                    </div>


                    {{-- Fonction --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fonction
                        </label>

                        <select
                            name="fonction"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                Toutes les fonctions
                            </option>

                            @foreach($fonctions as $fonction)

                                <option
                                    value="{{ $fonction }}"
                                    {{ request('fonction') === $fonction ? 'selected' : '' }}>

                                    {{ $fonction }}

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
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                Tous
                            </option>

                            <option value="ACTIF" {{ request('statut') === 'ACTIF' ? 'selected' : '' }}>
                                Actif
                            </option>

                            <option value="INACTIF" {{ request('statut') === 'INACTIF' ? 'selected' : '' }}>
                                Inactif
                            </option>

                            <option value="SUSPENDU" {{ request('statut') === 'SUSPENDU' ? 'selected' : '' }}>
                                Suspendu
                            </option>

                        </select>

                    </div>

                </div>


                <div class="mt-4 flex gap-3">

                    <button
                        type="submit"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Rechercher
                    </button>

                    <a
                        href="{{ route('personnel.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Réinitialiser
                    </a>

                </div>

            </form>

        </div>


        {{-- Tableau --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Liste du personnel
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ $personnels->total() }} membre(s)
                        </p>

                    </div>

                </div>

            </div>


            @if($personnels->count())

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Matricule
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Nom complet
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Sexe
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Fonction
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Téléphone
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Statut
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($personnels as $personnel)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                        {{ $personnel->matricule }}
                                    </td>


                                    <td class="px-6 py-4">

                                        <div class="font-medium text-gray-800">

                                            {{ $personnel->nom }}
                                            {{ $personnel->postnom }}
                                            {{ $personnel->prenom }}

                                        </div>

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $personnel->sexe === 'M' ? 'Masculin' : 'Féminin' }}
                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $personnel->fonction }}
                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $personnel->telephone ?? '-' }}
                                    </td>


                                    <td class="px-6 py-4">

                                        @if($personnel->statut === 'ACTIF')

                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                Actif
                                            </span>

                                        @elseif($personnel->statut === 'SUSPENDU')

                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                Suspendu
                                            </span>

                                        @else

                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                Inactif
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        <div class="flex justify-end gap-2">

                                            <a
                                                href="{{ route('personnel.show', $personnel) }}"
                                                class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                                Voir
                                            </a>

                                            <a
                                                href="{{ route('personnel.edit', $personnel) }}"
                                                class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                                Modifier
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('personnel.destroy', $personnel) }}"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce membre du personnel ?');">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
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
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $personnels->links() }}
                </div>


            @else

                <div class="p-10 text-center">

                    <div class="text-5xl mb-4">
                        👨‍🏫
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        Aucun membre du personnel
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Commencez par ajouter un enseignant.
                    </p>

                    <a
                        href="{{ route('personnel.create') }}"
                        class="inline-block mt-5 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        + Ajouter un enseignant
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


</x-app-layout>

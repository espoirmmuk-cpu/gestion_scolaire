<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Gestion des rôles
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gérez les rôles et les permissions des utilisateurs.
            </p>
        </div>

        <a href="{{ route('roles.create') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                  rounded-lg font-semibold text-xs text-white uppercase tracking-widest
                  hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900
                  focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2
                  transition">

            <span class="mr-2">+</span>
            Nouveau rôle

        </a>

    </div>
</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Messages --}}

        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700
                        px-4 py-3 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700
                        px-4 py-3 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        {{-- Tableau des rôles --}}

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="p-6 border-b border-gray-200">

                <h3 class="text-lg font-bold text-gray-800">
                    Rôles disponibles
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Chaque rôle possède un ensemble spécifique de permissions.
                </p>

            </div>


            @if($roles->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           text-gray-500 uppercase tracking-wider">
                                    Rôle
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold
                                           text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold
                                           text-gray-500 uppercase tracking-wider">
                                    Permissions
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold
                                           text-gray-500 uppercase tracking-wider">
                                    Utilisateurs
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold
                                           text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($roles as $role)

                                <tr class="hover:bg-gray-50 transition">


                                    {{-- Nom --}}

                                    <td class="px-6 py-5 whitespace-nowrap">

                                        <div class="flex items-center">

                                            <div class="w-10 h-10 bg-indigo-100 rounded-full
                                                        flex items-center justify-center">

                                                <span class="text-lg">
                                                    👤
                                                </span>

                                            </div>

                                            <div class="ml-3">

                                                <div class="text-sm font-semibold text-gray-800">
                                                    {{ $role->nom }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Description --}}

                                    <td class="px-6 py-5">

                                        <div class="text-sm text-gray-600 max-w-md">

                                            {{ $role->description ?: 'Aucune description' }}

                                        </div>

                                    </td>


                                    {{-- Permissions --}}

                                    <td class="px-6 py-5 text-center whitespace-nowrap">

                                        <span class="inline-flex items-center px-3 py-1
                                                     rounded-full text-xs font-semibold
                                                     bg-blue-100 text-blue-700">

                                            {{ $role->permissions_count }}

                                            permission(s)

                                        </span>

                                    </td>


                                    {{-- Utilisateurs --}}

                                    <td class="px-6 py-5 text-center whitespace-nowrap">

                                        <span class="inline-flex items-center px-3 py-1
                                                     rounded-full text-xs font-semibold
                                                     bg-green-100 text-green-700">

                                            {{ $role->utilisateurs_count }}

                                            utilisateur(s)

                                        </span>

                                    </td>


                                    {{-- Actions --}}

                                    <td class="px-6 py-5 whitespace-nowrap">

                                        <div class="flex items-center justify-end gap-2">


                                            {{-- Voir --}}

                                            <a href="{{ route('roles.show', $role) }}"
                                               class="inline-flex items-center px-3 py-2
                                                      bg-gray-100 text-gray-700 rounded-lg
                                                      text-xs font-semibold
                                                      hover:bg-gray-200 transition">

                                                👁️
                                                <span class="ml-1">
                                                    Voir
                                                </span>

                                            </a>


                                            {{-- Modifier permissions --}}

                                            <a href="{{ route('roles.edit', $role) }}"
                                               class="inline-flex items-center px-3 py-2
                                                      bg-indigo-100 text-indigo-700 rounded-lg
                                                      text-xs font-semibold
                                                      hover:bg-indigo-200 transition">

                                                ⚙️
                                                <span class="ml-1">
                                                    Permissions
                                                </span>

                                            </a>


                                            {{-- Supprimer --}}

                                            @if($role->nom !== 'Administrateur')

                                                <form method="POST"
                                                      action="{{ route('roles.destroy', $role) }}"
                                                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce rôle ?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-2
                                                                   bg-red-100 text-red-700 rounded-lg
                                                                   text-xs font-semibold
                                                                   hover:bg-red-200 transition">

                                                        🗑️
                                                        <span class="ml-1">
                                                            Supprimer
                                                        </span>

                                                    </button>

                                                </form>

                                            @endif


                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-12 text-center">

                    <div class="text-5xl mb-4">
                        👤
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        Aucun rôle trouvé
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Commencez par créer un rôle.
                    </p>

                    <div class="mt-5">

                        <a href="{{ route('roles.create') }}"
                           class="inline-flex items-center px-4 py-2
                                  bg-gray-800 text-white rounded-lg
                                  text-sm font-semibold
                                  hover:bg-gray-700 transition">

                            + Créer un rôle

                        </a>

                    </div>

                </div>

            @endif


        </div>

    </div>

</div>

</x-app-layout>

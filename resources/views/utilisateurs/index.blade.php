<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Gestion des utilisateurs
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des comptes utilisateurs, établissements et rôles
                </p>
            </div>

            <a
                href="{{ route('utilisateurs.create') }}"
                class="inline-flex items-center px-4 py-2
                       bg-gray-800 text-white rounded-lg
                       hover:bg-gray-900 transition"
            >
                + Ajouter un utilisateur
            </a>

        </div>

    </x-slot>


    <div class="py-6 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- =====================================================
                 MESSAGES
            ====================================================== --}}

            @if(session('success'))

                <div class="mb-5 p-4 rounded-lg
                            bg-green-100 border border-green-300
                            text-green-800">

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="mb-5 p-4 rounded-lg
                            bg-red-100 border border-red-300
                            text-red-800">

                    {{ session('error') }}

                </div>

            @endif


            {{-- =====================================================
                 STATISTIQUES
            ====================================================== --}}

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                {{-- TOTAL --}}

                <div class="bg-white rounded-xl shadow-sm
                            border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Total utilisateurs
                    </p>

                    <p class="text-3xl font-bold text-gray-900 mt-1">
                        {{ $utilisateurs->count() }}
                    </p>

                </div>


                {{-- ACTIFS --}}

                <div class="bg-white rounded-xl shadow-sm
                            border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Utilisateurs actifs
                    </p>

                    <p class="text-3xl font-bold text-green-600 mt-1">

                        {{ $utilisateurs->where('statut', 'ACTIF')->count() }}

                    </p>

                </div>


                {{-- BLOQUÉS --}}

                <div class="bg-white rounded-xl shadow-sm
                            border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Utilisateurs bloqués
                    </p>

                    <p class="text-3xl font-bold text-red-600 mt-1">

                        {{ $utilisateurs->where('statut', 'BLOQUE')->count() }}

                    </p>

                </div>

            </div>


            {{-- =====================================================
                 TABLEAU
            ====================================================== --}}

            <div class="bg-white rounded-xl shadow-sm
                        border border-gray-200 overflow-hidden">


                {{-- EN-TÊTE DU TABLEAU --}}

                <div class="px-6 py-4 border-b border-gray-200">

                    <h3 class="font-semibold text-gray-800">

                        Liste des utilisateurs

                    </h3>

                </div>


                @if($utilisateurs->count())


                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">


                            {{-- HEADER --}}

                            <thead class="bg-gray-800 text-white">

                                <tr>

                                    <th class="px-4 py-3 text-left">
                                        Utilisateur
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Email
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Établissement
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Rôle
                                    </th>

                                    <th class="px-4 py-3 text-center">
                                        Statut
                                    </th>

                                    <th class="px-4 py-3 text-center">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            {{-- BODY --}}

                            <tbody class="divide-y divide-gray-200">

                                @foreach($utilisateurs as $utilisateur)

                                    <tr class="hover:bg-gray-50">


                                        {{-- UTILISATEUR --}}

                                        <td class="px-4 py-4">

                                            <div class="font-semibold text-gray-900">

                                                {{ $utilisateur->nom }}

                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">

                                                ID :
                                                {{ $utilisateur->id_utilisateur }}

                                            </div>

                                        </td>


                                        {{-- EMAIL --}}

                                        <td class="px-4 py-4 text-gray-700">

                                            {{ $utilisateur->email }}

                                        </td>


                                        {{-- ÉTABLISSEMENT --}}

                                        <td class="px-4 py-4">

                                            @if($utilisateur->etablissement)

                                                <div class="font-medium text-gray-800">

                                                    {{ $utilisateur->etablissement->nom }}

                                                </div>

                                                @if($utilisateur->etablissement->ville)

                                                    <div class="text-xs text-gray-500">

                                                        {{ $utilisateur->etablissement->ville }}

                                                    </div>

                                                @endif

                                            @else

                                                <span class="text-gray-400">
                                                    Tous établissements
                                                </span>

                                            @endif

                                        </td>


                                        {{-- RÔLES --}}

                                        <td class="px-4 py-4">

                                            <div class="flex flex-wrap gap-1">

                                                @forelse($utilisateur->roles as $role)

                                                    <span
                                                        class="inline-flex items-center
                                                               px-2.5 py-1 rounded-full
                                                               text-xs font-semibold
                                                               bg-gray-100 text-gray-700"
                                                    >

                                                        {{ $role->nom }}

                                                    </span>

                                                @empty

                                                    <span class="text-gray-400">
                                                        Aucun rôle
                                                    </span>

                                                @endforelse

                                            </div>

                                        </td>


                                        {{-- STATUT --}}

                                        <td class="px-4 py-4 text-center">

                                            @if($utilisateur->statut === 'ACTIF')

                                                <span
                                                    class="inline-flex px-3 py-1
                                                           rounded-full text-xs
                                                           font-semibold
                                                           bg-green-100 text-green-700"
                                                >
                                                    Actif
                                                </span>

                                            @elseif($utilisateur->statut === 'INACTIF')

                                                <span
                                                    class="inline-flex px-3 py-1
                                                           rounded-full text-xs
                                                           font-semibold
                                                           bg-gray-100 text-gray-700"
                                                >
                                                    Inactif
                                                </span>

                                            @else

                                                <span
                                                    class="inline-flex px-3 py-1
                                                           rounded-full text-xs
                                                           font-semibold
                                                           bg-red-100 text-red-700"
                                                >
                                                    Bloqué
                                                </span>

                                            @endif

                                        </td>


                                        {{-- ACTIONS --}}

                                        <td class="px-4 py-4">

                                            <div class="flex items-center
                                                        justify-center gap-2">


                                                {{-- VOIR --}}

                                                <a
                                                    href="{{ route(
                                                        'utilisateurs.show',
                                                        $utilisateur
                                                    ) }}"
                                                    class="px-3 py-1.5
                                                           bg-gray-100
                                                           text-gray-700
                                                           rounded-lg
                                                           hover:bg-gray-200"
                                                >

                                                    Voir

                                                </a>


                                                {{-- MODIFIER --}}

                                                <a
                                                    href="{{ route(
                                                        'utilisateurs.edit',
                                                        $utilisateur
                                                    ) }}"
                                                    class="px-3 py-1.5
                                                           bg-gray-800
                                                           text-white
                                                           rounded-lg
                                                           hover:bg-gray-900"
                                                >

                                                    Modifier

                                                </a>


                                                {{-- SUPPRIMER --}}

                                                @if(
                                                    auth()->user()->id_utilisateur
                                                    !==
                                                    $utilisateur->id_utilisateur
                                                )

                                                    <form
                                                        action="{{ route(
                                                            'utilisateurs.destroy',
                                                            $utilisateur
                                                        ) }}"
                                                        method="POST"
                                                        onsubmit="return confirm(
                                                            'Voulez-vous vraiment supprimer cet utilisateur ?'
                                                        )"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="px-3 py-1.5
                                                                   bg-red-600
                                                                   text-white
                                                                   rounded-lg
                                                                   hover:bg-red-700"
                                                        >

                                                            Supprimer

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


                    {{-- AUCUN UTILISATEUR --}}

                    <div class="py-16 text-center">

                        <div class="text-5xl mb-4">
                            👤
                        </div>

                        <h3 class="text-lg font-semibold text-gray-700">

                            Aucun utilisateur

                        </h3>

                        <p class="text-sm text-gray-500 mt-1">

                            Aucun compte utilisateur n'a encore été enregistré.

                        </p>

                        <a
                            href="{{ route('utilisateurs.create') }}"
                            class="inline-flex mt-5 px-4 py-2
                                   bg-gray-800 text-white
                                   rounded-lg hover:bg-gray-900"
                        >

                            Ajouter le premier utilisateur

                        </a>

                    </div>


                @endif


            </div>

        </div>

    </div>

</x-app-layout>
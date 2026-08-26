<x-app-layout>

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Gestion des établissements
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des écoles enregistrées dans le système
                </p>
            </div>

            <a href="{{ route('etablissements.create') }}"
               class="inline-flex items-center px-4 py-2
                      bg-gray-800 text-white text-sm font-semibold
                      rounded-lg hover:bg-gray-900
                      transition">

                <span class="mr-2">+</span>

                Ajouter une école

            </a>

        </div>

    </x-slot>


    {{-- ========================================================= --}}
    {{-- CONTENU --}}
    {{-- ========================================================= --}}

    <div class="py-6 bg-gray-100 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- ================================================= --}}
            {{-- MESSAGE DE SUCCÈS --}}
            {{-- ================================================= --}}

            @if(session('success'))

                <div class="mb-5 p-4 rounded-lg
                            bg-green-100 border border-green-300
                            text-green-800">

                    <div class="flex items-center">

                        <span class="mr-2">✓</span>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- MESSAGE D'ERREUR --}}
            {{-- ================================================= --}}

            @if(session('error'))

                <div class="mb-5 p-4 rounded-lg
                            bg-red-100 border border-red-300
                            text-red-800">

                    <div class="flex items-center">

                        <span class="mr-2">⚠</span>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- CARTE PRINCIPALE --}}
            {{-- ================================================= --}}

            <div class="bg-white rounded-xl shadow-sm
                        border border-gray-200 overflow-hidden">


                {{-- ================================================= --}}
                {{-- BARRE SUPÉRIEURE --}}
                {{-- ================================================= --}}

                <div class="px-6 py-4 border-b border-gray-200
                            flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Établissements
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">

                            {{ $etablissements->count() }}

                            établissement(s) enregistré(s)

                        </p>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TABLEAU --}}
                {{-- ================================================= --}}

                @if($etablissements->count() > 0)

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            {{-- ================================================= --}}
                            {{-- EN-TÊTE DU TABLEAU --}}
                            {{-- ================================================= --}}

                            <thead class="bg-gray-800 text-white">

                                <tr>

                                    <th class="px-4 py-3 text-left">
                                        École
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Code
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Localisation
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Directeur
                                    </th>

                                    <th class="px-4 py-3 text-left">
                                        Téléphone
                                    </th>

                                    <th class="px-4 py-3 text-center">
                                        Statut
                                    </th>

                                    <th class="px-4 py-3 text-center">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            {{-- ================================================= --}}
                            {{-- CORPS --}}
                            {{-- ================================================= --}}

                            <tbody class="divide-y divide-gray-200">

                                @foreach($etablissements as $etablissement)

                                    <tr class="hover:bg-gray-50 transition">


                                        {{-- ================================================= --}}
                                        {{-- ÉCOLE --}}
                                        {{-- ================================================= --}}

                                        <td class="px-4 py-4">

                                            <div class="flex items-center gap-3">


                                                {{-- LOGO --}}

                                                <div class="flex-shrink-0">

                                                    @if($etablissement->logo)

                                                        <img
                                                            src="{{ asset('storage/' . $etablissement->logo) }}"
                                                            alt="Logo"
                                                            class="w-12 h-12 object-contain
                                                                   rounded-lg border
                                                                   border-gray-200
                                                                   bg-white"
                                                        >

                                                    @else

                                                        <div
                                                            class="w-12 h-12
                                                                   rounded-lg
                                                                   border border-gray-300
                                                                   bg-gray-100
                                                                   flex items-center
                                                                   justify-center">

                                                            <span class="text-xs
                                                                         text-gray-400
                                                                         font-semibold">

                                                                LOGO

                                                            </span>

                                                        </div>

                                                    @endif

                                                </div>


                                                {{-- NOM --}}

                                                <div>

                                                    <p class="font-bold
                                                              text-gray-900">

                                                        {{ $etablissement->nom }}

                                                    </p>

                                                    @if($etablissement->type)

                                                        <p class="text-xs
                                                                  text-gray-500 mt-1">

                                                            {{ $etablissement->type }}

                                                        </p>

                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- CODE --}}
                                        {{-- ================================================= --}}

                                        <td class="px-4 py-4">

                                            <span class="font-semibold
                                                         text-gray-700">

                                                {{ $etablissement->code ?? '—' }}

                                            </span>

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- LOCALISATION --}}
                                        {{-- ================================================= --}}

                                        <td class="px-4 py-4">

                                            <div class="text-gray-800">

                                                {{ $etablissement->ville ?? '—' }}

                                            </div>

                                            @if($etablissement->commune)

                                                <div class="text-xs
                                                            text-gray-500 mt-1">

                                                    {{ $etablissement->commune }}

                                                </div>

                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- DIRECTEUR --}}
                                        {{-- ================================================= --}}

                                        <td class="px-4 py-4">

                                            <span class="text-gray-800">

                                                {{ $etablissement->directeur ?? '—' }}

                                            </span>

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- TÉLÉPHONE --}}
                                        {{-- ================================================= --}}

                                        <td class="px-4 py-4">

                                            <span class="text-gray-700">

                                                {{ $etablissement->telephone ?? '—' }}

                                            </span>

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- STATUT --}}
                                        {{-- ================================================= --}}

                                        <td class="px-4 py-4 text-center">

                                            @if(
                                                in_array(
                                                    strtoupper($etablissement->statut ?? ''),
                                                    ['ACTIF', 'ACTIVE']
                                                )
                                            )

                                                <span
                                                    class="inline-flex items-center
                                                           px-3 py-1 rounded-full
                                                           text-xs font-semibold
                                                           bg-green-100
                                                           text-green-700">

                                                    Actif

                                                </span>

                                            @else

                                                <span
                                                    class="inline-flex items-center
                                                           px-3 py-1 rounded-full
                                                           text-xs font-semibold
                                                           bg-gray-100
                                                           text-gray-600">

                                                    {{ $etablissement->statut ?? 'Inactif' }}

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ================================================= --}}
                                        {{-- ACTIONS --}}
                                        {{-- ================================================= --}}

                                        <td class="px-4 py-4">

                                            <div class="flex items-center
                                                        justify-center gap-2">


                                                {{-- VOIR --}}

                                                <a
                                                    href="{{ route(
                                                        'etablissements.show',
                                                        $etablissement
                                                    ) }}"
                                                    title="Voir"
                                                    class="inline-flex items-center
                                                           justify-center
                                                           w-9 h-9
                                                           rounded-lg
                                                           bg-gray-100
                                                           text-gray-700
                                                           hover:bg-gray-200">

                                                    👁

                                                </a>


                                                {{-- MODIFIER --}}

                                                <a
                                                    href="{{ route(
                                                        'etablissements.edit',
                                                        $etablissement
                                                    ) }}"
                                                    title="Modifier"
                                                    class="inline-flex items-center
                                                           justify-center
                                                           w-9 h-9
                                                           rounded-lg
                                                           bg-gray-100
                                                           text-gray-700
                                                           hover:bg-gray-200">

                                                    ✏

                                                </a>


                                                {{-- SUPPRIMER --}}

                                                <form
                                                    action="{{ route(
                                                        'etablissements.destroy',
                                                        $etablissement
                                                    ) }}"
                                                    method="POST"
                                                    onsubmit="return confirm(
                                                        'Voulez-vous vraiment supprimer cet établissement ?'
                                                    )"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        title="Supprimer"
                                                        class="inline-flex items-center
                                                               justify-center
                                                               w-9 h-9
                                                               rounded-lg
                                                               bg-red-50
                                                               text-red-600
                                                               hover:bg-red-100">

                                                        🗑

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else


                    {{-- ================================================= --}}
                    {{-- AUCUNE ÉCOLE --}}
                    {{-- ================================================= --}}

                    <div class="px-6 py-16 text-center">


                        <div class="text-5xl mb-4">
                            🏫
                        </div>


                        <h3 class="text-lg font-bold
                                   text-gray-800">

                            Aucun établissement

                        </h3>


                        <p class="text-sm text-gray-500
                                  mt-2">

                            Aucun établissement scolaire
                            n'est encore enregistré.

                        </p>


                        <a
                            href="{{ route('etablissements.create') }}"
                            class="inline-flex items-center
                                   mt-5 px-4 py-2
                                   bg-gray-800 text-white
                                   rounded-lg
                                   hover:bg-gray-900">

                            <span class="mr-2">+</span>

                            Ajouter une école

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
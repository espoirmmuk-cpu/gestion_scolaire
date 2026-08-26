<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Détails de l'établissement
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $etablissement->nom }}
                </p>
            </div>

            <div class="flex gap-2">

                <a href="{{ route('etablissements.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    ← Retour
                </a>

                <a href="{{ route('etablissements.edit', $etablissement) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    ✏️ Modifier
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-6 bg-gray-100">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Message succès --}}

            @if(session('success'))

                <div class="mb-6 p-4 bg-green-100 border border-green-300
                            text-green-800 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            <div class="bg-white shadow-lg rounded-xl overflow-hidden">


                {{-- =====================================================
                     EN-TÊTE
                ====================================================== --}}

                <div class="p-6 border-b border-gray-200">

                    <div class="flex flex-col md:flex-row
                                items-center gap-6">

                        {{-- LOGO --}}

                        <div class="flex-shrink-0">

                            @if($etablissement->logo)

                                <img
                                    src="{{ asset('storage/' . $etablissement->logo) }}"
                                    alt="Logo"
                                    class="w-32 h-32 object-contain
                                           border rounded-lg p-2"
                                >

                            @else

                                <div class="w-32 h-32
                                            border-2 border-gray-300
                                            rounded-lg
                                            flex items-center justify-center
                                            text-gray-400">

                                    Aucun logo

                                </div>

                            @endif

                        </div>


                        {{-- NOM --}}

                        <div class="flex-1">

                            <h1 class="text-2xl font-bold
                                       uppercase text-gray-900">

                                {{ $etablissement->nom }}

                            </h1>

                            <p class="text-gray-500 mt-1">

                                Code :
                                <span class="font-semibold">
                                    {{ $etablissement->code }}
                                </span>

                            </p>

                            <div class="mt-3">

                                @if($etablissement->statut === 'ACTIF')

                                    <span class="px-3 py-1 text-sm
                                                 rounded-full
                                                 bg-green-100
                                                 text-green-700">

                                        ACTIF

                                    </span>

                                @else

                                    <span class="px-3 py-1 text-sm
                                                 rounded-full
                                                 bg-red-100
                                                 text-red-700">

                                        {{ $etablissement->statut }}

                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     INFORMATIONS
                ====================================================== --}}

                <div class="p-6">

                    <h3 class="text-lg font-bold text-gray-800 mb-4">

                        Informations de l'établissement

                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-2
                                lg:grid-cols-3 gap-5">


                        {{-- TYPE --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Type

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->type ?? '—' }}

                            </p>

                        </div>


                        {{-- PROVINCE --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Province

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->province ?? '—' }}

                            </p>

                        </div>


                        {{-- VILLE --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Ville

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->ville ?? '—' }}

                            </p>

                        </div>


                        {{-- COMMUNE --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Commune

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->commune ?? '—' }}

                            </p>

                        </div>


                        {{-- ADRESSE --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Adresse

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->adresse ?? '—' }}

                            </p>

                        </div>


                        {{-- TÉLÉPHONE --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Téléphone

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->telephone ?? '—' }}

                            </p>

                        </div>


                        {{-- EMAIL --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Email

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->email ?? '—' }}

                            </p>

                        </div>


                        {{-- DIRECTEUR --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Directeur

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->directeur ?? '—' }}

                            </p>

                        </div>


                        {{-- DATE CRÉATION --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase
                                      text-gray-500 font-semibold">

                                Date de création

                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $etablissement->date_creation
                                    ? $etablissement->date_creation->format('d/m/Y H:i')
                                    : '—'
                                }}

                            </p>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     PERSONNEL
                ====================================================== --}}

                <div class="p-6 border-t border-gray-200">

                    <div class="flex items-center justify-between mb-4">

                        <h3 class="text-lg font-bold text-gray-800">

                            Personnel de l'établissement

                        </h3>

                        <span class="px-3 py-1 bg-gray-100
                                     text-gray-700 rounded-full text-sm">

                            {{ $etablissement->personnels->count() }}
                            personnel(s)

                        </span>

                    </div>


                    @if($etablissement->personnels->count() > 0)

                        <div class="overflow-x-auto">

                            <table class="w-full border-collapse">

                                <thead>

                                    <tr class="bg-gray-800 text-white">

                                        <th class="px-4 py-3 text-left">
                                            Nom
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Fonction
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Téléphone
                                        </th>

                                        <th class="px-4 py-3 text-left">
                                            Statut
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($etablissement->personnels as $personnel)

                                        <tr class="border-b
                                                   hover:bg-gray-50">

                                            <td class="px-4 py-3">

                                                <span class="font-semibold">

                                                    {{ $personnel->nom }}
                                                    {{ $personnel->postnom ?? '' }}
                                                    {{ $personnel->prenom ?? '' }}

                                                </span>

                                            </td>

                                            <td class="px-4 py-3">

                                                {{ $personnel->fonction ?? '—' }}

                                            </td>

                                            <td class="px-4 py-3">

                                                {{ $personnel->telephone ?? '—' }}

                                            </td>

                                            <td class="px-4 py-3">

                                                {{ $personnel->statut ?? '—' }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="p-8 text-center
                                    border border-dashed
                                    border-gray-300 rounded-lg">

                            <p class="text-gray-500">

                                Aucun personnel associé à cet établissement.

                            </p>

                        </div>

                    @endif

                </div>


            </div>

        </div>

    </div>

</x-app-layout>
<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Années scolaires
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des années scolaires de l'établissement
                </p>
            </div>

            <a
                href="{{ route('annees-scolaires.create') }}"
                class="inline-flex items-center justify-center px-4 py-2
                       bg-gray-800 border border-transparent rounded-md
                       font-semibold text-xs text-white uppercase tracking-widest
                       hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900
                       focus:outline-none focus:ring-2 focus:ring-gray-500
                       focus:ring-offset-2 transition ease-in-out duration-150"
            >
                + Nouvelle année scolaire
            </a>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200
                            text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200
                            text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif


            {{-- Erreurs --}}
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200
                            text-red-800 px-4 py-3 rounded-lg">

                    <div class="font-semibold mb-2">
                        Impossible d'effectuer cette opération :
                    </div>

                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif


            {{-- Tableau --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider">
                                        #
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider">
                                        Année scolaire
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider">
                                        Date de début
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider">
                                        Date de fin
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider">
                                        État
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @forelse($anneesScolaires as $annee)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-6 py-4 whitespace-nowrap
                                                   text-sm text-gray-500">
                                            {{ $loop->iteration }}
                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $annee->libelle }}
                                            </div>

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap
                                                   text-sm text-gray-600">

                                            {{ \Carbon\Carbon::parse($annee->date_debut)->format('d/m/Y') }}

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap
                                                   text-sm text-gray-600">

                                            {{ \Carbon\Carbon::parse($annee->date_fin)->format('d/m/Y') }}

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if((int) $annee->est_active === 1)

                                                <span class="inline-flex items-center
                                                             px-2.5 py-0.5 rounded-full
                                                             text-xs font-medium
                                                             bg-green-100 text-green-800">
                                                    Active
                                                </span>

                                            @else

                                                <span class="inline-flex items-center
                                                             px-2.5 py-0.5 rounded-full
                                                             text-xs font-medium
                                                             bg-gray-100 text-gray-700">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap
                                                   text-right text-sm font-medium">

                                            <div class="flex justify-end items-center gap-2">

                                                {{-- Modifier --}}
                                                <a
                                                    href="{{ route('annees-scolaires.edit', $annee->id_annee_scolaire) }}"
                                                    class="px-3 py-1.5 rounded-md
                                                           bg-gray-600 text-white
                                                           hover:bg-gray-700 transition"
                                                >
                                                    Modifier
                                                </a>


                                                {{-- Supprimer --}}
                                                @if((int) $annee->est_active !== 1)

                                                    <form
                                                        action="{{ route('annees-scolaires.destroy', $annee->id_annee_scolaire) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Voulez-vous vraiment supprimer cette année scolaire ?');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="px-3 py-1.5 rounded-md
                                                                   bg-red-600 text-white
                                                                   hover:bg-red-700 transition"
                                                        >
                                                            Supprimer
                                                        </button>

                                                    </form>

                                                @else

                                                    <span
                                                        class="px-3 py-1.5 rounded-md
                                                               bg-gray-100 text-gray-400
                                                               cursor-not-allowed"
                                                        title="Une année scolaire active ne peut pas être supprimée."
                                                    >
                                                        Supprimer
                                                    </span>

                                                @endif

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="6"
                                            class="px-6 py-12 text-center"
                                        >

                                            <div class="text-gray-500">

                                                <div class="text-lg font-semibold mb-2">
                                                    Aucune année scolaire
                                                </div>

                                                <p class="text-sm mb-4">
                                                    Aucune année scolaire n'a encore été enregistrée.
                                                </p>

                                                <a
                                                    href="{{ route('annees-scolaires.create') }}"
                                                    class="inline-flex items-center
                                                           px-4 py-2 bg-gray-800
                                                           text-white rounded-md
                                                           hover:bg-gray-700"
                                                >
                                                    Ajouter une année scolaire
                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
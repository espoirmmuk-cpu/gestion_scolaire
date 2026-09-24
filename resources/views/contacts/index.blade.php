@extends('layouts.app')

@section('content')

    <div class="p-6">

        {{-- EN-TÊTE --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    📩 Messages de contact
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Messages reçus depuis la page « Nous contacter »
                </p>
            </div>

            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-4 py-2
                             bg-blue-100 text-blue-700 rounded-lg
                             font-semibold">
                    {{ $nombreNonLus }} non lu(s)
                </span>
            </div>

        </div>


        {{-- MESSAGE SUCCÈS --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-300
                        text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif


        {{-- MESSAGE ERREUR --}}
        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-300
                        text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif


        {{-- FILTRES --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">

            <form method="GET" action="{{ route('contacts.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- RECHERCHE --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Recherche
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nom, email, sujet..."
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                    </div>


                    {{-- ÉTAT --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            État
                        </label>

                        <select
                            name="etat"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500
                                   focus:ring-blue-500"
                        >

                            <option value="">
                                Tous les messages
                            </option>

                            <option
                                value="non_lu"
                                {{ request('etat') === 'non_lu' ? 'selected' : '' }}
                            >
                                🔵 Non lus
                            </option>

                            <option
                                value="lu"
                                {{ request('etat') === 'lu' ? 'selected' : '' }}
                            >
                                🟢 Lus
                            </option>

                        </select>

                    </div>


                    {{-- BOUTONS --}}
                    <div class="flex items-end gap-2">

                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-blue-600 text-white
                                   rounded-lg hover:bg-blue-700 transition"
                        >
                            Rechercher
                        </button>

                        <a
                            href="{{ route('contacts.index') }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700
                                   rounded-lg hover:bg-gray-200 transition"
                        >
                            Réinitialiser
                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- TABLEAU --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-500 uppercase">
                                État
                            </th>

                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-500 uppercase">
                                Expéditeur
                            </th>

                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-500 uppercase">
                                Sujet
                            </th>

                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-500 uppercase">
                                Date
                            </th>

                            <th class="px-6 py-3 text-right text-xs
                                       font-semibold text-gray-500 uppercase">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200">

                        @forelse($contacts as $contact)

                            <tr class="{{ !$contact->lu ? 'bg-blue-50' : '' }}">

                                {{-- ÉTAT --}}
                                <td class="px-6 py-4">

                                    @if(!$contact->lu)

                                        <span class="inline-flex px-2.5 py-1
                                                     text-xs font-semibold
                                                     bg-blue-100 text-blue-700
                                                     rounded-full">
                                            🔵 Non lu
                                        </span>

                                    @else

                                        <span class="inline-flex px-2.5 py-1
                                                     text-xs font-semibold
                                                     bg-green-100 text-green-700
                                                     rounded-full">
                                            🟢 Lu
                                        </span>

                                    @endif

                                </td>


                                {{-- EXPÉDITEUR --}}
                                <td class="px-6 py-4">

                                    <div class="font-semibold text-gray-800">
                                        {{ $contact->nom }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $contact->email }}
                                    </div>

                                </td>


                                {{-- SUJET --}}
                                <td class="px-6 py-4">

                                    <span class="font-medium text-gray-700">
                                        {{ $contact->sujet }}
                                    </span>

                                </td>


                                {{-- DATE --}}
                                <td class="px-6 py-4 text-sm text-gray-500">

                                    {{ $contact->created_at?->format('d/m/Y H:i') }}

                                </td>


                                {{-- ACTION --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('contacts.show', $contact) }}"
                                        class="inline-flex items-center px-3 py-2
                                               bg-blue-600 text-white rounded-lg
                                               hover:bg-blue-700"
                                    >
                                        👁️ Voir
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-12 text-center">

                                    <div class="text-4xl mb-3">
                                        📭
                                    </div>

                                    <p class="text-gray-500">
                                        Aucun message trouvé.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if($contacts->hasPages())

                <div class="px-6 py-4 border-t">

                    {{ $contacts->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection
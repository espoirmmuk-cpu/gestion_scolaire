<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Détails de l'utilisateur
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informations détaillées du compte
                </p>
            </div>

            <div class="flex gap-2">

                <a href="{{ route('utilisateurs.edit', $utilisateur) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    ✏️ Modifier
                </a>

                <a href="{{ route('utilisateurs.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    ← Retour
                </a>

            </div>

        </div>
    </x-slot>


    <div class="py-6 bg-gray-100">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-xl overflow-hidden">

                {{-- En-tête --}}

                <div class="bg-gray-800 px-6 py-5 text-white">

                    <div class="flex items-center justify-between">

                        <div>

                            <h1 class="text-xl font-bold">
                                {{ $utilisateur->nom }}
                            </h1>

                            <p class="text-gray-300 text-sm mt-1">
                                {{ $utilisateur->email }}
                            </p>

                        </div>

                        <div>

                            @if($utilisateur->statut === 'ACTIF')

                                <span class="px-3 py-1 text-xs font-semibold
                                             rounded-full bg-green-100 text-green-800">
                                    ACTIF
                                </span>

                            @elseif($utilisateur->statut === 'INACTIF')

                                <span class="px-3 py-1 text-xs font-semibold
                                             rounded-full bg-gray-100 text-gray-800">
                                    INACTIF
                                </span>

                            @else

                                <span class="px-3 py-1 text-xs font-semibold
                                             rounded-full bg-red-100 text-red-800">
                                    BLOQUÉ
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Informations générales --}}

                <div class="p-6">

                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Informations générales
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nom --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Nom
                            </p>

                            <p class="font-semibold text-gray-900 mt-1">
                                {{ $utilisateur->nom }}
                            </p>

                        </div>


                        {{-- Email --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Adresse e-mail
                            </p>

                            <p class="font-semibold text-gray-900 mt-1">
                                {{ $utilisateur->email }}
                            </p>

                        </div>


                        {{-- Établissement --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Établissement
                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                {{ $utilisateur->etablissement->nom ?? 'Aucun établissement' }}

                            </p>

                        </div>


                        {{-- Statut --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Statut
                            </p>

                            <p class="font-semibold text-gray-900 mt-1">
                                {{ $utilisateur->statut }}
                            </p>

                        </div>


                        {{-- Dernière connexion --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Dernière connexion
                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                @if($utilisateur->derniere_connexion)

                                    {{ $utilisateur->derniere_connexion->format('d/m/Y H:i') }}

                                @else

                                    <span class="text-gray-400">
                                        Jamais connecté
                                    </span>

                                @endif

                            </p>

                        </div>


                        {{-- Date création --}}

                        <div class="border rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Date de création
                            </p>

                            <p class="font-semibold text-gray-900 mt-1">

                                @if($utilisateur->date_creation)

                                    {{ $utilisateur->date_creation->format('d/m/Y H:i') }}

                                @else

                                    —

                                @endif

                            </p>

                        </div>

                    </div>


                    {{-- Rôles --}}

                    <div class="mt-8">

                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            Rôle(s)
                        </h3>

                        <div class="border rounded-lg p-4">

                            @forelse($utilisateur->roles as $role)

                                <div class="mb-3 last:mb-0">

                                    <span class="inline-block px-3 py-1
                                                 bg-blue-100 text-blue-800
                                                 rounded-full text-sm font-semibold">

                                        {{ $role->nom }}

                                    </span>

                                    @if($role->description)

                                        <p class="text-sm text-gray-500 mt-2">
                                            {{ $role->description }}
                                        </p>

                                    @endif

                                </div>

                            @empty

                                <p class="text-gray-400">
                                    Aucun rôle attribué.
                                </p>

                            @endforelse

                        </div>

                    </div>


                    {{-- Actions --}}

                    <div class="mt-8 pt-6 border-t flex justify-between">

                        <a href="{{ route('utilisateurs.index') }}"
                           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            ← Retour à la liste
                        </a>

                        <a href="{{ route('utilisateurs.edit', $utilisateur) }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            ✏️ Modifier l'utilisateur
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
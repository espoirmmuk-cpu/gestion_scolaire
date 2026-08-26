<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-2xl text-gray-800">
                    Fiche de l'élève
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informations complètes de l'élève
                </p>
            </div>

            <div class="flex gap-2">

                <a
                    href="{{ route('eleves.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700
                           border border-gray-200 rounded-lg
                           hover:bg-gray-200 transition">
                    ← Retour
                </a>

                <a
                    href="{{ route('eleves.edit', $eleve) }}"
                    class="px-4 py-2 bg-gray-700 text-white
                           rounded-lg hover:bg-gray-800 transition">
                    Modifier
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                {{-- EN-TÊTE DE LA FICHE --}}
                <div class="px-6 py-6 border-b border-gray-200">

                    <div class="flex flex-col sm:flex-row sm:items-center gap-5">

                        {{-- Avatar --}}
                        <div class="flex-shrink-0">

                            @if($eleve->photo)

                                <img
                                    src="{{ asset('storage/' . $eleve->photo) }}"
                                    alt="Photo de {{ $eleve->nom }}"
                                    class="w-28 h-28 rounded-full object-cover
                                           border-4 border-gray-100 shadow-sm">

                            @else

                                <div
                                    class="w-28 h-28 rounded-full bg-gray-100
                                           flex items-center justify-center
                                           border-4 border-gray-50">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-14 h-14 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0
                                               3.75 3.75 0 0 1 7.5 0ZM4.5
                                               20.25a8.25 8.25 0 0 1 15 0" />

                                    </svg>

                                </div>

                            @endif

                        </div>


                        {{-- IDENTITÉ --}}
                        <div class="flex-1">

                            <div class="flex flex-wrap items-center gap-3">

                                <h3 class="text-2xl font-bold text-gray-800">

                                    {{ $eleve->nom }}
                                    {{ $eleve->postnom }}
                                    {{ $eleve->prenom }}

                                </h3>


                                @if($eleve->statut === 'ACTIF')

                                    <span
                                        class="px-3 py-1 text-xs font-semibold
                                               rounded-full bg-green-100
                                               text-green-700">
                                        Actif
                                    </span>

                                @else

                                    <span
                                        class="px-3 py-1 text-xs font-semibold
                                               rounded-full bg-red-100
                                               text-red-700">
                                        Inactif
                                    </span>

                                @endif

                            </div>


                            <p class="text-gray-500 mt-1">
                                Élève
                            </p>


                            <p class="text-sm text-gray-400 mt-2">

                                Matricule :

                                <span class="font-medium text-gray-600">
                                    {{ $eleve->matricule }}
                                </span>

                            </p>

                        </div>

                    </div>

                </div>


                {{-- INFORMATIONS --}}
                <div class="p-6">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


                        {{-- INFORMATIONS PERSONNELLES --}}
                        <div
                            class="border border-gray-200
                                   rounded-xl overflow-hidden">

                            <div
                                class="px-5 py-4 bg-gray-50
                                       border-b border-gray-200">

                                <h4 class="font-semibold text-gray-800">
                                    Informations personnelles
                                </h4>

                            </div>


                            <div class="p-5 space-y-4">


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Nom
                                    </p>

                                    <p class="mt-1 text-gray-800 font-medium">
                                        {{ $eleve->nom ?: '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Postnom
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $eleve->postnom ?: '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Prénom
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $eleve->prenom ?: '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Sexe
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $eleve->sexe === 'M' ? 'Masculin' : 'Féminin' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Date de naissance
                                    </p>

                                    <p class="mt-1 text-gray-800">

                                        {{ $eleve->date_naissance
                                            ? $eleve->date_naissance->format('d/m/Y')
                                            : '—' }}

                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Lieu de naissance
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $eleve->lieu_naissance ?: '—' }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- COORDONNÉES --}}
                        <div
                            class="border border-gray-200
                                   rounded-xl overflow-hidden">

                            <div
                                class="px-5 py-4 bg-gray-50
                                       border-b border-gray-200">

                                <h4 class="font-semibold text-gray-800">
                                    Coordonnées
                                </h4>

                            </div>


                            <div class="p-5 space-y-4">


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Matricule
                                    </p>

                                    <p class="mt-1 text-gray-800 font-medium">
                                        {{ $eleve->matricule ?: '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Téléphone
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $eleve->telephone ?: '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        E-mail
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $eleve->email ?: '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Adresse
                                    </p>

                                    <p class="mt-1 text-gray-800">
                                        {{ $eleve->adresse ?: '—' }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- STATUT --}}
                        <div
                            class="border border-gray-200
                                   rounded-xl overflow-hidden">

                            <div
                                class="px-5 py-4 bg-gray-50
                                       border-b border-gray-200">

                                <h4 class="font-semibold text-gray-800">
                                    Statut
                                </h4>

                            </div>


                            <div class="p-5">

                                @if($eleve->statut === 'ACTIF')

                                    <span
                                        class="px-3 py-1 text-xs
                                               font-semibold rounded-full
                                               bg-green-100 text-green-700">
                                        Actif
                                    </span>

                                @else

                                    <span
                                        class="px-3 py-1 text-xs
                                               font-semibold rounded-full
                                               bg-red-100 text-red-700">
                                        Inactif
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- DATES --}}
                        <div
                            class="border border-gray-200
                                   rounded-xl overflow-hidden">

                            <div
                                class="px-5 py-4 bg-gray-50
                                       border-b border-gray-200">

                                <h4 class="font-semibold text-gray-800">
                                    Informations système
                                </h4>

                            </div>


                            <div class="p-5 space-y-4">


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Date de création
                                    </p>

                                    <p class="mt-1 text-gray-800">

                                        {{ $eleve->date_creation
                                            ? $eleve->date_creation->format('d/m/Y H:i')
                                            : '—' }}

                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs uppercase
                                              tracking-wide text-gray-400">
                                        Dernière modification
                                    </p>

                                    <p class="mt-1 text-gray-800">

                                        {{ $eleve->date_modification
                                            ? $eleve->date_modification->format('d/m/Y H:i')
                                            : '—' }}

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div
                    class="px-6 py-4 bg-gray-50
                           border-t border-gray-200
                           flex flex-col sm:flex-row
                           sm:justify-end gap-3">

                    <a
                        href="{{ route('eleves.index') }}"
                        class="px-5 py-2.5 text-center
                               bg-white text-gray-700
                               border border-gray-200 rounded-lg
                               hover:bg-gray-100 transition">

                        Retour

                    </a>


                    <a
                        href="{{ route('eleves.edit', $eleve) }}"
                        class="px-5 py-2.5 text-center
                               bg-gray-700 text-white
                               rounded-lg hover:bg-gray-800 transition">

                        Modifier ce dossier

                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
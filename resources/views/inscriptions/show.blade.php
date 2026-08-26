<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Détails de l'inscription
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Consultation des informations de l'inscription de l'élève.
            </p>

        </div>

        <a href="{{ route('inscriptions.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

            ← Retour aux inscriptions

        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


        {{-- Message de succès --}}

        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">

                {{ session('success') }}

            </div>

        @endif


        {{-- Message d'erreur --}}

        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">

                {{ session('error') }}

            </div>

        @endif


        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête --}}

            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-bold text-gray-800">
                        Informations de l'inscription
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Inscription N° {{ $inscription->id_inscription }}
                    </p>

                </div>


                {{-- Statut --}}

                @php

                    $statutClasses = [

                        'INSCRIT' => 'bg-green-100 text-green-700',

                        'ABANDON' => 'bg-yellow-100 text-yellow-700',

                        'TRANSFERE' => 'bg-blue-100 text-blue-700',

                        'RADIE' => 'bg-red-100 text-red-700',

                        'DIPLOME' => 'bg-purple-100 text-purple-700',

                    ];

                @endphp


                <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $statutClasses[$inscription->statut] ?? 'bg-gray-100 text-gray-700' }}">

                    {{ $inscription->statut }}

                </span>

            </div>


            {{-- Informations de l'élève --}}

            <div class="p-6">

                <h4 class="text-base font-bold text-gray-800 mb-4">
                    Élève
                </h4>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            Matricule
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $inscription->eleve->matricule ?? '—' }}

                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Nom complet
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $inscription->eleve->nom ?? '' }}

                            {{ $inscription->eleve->postnom ?? '' }}

                            {{ $inscription->eleve->prenom ?? '' }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- Informations scolaires --}}

            <div class="px-6 pb-6">

                <h4 class="text-base font-bold text-gray-800 mb-4">
                    Informations scolaires
                </h4>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- Classe --}}

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Classe
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $inscription->classe->libelle ?? '—' }}

                        </p>

                    </div>


                    {{-- Année scolaire --}}

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Année scolaire
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $inscription->anneeScolaire->libelle ?? '—' }}

                        </p>

                    </div>


                    {{-- Date inscription --}}

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Date d'inscription
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $inscription->date_inscription
                                ? \Carbon\Carbon::parse($inscription->date_inscription)->format('d/m/Y')
                                : '—'
                            }}

                        </p>

                    </div>


                    {{-- Statut --}}

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Statut
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ $inscription->statut }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- Observation --}}

            <div class="px-6 pb-6">

                <h4 class="text-base font-bold text-gray-800 mb-4">
                    Observation
                </h4>


                <div class="bg-gray-50 rounded-lg p-4">

                    @if($inscription->observation)

                        <p class="text-gray-700 whitespace-pre-line">

                            {{ $inscription->observation }}

                        </p>

                    @else

                        <p class="text-gray-400 italic">
                            Aucune observation.
                        </p>

                    @endif

                </div>

            </div>


            {{-- Frais scolaires --}}

            <div class="px-6 pb-6">

                <div class="flex items-center justify-between mb-4">

                    <div>

                        <h4 class="text-base font-bold text-gray-800">
                            Frais scolaires
                        </h4>

                        <p class="text-sm text-gray-500 mt-1">
                            Frais générés pour cette inscription.
                        </p>

                    </div>

                </div>


                @if($inscription->fraisEleves && $inscription->fraisEleves->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Frais
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Montant
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Devise
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Statut
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-100">

                                @foreach($inscription->fraisEleves as $frais)

                                    <tr class="hover:bg-gray-50">

                                        <td class="px-4 py-3 text-sm text-gray-800">

                                            {{ $frais->libelle
                                                ?? $frais->categorieFrais->libelle
                                                ?? '—'
                                            }}

                                        </td>


                                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">

                                            {{ number_format($frais->montant ?? 0, 2, ',', ' ') }}

                                        </td>


                                        <td class="px-4 py-3 text-sm text-gray-600">

                                            {{ $frais->devise ?? '—' }}

                                        </td>


                                        <td class="px-4 py-3">

                                            @if(isset($frais->statut))

                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                                    {{ $frais->statut === 'PAYE'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-yellow-100 text-yellow-700'
                                                    }}">

                                                    {{ $frais->statut }}

                                                </span>

                                            @else

                                                <span class="text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="bg-gray-50 rounded-lg p-5 text-center">

                        <p class="text-gray-400 italic">
                            Aucun frais scolaire associé à cette inscription.
                        </p>

                    </div>

                @endif

            </div>


            {{-- Boutons d'action --}}

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">

                <a href="{{ route('inscriptions.index') }}"
                   class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">

                    ← Retour

                </a>


                <div class="flex items-center gap-3">

                    <a href="{{ route('inscriptions.edit', $inscription->id_inscription) }}"
                       class="px-5 py-2.5 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">

                        ✎ Modifier

                    </a>

                </div>

            </div>


        </div>

    </div>

</div>

</x-app-layout>

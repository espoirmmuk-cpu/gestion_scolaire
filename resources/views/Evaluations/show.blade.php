<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails de l'évaluation
            </h2>

            <a
                href="{{ route('evaluations.index') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
            >
                Retour
            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- ===================================================== --}}
            {{-- MESSAGES --}}
            {{-- ===================================================== --}}

            @if(session('success'))

                <div class="mb-6 p-4 bg-green-100 border border-green-300
                            text-green-800 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="mb-6 p-4 bg-red-100 border border-red-300
                            text-red-800 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            @if($errors->any())

                <div class="mb-6 p-4 bg-red-100 border border-red-300
                            text-red-800 rounded-lg">

                    <p class="font-semibold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </p>

                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- ===================================================== --}}
            {{-- INFORMATIONS DE L'ÉVALUATION --}}
            {{-- ===================================================== --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h1 class="text-2xl font-bold text-gray-800">

                                {{ $evaluation->libelle }}

                            </h1>

                            <p class="text-sm text-gray-500 mt-1">

                                Informations de l'évaluation

                            </p>

                        </div>


                        <a
                            href="{{ route('evaluations.edit', $evaluation) }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg
                                   hover:bg-gray-800"
                        >

                            Modifier

                        </a>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">


                        {{-- Année scolaire --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Année scolaire
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $evaluation->anneeScolaire?->libelle ?? '—' }}

                            </p>

                        </div>


                        {{-- Classe --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Classe
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $evaluation->classe?->libelle ?? '—' }}

                            </p>

                        </div>


                        {{-- Matière --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Matière
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $evaluation->matiere?->libelle ?? '—' }}

                            </p>

                        </div>


                        {{-- Période --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Période
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $evaluation->periode?->libelle ?? '—' }}

                            </p>

                        </div>


                        {{-- Type --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Type d'évaluation
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $evaluation->type_evaluation ?? '—' }}

                            </p>

                        </div>


                        {{-- Note maximale --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Note maximale
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ number_format(
                                    $evaluation->note_maximale,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                            </p>

                        </div>


                        {{-- Date --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Date
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                @if($evaluation->date_evaluation)

                                    {{ $evaluation->date_evaluation->format('d/m/Y') }}

                                @else

                                    —

                                @endif

                            </p>

                        </div>


                        {{-- Nombre d'élèves --}}

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Élèves inscrits
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $inscriptions->count() }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- LISTE DES ÉLÈVES ET NOTES --}}
            {{-- ===================================================== --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">


                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h2 class="text-xl font-bold text-gray-800">
                                Gestion des notes
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Saisissez les notes des élèves inscrits.
                            </p>

                        </div>


                        <div class="text-sm text-gray-600">

                            Note maximale :

                            <strong class="text-gray-800">

                                {{ number_format(
                                    $evaluation->note_maximale,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                            </strong>

                        </div>

                    </div>



                    @if($inscriptions->count() > 0)


                        {{-- ================================================= --}}
                        {{-- FORMULAIRE GLOBAL --}}
                        {{-- ================================================= --}}

                        <form
                            action="{{ route(
                                'evaluations.notes.enregistrer',
                                $evaluation
                            ) }}"
                            method="POST"
                            id="form-notes"
                        >

                            @csrf


                            <div class="overflow-x-auto">

                                <table class="min-w-full divide-y divide-gray-200">

                                    <thead class="bg-gray-50">

                                        <tr>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">
                                                #
                                            </th>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">
                                                Matricule
                                            </th>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">
                                                Élève
                                            </th>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">
                                                Note
                                            </th>

                                            <th class="px-4 py-3 text-left text-xs
                                                       font-semibold text-gray-600 uppercase">
                                                Appréciation
                                            </th>

                                            <th class="px-4 py-3 text-center text-xs
                                                       font-semibold text-gray-600 uppercase">
                                                Action
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody class="bg-white divide-y divide-gray-200">


                                        @foreach($inscriptions as $index => $inscription)


                                            @php

                                                $eleve = $inscription->eleve;

                                                $noteExistante = $notes->get(
                                                    $inscription->id_eleve
                                                );

                                            @endphp


                                            @if($eleve)


                                                <tr class="hover:bg-gray-50">


                                                    {{-- Numéro --}}

                                                    <td class="px-4 py-4 text-sm text-gray-500">

                                                        {{ $index + 1 }}

                                                    </td>


                                                    {{-- Matricule --}}

                                                    <td class="px-4 py-4 text-sm text-gray-700">

                                                        {{ $eleve->matricule ?? '—' }}

                                                    </td>


                                                    {{-- Élève --}}

                                                    <td class="px-4 py-4">

                                                        <p class="font-semibold text-gray-800">

                                                            {{ $eleve->nom }}
                                                            {{ $eleve->postnom }}
                                                            {{ $eleve->prenom }}

                                                        </p>

                                                    </td>


                                                    {{-- Note --}}

                                                    <td class="px-4 py-4">

                                                        <div class="flex items-center gap-2">


                                                            <input
                                                                type="number"
                                                                name="notes[{{ $eleve->id_eleve }}][note]"
                                                                value="{{ old(
                                                                    'notes.' . $eleve->id_eleve . '.note',
                                                                    $noteExistante?->note
                                                                ) }}"
                                                                min="0"
                                                                max="{{ $evaluation->note_maximale }}"
                                                                step="0.01"
                                                                class="w-24 rounded-lg border-gray-300
                                                                       focus:border-gray-500
                                                                       focus:ring-gray-500"
                                                            >


                                                            <span class="text-gray-500 text-sm">

                                                                /

                                                                {{ number_format(
                                                                    $evaluation->note_maximale,
                                                                    2,
                                                                    ',',
                                                                    ' '
                                                                ) }}

                                                            </span>


                                                        </div>

                                                    </td>


                                                    {{-- Appréciation --}}

                                                    <td class="px-4 py-4">

                                                        <input
                                                            type="text"
                                                            name="notes[{{ $eleve->id_eleve }}][appreciation]"
                                                            value="{{ old(
                                                                'notes.' . $eleve->id_eleve . '.appreciation',
                                                                $noteExistante?->appreciation
                                                            ) }}"
                                                            maxlength="255"
                                                            placeholder="Appréciation"
                                                            class="w-full min-w-[180px]
                                                                   rounded-lg border-gray-300
                                                                   focus:border-gray-500
                                                                   focus:ring-gray-500"
                                                        >

                                                    </td>


                                                    {{-- Actions --}}

                                                    <td class="px-4 py-4">

                                                        <div class="flex items-center
                                                                    justify-center gap-2">


                                                            @if($noteExistante)


                                                                {{-- Modifier --}}

                                                                <a
                                                                    href="{{ route(
                                                                        'notes.edit',
                                                                        $noteExistante
                                                                    ) }}"
                                                                    class="px-3 py-2 bg-gray-600
                                                                           text-white rounded-lg
                                                                           hover:bg-gray-800
                                                                           text-sm"
                                                                >

                                                                    Modifier

                                                                </a>


                                                                {{-- Supprimer --}}

                                                                <button
                                                                    type="submit"
                                                                    form="delete-note-{{ $noteExistante->id_note }}"
                                                                    class="px-3 py-2 bg-red-600
                                                                           text-white rounded-lg
                                                                           hover:bg-red-700
                                                                           text-sm"
                                                                >

                                                                    Supprimer

                                                                </button>


                                                            @else


                                                                <span class="text-gray-400 text-sm">

                                                                    Nouvelle note

                                                                </span>


                                                            @endif


                                                        </div>

                                                    </td>


                                                </tr>


                                            @endif


                                        @endforeach


                                    </tbody>

                                </table>

                            </div>



                            {{-- ================================================= --}}
                            {{-- BOUTON UNIQUE D'ENREGISTREMENT --}}
                            {{-- ================================================= --}}

                            <div class="mt-6 flex items-center justify-between">


                                <div class="text-sm text-gray-500">

                                    Vous pouvez saisir ou modifier toutes les notes
                                    avant de les enregistrer.

                                </div>


                                <button
                                    type="submit"
                                    class="px-6 py-3 bg-gray-600
                                           text-white rounded-lg
                                           hover:bg-gray-800
                                           font-semibold shadow"
                                >

                                    Enregistrer toutes les notes

                                </button>

                            </div>


                        </form>


                        {{-- ================================================= --}}
                        {{-- FORMULAIRES DE SUPPRESSION --}}
                        {{-- Placés en dehors du formulaire global --}}
                        {{-- ================================================= --}}

                        @foreach($inscriptions as $inscription)

                            @php

                                $noteExistante = $notes->get(
                                    $inscription->id_eleve
                                );

                            @endphp


                            @if($noteExistante)

                                <form
                                    id="delete-note-{{ $noteExistante->id_note }}"
                                    action="{{ route(
                                        'notes.destroy',
                                        $noteExistante
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Voulez-vous vraiment supprimer cette note ?'
                                    );"
                                >

                                    @csrf

                                    @method('DELETE')

                                </form>

                            @endif

                        @endforeach


                    @else


                        <div class="text-center py-10">

                            <p class="text-gray-500">

                                Aucun élève inscrit dans cette classe
                                pour cette année scolaire.

                            </p>

                        </div>


                    @endif

                </div>

            </div>


        </div>

    </div>

</x-app-layout>
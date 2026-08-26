@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Saisir une note
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Enregistrement de la note d'un élève
            </p>
        </div>

        @if($evaluation)

            <a href="{{ route('evaluations.show', $evaluation) }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                ← Retour à l'évaluation

            </a>

        @else

            <a href="{{ route('notes.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                ← Retour

            </a>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- ERREURS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="mb-6 p-4 bg-red-100 border border-red-200 rounded-lg text-red-800">

            <ul class="list-disc list-inside text-sm">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================= --}}

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <div class="px-6 py-4 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-800">
                Informations de la note
            </h2>

        </div>


        <form method="POST"
              action="{{ route('notes.store') }}"
              class="p-6">

            @csrf


            {{-- ================================================= --}}
            {{-- ÉVALUATION --}}
            {{-- ================================================= --}}

            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Évaluation
                </label>


                @if($evaluation)

                    {{-- Évaluation déjà sélectionnée --}}

                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">

                        <div class="font-semibold text-gray-800">
                            {{ $evaluation->libelle }}
                        </div>

                        <div class="text-sm text-gray-500 mt-1">

                            {{ $evaluation->matiere?->libelle ?? '—' }}

                            —
                            
                            {{ $evaluation->classe?->libelle ?? '—' }}

                            —

                            {{ $evaluation->periode?->libelle ?? '—' }}

                        </div>

                        <div class="text-sm text-gray-500 mt-1">

                            Note maximale :
                            <strong>
                                {{ number_format($evaluation->note_maximale, 2, ',', ' ') }}
                            </strong>

                        </div>

                    </div>

                    <input type="hidden"
                           name="id_evaluation"
                           value="{{ $evaluation->id_evaluation }}">

                @else

                    {{-- Sélection manuelle si accès direct --}}

                    <select name="id_evaluation"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                            required>

                        <option value="">
                            -- Sélectionner une évaluation --
                        </option>

                        @foreach($evaluations as $item)

                            <option value="{{ $item->id_evaluation }}"
                                {{ old('id_evaluation') == $item->id_evaluation ? 'selected' : '' }}>

                                {{ $item->libelle }}

                                —
                                {{ $item->matiere?->libelle ?? '—' }}

                                —
                                {{ $item->classe?->libelle ?? '—' }}

                                —
                                {{ $item->periode?->libelle ?? '—' }}

                            </option>

                        @endforeach

                    </select>

                @endif

            </div>


            {{-- ================================================= --}}
            {{-- ÉLÈVE --}}
            {{-- ================================================= --}}

            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Élève
                </label>


                @if($eleve)

                    {{-- Élève déjà sélectionné --}}

                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">

                        <div class="font-semibold text-gray-800">

                            {{ $eleve->nom }}

                            {{ $eleve->postnom }}

                            {{ $eleve->prenom }}

                        </div>

                        <div class="text-sm text-gray-500 mt-1">

                            Matricule :
                            {{ $eleve->matricule ?? '—' }}

                        </div>

                    </div>

                    <input type="hidden"
                           name="id_eleve"
                           value="{{ $eleve->id_eleve }}">

                @elseif($evaluation)

                    {{-- Élèves appartenant à la classe de l'évaluation --}}

                    <select name="id_eleve"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                            required>

                        <option value="">
                            -- Sélectionner un élève --
                        </option>

                        @foreach($eleves as $item)

                            <option value="{{ $item->id_eleve }}"
                                {{ old('id_eleve') == $item->id_eleve ? 'selected' : '' }}>

                                {{ $item->nom }}
                                {{ $item->postnom }}
                                {{ $item->prenom }}

                                —
                                {{ $item->matricule ?? 'Sans matricule' }}

                            </option>

                        @endforeach

                    </select>

                @else

                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-sm">

                        Sélectionnez d'abord une évaluation.

                    </div>

                @endif

            </div>


            {{-- ================================================= --}}
            {{-- NOTE --}}
            {{-- ================================================= --}}

            <div class="mb-6">

                <label for="note"
                       class="block text-sm font-medium text-gray-700 mb-2">

                    Note

                </label>


                <div class="flex items-center gap-3">

                    <input type="number"
                           name="note"
                           id="note"
                           value="{{ old('note') }}"
                           min="0"
                           @if($evaluation)
                               max="{{ $evaluation->note_maximale }}"
                           @endif
                           step="0.01"
                           class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                           required>


                    @if($evaluation)

                        <span class="text-gray-600 whitespace-nowrap">

                            / {{ number_format($evaluation->note_maximale, 2, ',', ' ') }}

                        </span>

                    @endif

                </div>

                @if($evaluation)

                    <p class="text-xs text-gray-500 mt-1">

                        La note doit être comprise entre 0 et
                        {{ number_format($evaluation->note_maximale, 2, ',', ' ') }}.

                    </p>

                @endif

            </div>


            {{-- ================================================= --}}
            {{-- APPRÉCIATION --}}
            {{-- ================================================= --}}

            <div class="mb-6">

                <label for="appreciation"
                       class="block text-sm font-medium text-gray-700 mb-2">

                    Appréciation

                </label>

                <textarea name="appreciation"
                          id="appreciation"
                          rows="3"
                          maxlength="255"
                          class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                          placeholder="Facultatif">{{ old('appreciation') }}</textarea>

            </div>


            {{-- ================================================= --}}
            {{-- BOUTONS --}}
            {{-- ================================================= --}}

            <div class="flex justify-end gap-3">

                @if($evaluation)

                    <a href="{{ route('evaluations.show', $evaluation) }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">

                        Annuler

                    </a>

                @else

                    <a href="{{ route('notes.index') }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">

                        Annuler

                    </a>

                @endif


                <button type="submit"
                        class="px-5 py-2.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800">

                    Enregistrer la note

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
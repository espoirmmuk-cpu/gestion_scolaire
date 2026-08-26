<x-app-layout>

{{-- En-tête --}}
<x-slot name="header">
    <div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Modifier la note
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Modifier les informations de la note enregistrée.
        </p>
    </div>
</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


        {{-- Erreurs de validation --}}
        @if($errors->any())

            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5">

                <div class="font-semibold text-red-700 mb-2">
                    Veuillez corriger les erreurs suivantes :
                </div>

                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Message d'erreur --}}
        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                {{ session('error') }}
            </div>

        @endif


        {{-- Formulaire --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête du formulaire --}}
            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Informations de la note
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Modifiez les informations ci-dessous puis enregistrez les changements.
                </p>

            </div>


            <form action="{{ route('notes.update', $note) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="p-6 space-y-6">


                    {{-- Élève --}}
                    <div>

                        <label for="id_eleve"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Élève
                            <span class="text-red-500">*</span>

                        </label>


                        <select name="id_eleve"
                                id="id_eleve"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                -- Sélectionner un élève --
                            </option>


                            @foreach($eleves as $eleve)

                                <option value="{{ $eleve->id_eleve }}"
                                    {{ old('id_eleve', $note->id_eleve) == $eleve->id_eleve ? 'selected' : '' }}>

                                    {{ $eleve->matricule }}
                                    -
                                    {{ $eleve->nom }}
                                    {{ $eleve->postnom }}
                                    {{ $eleve->prenom }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Évaluation --}}
                    <div>

                        <label for="id_evaluation"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Évaluation
                            <span class="text-red-500">*</span>

                        </label>


                        <select name="id_evaluation"
                                id="id_evaluation"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                -- Sélectionner une évaluation --
                            </option>


                            @foreach($evaluations as $evaluation)

                                <option value="{{ $evaluation->id_evaluation }}"
                                    {{ old('id_evaluation', $note->id_evaluation) == $evaluation->id_evaluation ? 'selected' : '' }}>

                                    {{ $evaluation->libelle }}

                                    @if($evaluation->matiere)
                                        — {{ $evaluation->matiere->libelle }}
                                    @endif

                                    @if($evaluation->classe)
                                        — {{ $evaluation->classe->libelle }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Note --}}
                    <div>

                        <label for="note"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Note
                            <span class="text-red-500">*</span>

                        </label>


                        <input type="number"
                               name="note"
                               id="note"
                               step="0.01"
                               min="0"
                               @if($note->evaluation)
                                   max="{{ $note->evaluation->note_maximale }}"
                               @endif
                               value="{{ old('note', $note->note) }}"
                               required
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        @if($note->evaluation)

                            <p class="text-xs text-gray-400 mt-1">
                                Note maximale :
                                {{ number_format((float) $note->evaluation->note_maximale, 2, ',', ' ') }}
                            </p>

                        @endif

                    </div>


                    {{-- Appréciation --}}
                    <div>

                        <label for="appreciation"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Appréciation

                        </label>


                        <textarea name="appreciation"
                                  id="appreciation"
                                  rows="4"
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Observation ou appréciation éventuelle...">{{ old('appreciation', $note->appreciation) }}</textarea>

                    </div>


                </div>


                {{-- Boutons --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">


                    <a href="{{ route('notes.show', $note) }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">

                        ← Annuler

                    </a>


                    <button type="submit"
                            class="px-5 py-2.5 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">

                        ✓ Enregistrer les modifications

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

</x-app-layout>

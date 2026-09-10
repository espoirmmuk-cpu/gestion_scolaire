<x-app-layout>

    <!-- En-tête -->
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Modifier l'évaluation
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Modifier les informations de l'évaluation.
            </p>
        </div>
    </x-slot>


    <!-- Contenu principal -->
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


            {{-- Message d'erreur général --}}
            @if(session('error'))

                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">

                    {{ session('error') }}

                </div>

            @endif


            {{-- Formulaire --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <!-- En-tête du formulaire -->
                <div class="px-6 py-5 border-b border-gray-100">

                    <h3 class="text-lg font-bold text-gray-800">
                        Informations de l'évaluation
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Modifiez les informations nécessaires puis enregistrez.
                    </p>

                </div>


                <form action="{{ route('evaluations.update', $evaluation) }}"
                      method="POST">

                    @csrf

                    @method('PUT')


                    <div class="p-6 space-y-6">


                        {{-- Année scolaire et classe --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Année scolaire --}}
                            <div>

                                <label for="id_annee_scolaire"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Année scolaire

                                    <span class="text-red-500">*</span>

                                </label>


                                <select name="id_annee_scolaire"
                                        id="id_annee_scolaire"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                    <option value="">
                                        -- Sélectionner une année --
                                    </option>


                                    @foreach($annees as $annee)

                                        <option value="{{ $annee->id_annee_scolaire }}"
                                            {{ old('id_annee_scolaire', $evaluation->id_annee_scolaire) == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                            {{ $annee->libelle }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Classe --}}
                            <div>

                                <label for="id_classe"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Classe

                                    <span class="text-red-500">*</span>

                                </label>


                                <select name="id_classe"
                                        id="id_classe"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                    <option value="">
                                        -- Sélectionner une classe --
                                    </option>


                                    @foreach($classes as $classe)

                                        <option value="{{ $classe->id_classe }}"
                                            {{ old('id_classe', $evaluation->id_classe) == $classe->id_classe ? 'selected' : '' }}>

                                            {{ $classe->libelle }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Matière et période --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Matière --}}
                            <div>

                                <label for="id_matiere"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Matière

                                    <span class="text-red-500">*</span>

                                </label>


                                <select name="id_matiere"
                                        id="id_matiere"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                    <option value="">
                                        -- Sélectionner une matière --
                                    </option>


                                    @foreach($matieres as $matiere)

                                        <option value="{{ $matiere->id_matiere }}"
                                            {{ old('id_matiere', $evaluation->id_matiere) == $matiere->id_matiere ? 'selected' : '' }}>

                                            {{ $matiere->code }}
                                            -
                                            {{ $matiere->libelle }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Période scolaire --}}
                            <div>

                                <label for="id_periode"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Période scolaire

                                    <span class="text-red-500">*</span>

                                </label>


                                <select name="id_periode"
                                        id="id_periode"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                    <option value="">
                                        -- Sélectionner une période --
                                    </option>


                                    @foreach($periodes as $periode)

                                        <option value="{{ $periode->id_periode }}"
                                            {{ old('id_periode', $evaluation->id_periode) == $periode->id_periode ? 'selected' : '' }}>

                                            {{ $periode->libelle }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Libellé et type --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Libellé --}}
                            <div>

                                <label for="libelle"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Libellé de l'évaluation

                                    <span class="text-red-500">*</span>

                                </label>


                                <input type="text"
                                       name="libelle"
                                       id="libelle"
                                       value="{{ old('libelle', $evaluation->libelle) }}"
                                       required
                                       maxlength="150"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ex. Interrogation de mathématiques">

                            </div>


                            {{-- Type d'évaluation --}}
                            <div>

                                <label for="type_evaluation"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Type d'évaluation

                                </label>


                                <input type="text"
                                       name="type_evaluation"
                                       id="type_evaluation"
                                       value="{{ old('type_evaluation', $evaluation->type_evaluation) }}"
                                       maxlength="100"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Ex. Interrogation, Examen, Devoir...">

                            </div>

                        </div>


                        {{-- Note maximale et date --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Note maximale --}}
                            <div>

                                <label for="note_maximale"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Note maximale

                                    <span class="text-red-500">*</span>

                                </label>


                                <input type="number"
                                       name="note_maximale"
                                       id="note_maximale"
                                       value="{{ old('note_maximale', $evaluation->note_maximale) }}"
                                       required
                                       min="0"
                                       step="0.01"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="20">

                            </div>


                            {{-- Date --}}
                            <div>

                                <label for="date_evaluation"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Date de l'évaluation

                                </label>


                                <input type="date"
                                       name="date_evaluation"
                                       id="date_evaluation"
                                       value="{{ old('date_evaluation', $evaluation->date_evaluation ? $evaluation->date_evaluation->format('Y-m-d') : '') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            </div>

                        </div>


                    </div>


                    {{-- Boutons --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">

                        <a href="{{ route('evaluations.show', $evaluation) }}"
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
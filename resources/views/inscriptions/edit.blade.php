<x-app-layout>

<x-slot name="header">

    <div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Modifier l'inscription
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Modifier les informations de l'inscription de l'élève.
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


        {{-- Message d'erreur général --}}

        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">

                {{ session('error') }}

            </div>

        @endif


        {{-- Message de succès --}}

        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">

                {{ session('success') }}

            </div>

        @endif


        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête du formulaire --}}

            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Informations de l'inscription
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Modifiez les informations nécessaires puis enregistrez les changements.
                </p>

            </div>


            {{-- Formulaire de modification --}}

            <form action="{{ route('inscriptions.update', $inscription->id_inscription) }}"
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
                                    {{ old('id_eleve', $inscription->id_eleve) == $eleve->id_eleve ? 'selected' : '' }}>

                                    {{ $eleve->matricule }}
                                    -
                                    {{ $eleve->nom }}
                                    {{ $eleve->postnom }}
                                    {{ $eleve->prenom }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Classe et année --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


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
                                        {{ old('id_classe', $inscription->id_classe) == $classe->id_classe ? 'selected' : '' }}>

                                        {{ $classe->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


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
                                        {{ old('id_annee_scolaire', $inscription->id_annee_scolaire) == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                        {{ $annee->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- Date et statut --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- Date d'inscription --}}

                        <div>

                            <label for="date_inscription"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Date d'inscription

                                <span class="text-red-500">*</span>

                            </label>


                            <input type="date"
                                   name="date_inscription"
                                   id="date_inscription"
                                   value="{{ old('date_inscription', $inscription->date_inscription ? \Carbon\Carbon::parse($inscription->date_inscription)->format('Y-m-d') : '') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Statut --}}

                        <div>

                            <label for="statut"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Statut

                                <span class="text-red-500">*</span>

                            </label>


                            <select name="statut"
                                    id="statut"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                <option value="INSCRIT"
                                    {{ old('statut', $inscription->statut) === 'INSCRIT' ? 'selected' : '' }}>

                                    INSCRIT

                                </option>

                                <option value="ABANDON"
                                    {{ old('statut', $inscription->statut) === 'ABANDON' ? 'selected' : '' }}>

                                    ABANDON

                                </option>

                                <option value="TRANSFERE"
                                    {{ old('statut', $inscription->statut) === 'TRANSFERE' ? 'selected' : '' }}>

                                    TRANSFÉRÉ

                                </option>

                                <option value="RADIE"
                                    {{ old('statut', $inscription->statut) === 'RADIE' ? 'selected' : '' }}>

                                    RADIÉ

                                </option>

                                <option value="DIPLOME"
                                    {{ old('statut', $inscription->statut) === 'DIPLOME' ? 'selected' : '' }}>

                                    DIPLÔMÉ

                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Observation --}}

                    <div>

                        <label for="observation"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Observation

                        </label>


                        <textarea name="observation"
                                  id="observation"
                                  rows="4"
                                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Observation éventuelle...">{{ old('observation', $inscription->observation) }}</textarea>

                    </div>


                </div>


                {{-- Boutons --}}

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">

                    <a href="{{ route('inscriptions.index') }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">

                        ← Annuler

                    </a>


                    <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                        ✓ Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


</x-app-layout>

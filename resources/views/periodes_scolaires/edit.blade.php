<x-app-layout>

<x-slot name="header">

    <div>

        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Modifier la période scolaire
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Modifier les informations de cette période scolaire.
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


        {{-- Erreur générale --}}

        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                {{ session('error') }}
            </div>

        @endif


        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête --}}

            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Informations de la période
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Modifiez les informations nécessaires puis enregistrez.
                </p>

            </div>


            <form action="{{ route('periodes-scolaires.update', $periodeScolaire) }}"
                  method="POST">

                @csrf

                @method('PUT')


                <div class="p-6 space-y-6">


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
                                -- Sélectionner une année scolaire --
                            </option>


                            @foreach($annees as $annee)

                                <option value="{{ $annee->id_annee_scolaire }}"
                                    {{ old(
                                        'id_annee_scolaire',
                                        $periodeScolaire->id_annee_scolaire
                                    ) == $annee->id_annee_scolaire
                                        ? 'selected'
                                        : ''
                                    }}>

                                    {{ $annee->libelle }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Libellé --}}

                    <div>

                        <label for="libelle"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Libellé de la période

                            <span class="text-red-500">*</span>

                        </label>


                        <input type="text"
                               name="libelle"
                               id="libelle"
                               value="{{ old(
                                   'libelle',
                                   $periodeScolaire->libelle
                               ) }}"
                               required
                               maxlength="100"
                               placeholder="Exemple : Premier trimestre"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <p class="text-xs text-gray-400 mt-1">
                            Exemple : Premier trimestre, Deuxième trimestre, Premier semestre...
                        </p>

                    </div>


                    {{-- Dates --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- Date début --}}

                        <div>

                            <label for="date_debut"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Date de début

                                <span class="text-red-500">*</span>

                            </label>


                            <input type="date"
                                   name="date_debut"
                                   id="date_debut"
                                   value="{{ old(
                                       'date_debut',
                                       $periodeScolaire->date_debut
                                           ? $periodeScolaire->date_debut->format('Y-m-d')
                                           : ''
                                   ) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Date fin --}}

                        <div>

                            <label for="date_fin"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Date de fin

                                <span class="text-red-500">*</span>

                            </label>


                            <input type="date"
                                   name="date_fin"
                                   id="date_fin"
                                   value="{{ old(
                                       'date_fin',
                                       $periodeScolaire->date_fin
                                           ? $periodeScolaire->date_fin->format('Y-m-d')
                                           : ''
                                   ) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>

                    </div>


                </div>


                {{-- Boutons --}}

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">


                    <a href="{{ route('periodes-scolaires.show', $periodeScolaire) }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">

                        ← Annuler

                    </a>


                    <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

                        ✓ Enregistrer les modifications

                    </button>


                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>

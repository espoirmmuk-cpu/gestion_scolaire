<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Nouvelle affectation d'un enseignant
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Affecter un enseignant à une classe et à une matière.
                </p>

            </div>

            <a href="{{ route('affectations-enseignants.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100
                      text-gray-700 rounded-lg hover:bg-gray-200
                      transition duration-200">

                <span class="mr-2">←</span>

                Retour

            </a>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- =====================================================
                 MESSAGES
            ====================================================== -->

            @if(session('success'))

                <div class="mb-6 bg-green-100 border border-green-300
                            text-green-700 px-4 py-3 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="mb-6 bg-red-100 border border-red-300
                            text-red-700 px-4 py-3 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            @if($errors->any())

                <div class="mb-6 bg-red-100 border border-red-300
                            text-red-700 px-4 py-3 rounded-lg">

                    <p class="font-semibold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </p>

                    <ul class="list-disc ml-5 text-sm">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- =====================================================
                 FORMULAIRE
            ====================================================== -->

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <!-- EN-TÊTE DU FORMULAIRE -->

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-bold text-gray-800">
                        Informations de l'affectation
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Sélectionnez l'enseignant, la classe, la matière
                        et l'année scolaire.
                    </p>

                </div>


                <form method="POST"
                      action="{{ route('affectations-enseignants.store') }}"
                      class="p-6">

                    @csrf


                    <!-- =================================================
                         ENSEIGNANT
                    ================================================== -->

                    <div class="mb-6">

                        <label for="id_enseignant"
                               class="block text-sm font-medium text-gray-700 mb-2">

                            Enseignant
                            <span class="text-red-500">*</span>

                        </label>

                        <select id="id_enseignant"
                                name="id_enseignant"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500">

                            <option value="">
                                -- Sélectionner un enseignant --
                            </option>

                            @foreach($enseignants as $enseignant)

                                <option value="{{ $enseignant->id_personnel }}"
                                    {{ old('id_enseignant') == $enseignant->id_personnel ? 'selected' : '' }}>

                                    {{ $enseignant->nom }}
                                    {{ $enseignant->postnom }}
                                    {{ $enseignant->prenom }}

                                    @if(!empty($enseignant->matricule))
                                        — {{ $enseignant->matricule }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('id_enseignant')

                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    <!-- =================================================
                         CLASSE + MATIÈRE
                    ================================================== -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <!-- CLASSE -->

                        <div>

                            <label for="id_classe"
                                   class="block text-sm font-medium text-gray-700 mb-2">

                                Classe
                                <span class="text-red-500">*</span>

                            </label>

                            <select id="id_classe"
                                    name="id_classe"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-blue-500
                                           focus:ring-blue-500">

                                <option value="">
                                    -- Sélectionner une classe --
                                </option>

                                @foreach($classes as $classe)

                                    <option value="{{ $classe->id_classe }}"
                                        {{ old('id_classe') == $classe->id_classe ? 'selected' : '' }}>

                                        {{ $classe->libelle }}

                                    </option>

                                @endforeach

                            </select>

                            @error('id_classe')

                                <p class="text-sm text-red-600 mt-1">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>



                        <!-- MATIÈRE -->

                        <div>

                            <label for="id_matiere"
                                   class="block text-sm font-medium text-gray-700 mb-2">

                                Matière
                                <span class="text-red-500">*</span>

                            </label>

                            <select id="id_matiere"
                                    name="id_matiere"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-blue-500
                                           focus:ring-blue-500">

                                <option value="">
                                    -- Sélectionner une matière --
                                </option>

                                @foreach($matieres as $matiere)

                                    <option value="{{ $matiere->id_matiere }}"
                                        {{ old('id_matiere') == $matiere->id_matiere ? 'selected' : '' }}>

                                        {{ $matiere->libelle }}

                                    </option>

                                @endforeach

                            </select>

                            @error('id_matiere')

                                <p class="text-sm text-red-600 mt-1">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>



                    <!-- =================================================
                         ANNÉE SCOLAIRE
                    ================================================== -->

                    <div class="mt-6">

                        <label for="id_annee_scolaire"
                               class="block text-sm font-medium text-gray-700 mb-2">

                            Année scolaire
                            <span class="text-red-500">*</span>

                        </label>

                        <select id="id_annee_scolaire"
                                name="id_annee_scolaire"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500">

                            <option value="">
                                -- Sélectionner une année scolaire --
                            </option>

                            @foreach($anneesScolaires as $annee)

                                <option value="{{ $annee->id_annee_scolaire }}"
                                    {{ old('id_annee_scolaire') == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                    {{ $annee->libelle }}

                                </option>

                            @endforeach

                        </select>

                        @error('id_annee_scolaire')

                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    <!-- =================================================
                         TITULAIRE
                    ================================================== -->

                    <div class="mt-6">

                        <div class="flex items-center">

                            <input type="checkbox"
                                   id="est_titulaire"
                                   name="est_titulaire"
                                   value="1"
                                   {{ old('est_titulaire') ? 'checked' : '' }}
                                   class="rounded border-gray-300
                                          text-blue-600
                                          shadow-sm
                                          focus:ring-blue-500">

                            <label for="est_titulaire"
                                   class="ml-3 text-sm font-medium text-gray-700">

                                Enseignant titulaire de cette matière dans cette classe

                            </label>

                        </div>

                        <p class="text-xs text-gray-500 mt-2 ml-7">

                            Cochez cette option si l'enseignant est le titulaire
                            de la matière pour cette classe.

                        </p>

                        @error('est_titulaire')

                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>



                    <!-- =================================================
                         BOUTONS
                    ================================================== -->

                    <div class="flex items-center justify-end gap-3
                                mt-8 pt-6 border-t border-gray-200">

                        <a href="{{ route('affectations-enseignants.index') }}"
                           class="px-5 py-2.5 bg-gray-100 text-gray-700
                                  font-medium rounded-lg
                                  hover:bg-gray-200
                                  transition duration-200">

                            Annuler

                        </a>


                        <button type="submit"
                                class="inline-flex items-center px-5 py-2.5
                                       bg-blue-600 text-white font-semibold
                                       rounded-lg shadow-sm
                                       hover:bg-blue-700
                                       transition duration-200">

                            <span class="mr-2">
                                💾
                            </span>

                            Enregistrer l'affectation

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
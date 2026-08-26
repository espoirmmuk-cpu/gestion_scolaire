<x-app-layout>

<!-- ================================================================
     EN-TÊTE
================================================================= -->

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Modifier une affectation
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Modifier l'affectation d'un enseignant à une classe et une matière.
            </p>

        </div>

        <a href="{{ route('affectations-enseignants.index') }}"
           class="inline-flex items-center px-4 py-2
                  bg-gray-100 text-gray-700 rounded-lg
                  hover:bg-gray-200 transition duration-200">

            <span class="mr-2">←</span>

            Retour

        </a>

    </div>

</x-slot>


<!-- ================================================================
     CONTENU
================================================================= -->

<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white rounded-xl shadow-sm p-8">


            <!-- ====================================================
                 TITRE DU FORMULAIRE
            ===================================================== -->

            <div class="mb-8">

                <h3 class="text-xl font-bold text-gray-800">
                    Modifier l'affectation
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Vérifiez les informations avant d'enregistrer les modifications.
                </p>

            </div>


            <!-- ====================================================
                 ERREURS
            ===================================================== -->

            @if($errors->any())

                <div class="mb-6 bg-red-100 border border-red-300
                            text-red-700 px-4 py-3 rounded-lg">

                    <ul class="list-disc ml-5">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- ====================================================
                 FORMULAIRE
            ===================================================== -->

            <form method="POST"
                  action="{{ route('affectations-enseignants.update', $affectation->id_affectation) }}">

                @csrf

                @method('PUT')


                <!-- =================================================
                     ANNÉE SCOLAIRE
                ================================================== -->

                <div class="mb-6">

                    <label for="id_annee_scolaire"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Année scolaire

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
                                {{ old(
                                    'id_annee_scolaire',
                                    $affectation->id_annee_scolaire
                                ) == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                {{ $annee->libelle }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- =================================================
                     ENSEIGNANT
                ================================================== -->

                <div class="mb-6">

                    <label for="id_enseignant"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Enseignant

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
                                {{ old(
                                    'id_enseignant',
                                    $affectation->id_enseignant
                                ) == $enseignant->id_personnel ? 'selected' : '' }}>

                                {{ $enseignant->nom }}
                                {{ $enseignant->postnom }}
                                {{ $enseignant->prenom }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- =================================================
                     CLASSE
                ================================================== -->

                <div class="mb-6">

                    <label for="id_classe"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Classe

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
                                {{ old(
                                    'id_classe',
                                    $affectation->id_classe
                                ) == $classe->id_classe ? 'selected' : '' }}>

                                {{ $classe->libelle }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- =================================================
                     MATIÈRE
                ================================================== -->

                <div class="mb-6">

                    <label for="id_matiere"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Matière

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
                                {{ old(
                                    'id_matiere',
                                    $affectation->id_matiere
                                ) == $matiere->id_matiere ? 'selected' : '' }}>

                                {{ $matiere->libelle }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- =================================================
                     TITULAIRE
                ================================================== -->

                <div class="mb-6">

                    <label class="flex items-center">

                        <input type="checkbox"
                               name="est_titulaire"
                               value="1"
                               {{ old(
                                   'est_titulaire',
                                   $affectation->est_titulaire
                               ) ? 'checked' : '' }}
                               class="rounded border-gray-300
                                      text-blue-600
                                      shadow-sm
                                      focus:ring-blue-500">

                        <span class="ml-3 text-sm font-medium text-gray-700">

                            Cet enseignant est titulaire de cette classe

                        </span>

                    </label>

                </div>


                <!-- =================================================
                     BOUTONS
                ================================================== -->

                <div class="flex items-center justify-end gap-3 pt-4
                            border-t border-gray-200">

                    <a href="{{ route('affectations-enseignants.index') }}"
                       class="inline-flex items-center px-5 py-2.5
                              bg-gray-100 text-gray-700
                              font-semibold rounded-lg
                              hover:bg-gray-200
                              transition duration-200">

                        Annuler

                    </a>


                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5
                                   bg-blue-600 text-white
                                   font-semibold rounded-lg
                                   shadow-sm
                                   hover:bg-blue-700
                                   transition duration-200">

                        <span class="mr-2">
                            💾
                        </span>

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>
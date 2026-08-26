<x-app-layout>

<x-slot name="header">

    <div>

        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Nouveau tarif scolaire
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Définissez le montant d'un frais pour une classe et une année scolaire.
        </p>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">


        {{-- Erreur générale --}}

        @if(session('error'))

            <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        {{-- Erreurs de validation --}}

        @if($errors->any())

            <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-lg">

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


        <div class="bg-white rounded-xl shadow-sm p-6">


            <form action="{{ route('tarifs-scolaires.store') }}"
                  method="POST">

                @csrf


                {{-- Année scolaire --}}

                <div class="mb-6">

                    <label for="id_annee_scolaire"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Année scolaire
                        <span class="text-red-500">*</span>

                    </label>


                    <select id="id_annee_scolaire"
                            name="id_annee_scolaire"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <option value="">
                            -- Sélectionner une année --
                        </option>


                        @foreach($annees as $annee)

                            <option value="{{ $annee->id_annee_scolaire }}"
                                {{ old('id_annee_scolaire') == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                {{ $annee->libelle }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Classe --}}

                <div class="mb-6">

                    <label for="id_classe"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Classe
                        <span class="text-red-500">*</span>

                    </label>


                    <select id="id_classe"
                            name="id_classe"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

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

                </div>


                {{-- Catégorie de frais --}}

                <div class="mb-6">

                    <label for="id_categorie_frais"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Catégorie de frais
                        <span class="text-red-500">*</span>

                    </label>


                    <select id="id_categorie_frais"
                            name="id_categorie_frais"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <option value="">
                            -- Sélectionner une catégorie --
                        </option>


                        @foreach($categories as $categorie)

                            <option value="{{ $categorie->id_categorie_frais }}"
                                {{ old('id_categorie_frais') == $categorie->id_categorie_frais ? 'selected' : '' }}>

                                {{ $categorie->libelle }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Montant --}}

                <div class="mb-6">

                    <label for="montant"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Montant
                        <span class="text-red-500">*</span>

                    </label>


                    <input type="number"
                           id="montant"
                           name="montant"
                           value="{{ old('montant') }}"
                           min="0"
                           step="0.01"
                           placeholder="Exemple : 500"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                </div>


                {{-- Devise --}}

                <div class="mb-8">

                    <label for="devise"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Devise
                        <span class="text-red-500">*</span>

                    </label>


                    <select id="devise"
                            name="devise"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <option value="USD"
                            {{ old('devise', 'USD') === 'USD' ? 'selected' : '' }}>

                            USD — Dollar américain

                        </option>

                        <option value="CDF"
                            {{ old('devise') === 'CDF' ? 'selected' : '' }}>

                            CDF — Franc congolais

                        </option>

                    </select>

                </div>


                {{-- Boutons --}}

                <div class="flex items-center justify-between">

                    <a href="{{ route('tarifs-scolaires.index') }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">

                        ← Annuler

                    </a>


                    <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                        💾 Enregistrer le tarif
                        

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


</x-app-layout>

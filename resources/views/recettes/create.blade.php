<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800">
                Nouvelle recette
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Enregistrer une nouvelle entrée financière
            </p>

        </div>

        <a href="{{ route('recettes.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg
                  hover:bg-gray-300 transition">

            Retour aux recettes

        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


        {{-- Erreurs de validation --}}

        @if ($errors->any())

            <div class="mb-6 bg-red-100 border border-red-300
                        text-red-700 px-4 py-3 rounded-lg">

                <div class="font-semibold mb-2">
                    Veuillez corriger les erreurs suivantes :
                </div>

                <ul class="list-disc list-inside text-sm">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Formulaire --}}

        <div class="bg-white shadow-sm rounded-xl overflow-hidden">

            <div class="p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                    Informations de la recette
                </h3>


                <form method="POST"
                      action="{{ route('recettes.store') }}">

                    @csrf


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- Année scolaire --}}

                        <div>

                            <label for="id_annee_scolaire"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-1">

                                Année scolaire
                                <span class="text-red-500">*</span>

                            </label>

                            <select
                                id="id_annee_scolaire"
                                name="id_annee_scolaire"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                                <option value="">
                                    Sélectionner une année scolaire
                                </option>

                                @foreach ($anneesScolaires as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        @selected(
                                            old('id_annee_scolaire') ==
                                            $annee->id_annee_scolaire
                                        )>

                                        {{ $annee->libelle
                                            ?? $annee->annee
                                            ?? $annee->id_annee_scolaire }}

                                    </option>

                                @endforeach

                            </select>

                            @error('id_annee_scolaire')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Date --}}

                        <div>

                            <label for="date_recette"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-1">

                                Date de la recette
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="date"
                                id="date_recette"
                                name="date_recette"
                                value="{{ old('date_recette', now()->format('Y-m-d')) }}"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                            @error('date_recette')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Source --}}

                        <div class="md:col-span-2">

                            <label for="source"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-1">

                                Source
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                id="source"
                                name="source"
                                value="{{ old('source') }}"
                                maxlength="255"
                                required
                                placeholder="Ex. Frais scolaires, inscription, uniforme..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                            @error('source')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Montant --}}

                        <div>

                            <label for="montant"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-1">

                                Montant
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="number"
                                id="montant"
                                name="montant"
                                value="{{ old('montant') }}"
                                min="0"
                                step="0.01"
                                required
                                placeholder="0.00"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                            @error('montant')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Devise --}}

                        <div>

                            <label for="devise"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-1">

                                Devise
                                <span class="text-red-500">*</span>

                            </label>

                            <select
                                id="devise"
                                name="devise"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                                <option value="">
                                    Sélectionner une devise
                                </option>

                                <option value="USD"
                                    @selected(old('devise') === 'USD')>
                                    USD
                                </option>

                                <option value="CDF"
                                    @selected(old('devise') === 'CDF')>
                                    CDF
                                </option>

                            </select>

                            @error('devise')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Description --}}

                        <div class="md:col-span-2">

                            <label for="description"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-1">

                                Description

                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                maxlength="1000"
                                placeholder="Informations complémentaires..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">{{ old('description') }}</textarea>

                            @error('description')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>


                    {{-- Boutons --}}

                    <div class="mt-8 pt-6 border-t border-gray-200
                                flex items-center justify-end gap-3">

                        <a href="{{ route('recettes.index') }}"
                           class="px-5 py-2.5 bg-gray-200
                                  text-gray-700 rounded-lg
                                  hover:bg-gray-300 transition">

                            Annuler

                        </a>


                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-gray-600
                                   text-white rounded-lg
                                   hover:bg-gray-700 transition">

                            Enregistrer la recette

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</x-app-layout>

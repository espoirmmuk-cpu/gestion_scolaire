<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier la recette
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Modification des informations de la recette
            </p>

        </div>

        <a href="{{ route('recettes.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700
                  rounded-lg hover:bg-gray-300">

            Retour

        </a>

    </div>

</x-slot>


<div class="py-6 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


        {{-- Erreurs --}}

        @if ($errors->any())

            <div class="mb-6 bg-red-100 border border-red-300
                        text-red-800 px-4 py-3 rounded-lg">

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

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Informations de la recette
                </h3>

            </div>


            <form method="POST"
                  action="{{ route('recettes.update', $recette) }}">

                @csrf

                @method('PUT')


                <div class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- Date --}}

                        <div>

                            <label
                                for="date_recette"
                                class="block text-sm font-medium
                                       text-gray-700 mb-1">

                                Date de la recette
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="date"
                                id="date_recette"
                                name="date_recette"
                                value="{{ old(
                                    'date_recette',
                                    $recette->date_recette
                                        ? \Carbon\Carbon::parse(
                                            $recette->date_recette
                                        )->format('Y-m-d')
                                        : ''
                                ) }}"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                        </div>


                        {{-- Année scolaire --}}

                        <div>

                            <label
                                for="id_annee_scolaire"
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
                                            old(
                                                'id_annee_scolaire',
                                                $recette->id_annee_scolaire
                                            ) ==
                                            $annee->id_annee_scolaire
                                        )>

                                        {{ $annee->libelle
                                            ?? $annee->annee
                                            ?? $annee->id_annee_scolaire }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Source --}}

                        <div class="md:col-span-2">

                            <label
                                for="source"
                                class="block text-sm font-medium
                                       text-gray-700 mb-1">

                                Source de la recette
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                id="source"
                                name="source"
                                value="{{ old(
                                    'source',
                                    $recette->source
                                ) }}"
                                required
                                maxlength="255"
                                placeholder="Ex. Frais scolaires"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                        </div>


                        {{-- Montant --}}

                        <div>

                            <label
                                for="montant"
                                class="block text-sm font-medium
                                       text-gray-700 mb-1">

                                Montant
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="number"
                                id="montant"
                                name="montant"
                                value="{{ old(
                                    'montant',
                                    $recette->montant
                                ) }}"
                                min="0"
                                step="0.01"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                        </div>


                        {{-- Devise --}}

                        <div>

                            <label
                                for="devise"
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

                                @php
                                    $deviseActuelle = old(
                                        'devise',
                                        $recette->devise
                                    );
                                @endphp

                                <option
                                    value="USD"
                                    @selected($deviseActuelle === 'USD')>

                                    USD

                                </option>

                                <option
                                    value="CDF"
                                    @selected($deviseActuelle === 'CDF')>

                                    CDF

                                </option>

                            </select>

                        </div>


                        {{-- Description --}}

                        <div class="md:col-span-2">

                            <label
                                for="description"
                                class="block text-sm font-medium
                                       text-gray-700 mb-1">

                                Description

                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                maxlength="1000"
                                placeholder="Description ou remarque concernant cette recette..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">{{ old(
                                        'description',
                                        $recette->description
                                    ) }}</textarea>

                        </div>

                    </div>


                    {{-- Boutons --}}

                    <div class="mt-8 pt-6 border-t border-gray-200
                                flex items-center justify-between">

                        <a
                            href="{{ route('recettes.show', $recette) }}"
                            class="px-5 py-2.5 bg-gray-200
                                   text-gray-700 rounded-lg
                                   hover:bg-gray-300">

                            Annuler

                        </a>


                        @can('update', $recette)

                            <button
                                type="submit"
                                class="px-6 py-2.5 bg-gray-600
                                       text-white rounded-lg
                                       hover:bg-gray-700">

                                Enregistrer les modifications

                            </button>

                        @endcan

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>

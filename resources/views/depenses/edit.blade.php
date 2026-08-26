<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Modifier la dépense
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Modification des informations de la dépense
                </p>

            </div>

            <a href="{{ route('depenses.show', $depense) }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">

                Voir la dépense

            </a>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages d'erreur --}}

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


            <div class="bg-white shadow-sm rounded-xl overflow-hidden">

                <div class="p-6">

                    <form method="POST"
                          action="{{ route('depenses.update', $depense) }}">

                        @csrf
                        @method('PUT')


                        {{-- Année scolaire --}}

                        <div class="mb-5">

                            <label for="id_annee_scolaire"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Année scolaire

                            </label>

                            <select
                                name="id_annee_scolaire"
                                id="id_annee_scolaire"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500">

                                <option value="">
                                    Aucune année scolaire
                                </option>

                                @foreach ($anneesScolaires as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        @selected(
                                            old(
                                                'id_annee_scolaire',
                                                $depense->id_annee_scolaire
                                            ) == $annee->id_annee_scolaire
                                        )
                                    >

                                        {{ $annee->libelle
                                            ?? $annee->annee
                                            ?? $annee->id_annee_scolaire }}

                                    </option>

                                @endforeach

                            </select>

                            @error('id_annee_scolaire')

                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Date --}}

                        <div class="mb-5">

                            <label for="date_depense"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Date de la dépense

                            </label>

                            <input
                                type="datetime-local"
                                name="date_depense"
                                id="date_depense"
                                value="{{ old(
                                    'date_depense',
                                    $depense->date_depense
                                        ? \Carbon\Carbon::parse($depense->date_depense)->format('Y-m-d\TH:i')
                                        : ''
                                ) }}"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500"
                            >

                            @error('date_depense')

                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Catégorie --}}

                        <div class="mb-5">

                            <label for="categorie"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Catégorie

                            </label>

                            <input
                                type="text"
                                name="categorie"
                                id="categorie"
                                value="{{ old('categorie', $depense->categorie) }}"
                                placeholder="Ex. Fournitures scolaires"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500"
                            >

                            @error('categorie')

                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Montant + devise --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">


                            {{-- Montant --}}

                            <div>

                                <label for="montant"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Montant

                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    name="montant"
                                    id="montant"
                                    value="{{ old('montant', $depense->montant) }}"
                                    placeholder="0.00"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500 focus:ring-gray-500"
                                >

                                @error('montant')

                                    <p class="text-red-600 text-sm mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Devise --}}

                            <div>

                                <label for="devise"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Devise

                                </label>

                                <select
                                    name="devise"
                                    id="devise"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500 focus:ring-gray-500"
                                >

                                    <option value="USD"
                                        @selected(old('devise', $depense->devise) === 'USD')>
                                        USD
                                    </option>

                                    <option value="CDF"
                                        @selected(old('devise', $depense->devise) === 'CDF')>
                                        CDF
                                    </option>

                                </select>

                                @error('devise')

                                    <p class="text-red-600 text-sm mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>


                        {{-- Description --}}

                        <div class="mb-6">

                            <label for="description"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Description

                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                placeholder="Description de la dépense..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500"
                            >{{ old('description', $depense->description) }}</textarea>

                            @error('description')

                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Boutons --}}

                        <div class="flex items-center justify-end gap-3">

                            <a href="{{ route('depenses.index') }}"
                               class="px-5 py-2.5 bg-gray-200 text-gray-700
                                      rounded-lg hover:bg-gray-300 transition">

                                Annuler

                            </a>


                            <button
                                type="submit"
                                class="px-5 py-2.5 bg-gray-600 text-white
                                       rounded-lg hover:bg-gray-700 transition">

                                Enregistrer les modifications

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
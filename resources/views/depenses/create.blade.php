<x-app-layout>

<x-slot name="header">

    <div>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nouvelle dépense
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Enregistrer une nouvelle sortie financière
        </p>

    </div>

</x-slot>


<div class="py-6">

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


        {{-- Erreurs de validation --}}

        @if ($errors->any())

            <div class="mb-6 px-4 py-3
                        bg-red-100 border border-red-300
                        text-red-800 rounded-lg">

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


            {{-- En-tête du formulaire --}}

            <div class="px-6 py-5 border-b border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800">
                    Informations de la dépense
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Les champs marqués d'un <span class="text-red-500">*</span>
                    sont obligatoires.
                </p>

            </div>


            {{-- Formulaire --}}

            <form
                method="POST"
                action="{{ route('depenses.store') }}"
            >

                @csrf


                <div class="p-6 space-y-6">


                    {{-- Année scolaire --}}

                    <div>

                        <label
                            for="id_annee_scolaire"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >

                            Année scolaire

                        </label>


                        <select
                            id="id_annee_scolaire"
                            name="id_annee_scolaire"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500
                                   focus:ring-gray-500"
                        >

                            <option value="">
                                -- Sélectionner une année scolaire --
                            </option>


                            @foreach ($anneesScolaires as $annee)

                                <option
                                    value="{{ $annee->id_annee_scolaire }}"
                                    @selected(
                                        old('id_annee_scolaire') ==
                                        $annee->id_annee_scolaire
                                    )
                                >

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

                        <label
                            for="date_depense"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >

                            Date de la dépense
                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="datetime-local"
                            id="date_depense"
                            name="date_depense"
                            value="{{ old(
                                'date_depense',
                                now()->format('Y-m-d\TH:i')
                            ) }}"
                            required
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500
                                   focus:ring-gray-500"
                        >


                        @error('date_depense')

                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Catégorie --}}

                    <div>

                        <label
                            for="categorie"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >

                            Catégorie
                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="text"
                            id="categorie"
                            name="categorie"
                            value="{{ old('categorie') }}"
                            required
                            maxlength="150"
                            placeholder="Ex. Salaires, fournitures, transport..."
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500
                                   focus:ring-gray-500"
                        >


                        @error('categorie')

                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Montant + Devise --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- Montant --}}

                        <div>

                            <label
                                for="montant"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >

                                Montant
                                <span class="text-red-500">*</span>

                            </label>


                            <input
                                type="number"
                                id="montant"
                                name="montant"
                                value="{{ old('montant') }}"
                                required
                                min="0.01"
                                step="0.01"
                                placeholder="0.00"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >


                            @error('montant')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Devise --}}

                        <div>

                            <label
                                for="devise"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >

                                Devise
                                <span class="text-red-500">*</span>

                            </label>


                            <select
                                id="devise"
                                name="devise"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                                <option value="USD"
                                    @selected(old('devise', 'USD') === 'USD')
                                >
                                    USD
                                </option>

                                <option value="CDF"
                                    @selected(old('devise') === 'CDF')
                                >
                                    CDF
                                </option>

                            </select>


                            @error('devise')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>


                    {{-- Description --}}

                    <div>

                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >

                            Description

                        </label>


                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Description ou détails de la dépense..."
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500
                                   focus:ring-gray-500"
                        >{{ old('description') }}</textarea>


                        @error('description')

                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- Boutons --}}

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200
                            flex items-center justify-between">


                    <a
                        href="{{ route('depenses.index') }}"
                        class="px-5 py-2.5
                               bg-gray-200 text-gray-700
                               rounded-lg
                               hover:bg-gray-300 transition"
                    >

                        Annuler

                    </a>


                    <button
                        type="submit"
                        class="px-5 py-2.5
                               bg-gray-600 text-white
                               rounded-lg
                               hover:bg-gray-700 transition"
                    >

                        Enregistrer la dépense

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>

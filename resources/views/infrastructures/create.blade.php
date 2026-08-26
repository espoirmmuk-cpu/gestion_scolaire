<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nouvelle infrastructure
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Enregistrer une nouvelle infrastructure dans l'établissement
            </p>
        </div>

    </x-slot>


    <div class="py-6 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-xl">

                <div class="p-6">

                    @if($errors->any())

                        <div class="mb-6 px-4 py-3
                                    bg-red-100 border border-red-300
                                    text-red-800 rounded-lg">

                            <div class="font-semibold mb-2">
                                Veuillez corriger les erreurs suivantes :
                            </div>

                            <ul class="list-disc list-inside text-sm">

                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form method="POST"
                          action="{{ route('infrastructures.store') }}">

                        @csrf


                        {{-- Désignation --}}

                        <div class="mb-5">

                            <label for="designation"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Désignation <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="text"
                                id="designation"
                                name="designation"
                                value="{{ old('designation') }}"
                                required
                                maxlength="150"
                                placeholder="Ex. Salle de classe, bureau, bâtiment..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500"
                            >

                            @error('designation')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Type --}}

                        <div class="mb-5">

                            <label for="type"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Type

                            </label>

                            <input
                                type="text"
                                id="type"
                                name="type"
                                value="{{ old('type') }}"
                                maxlength="100"
                                placeholder="Ex. Bâtiment, salle, terrain, équipement..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500"
                            >

                            @error('type')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                            {{-- Quantité --}}

                            <div>

                                <label for="quantite"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    Quantité <span class="text-red-500">*</span>

                                </label>

                                <input
                                    type="number"
                                    id="quantite"
                                    name="quantite"
                                    value="{{ old('quantite', 1) }}"
                                    min="1"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500 focus:ring-gray-500"
                                >

                                @error('quantite')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- État --}}

                            <div>

                                <label for="etat"
                                       class="block text-sm font-medium text-gray-700 mb-1">

                                    État <span class="text-red-500">*</span>

                                </label>

                                <select
                                    id="etat"
                                    name="etat"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-gray-500 focus:ring-gray-500">

                                    <option value="BON"
                                        @selected(old('etat', 'BON') === 'BON')>
                                        Bon
                                    </option>

                                    <option value="MOYEN"
                                        @selected(old('etat') === 'MOYEN')>
                                        Moyen
                                    </option>

                                    <option value="A_REHABILITER"
                                        @selected(old('etat') === 'A_REHABILITER')>
                                        À réhabiliter
                                    </option>

                                    <option value="HORS_SERVICE"
                                        @selected(old('etat') === 'HORS_SERVICE')>
                                        Hors service
                                    </option>

                                </select>

                                @error('etat')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>


                        {{-- Localisation --}}

                        <div class="mt-5 mb-5">

                            <label for="localisation"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Localisation

                            </label>

                            <input
                                type="text"
                                id="localisation"
                                name="localisation"
                                value="{{ old('localisation') }}"
                                maxlength="150"
                                placeholder="Ex. Bloc A, étage 1..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500"
                            >

                            @error('localisation')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Observation --}}

                        <div class="mb-6">

                            <label for="observation"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Observation

                            </label>

                            <textarea
                                id="observation"
                                name="observation"
                                rows="4"
                                placeholder="Observations concernant cette infrastructure..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500 focus:ring-gray-500"
                            >{{ old('observation') }}</textarea>

                            @error('observation')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Boutons --}}

                        <div class="flex justify-end gap-3">

                            <a href="{{ route('infrastructures.index') }}"
                               class="px-5 py-2.5
                                      bg-gray-200 text-gray-700
                                      rounded-lg hover:bg-gray-300">

                                Annuler

                            </a>


                            <button
                                type="submit"
                                class="px-5 py-2.5
                                       bg-gray-600 text-white
                                       rounded-lg hover:bg-gray-700">

                                Enregistrer

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
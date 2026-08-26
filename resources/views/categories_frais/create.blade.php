<x-app-layout>


<x-slot name="header">

    <div>

        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Nouvelle catégorie de frais
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Ajoutez un nouveau type de frais scolaires.
        </p>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white rounded-xl shadow-sm p-6">


            <form action="{{ route('categories-frais.store') }}"
                  method="POST">

                @csrf


                {{-- Libellé --}}

                <div class="mb-6">

                    <label for="libelle"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Libellé
                        <span class="text-red-500">*</span>

                    </label>

                    <input type="text"
                           id="libelle"
                           name="libelle"
                           value="{{ old('libelle') }}"
                           placeholder="Exemple : Minerval"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                    @error('libelle')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Description --}}

                <div class="mb-6">

                    <label for="description"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Description

                    </label>

                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Description facultative..."
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>

                    @error('description')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Statut --}}

                <div class="mb-8">

                    <label for="statut"
                           class="block text-sm font-medium text-gray-700 mb-2">

                        Statut
                        <span class="text-red-500">*</span>

                    </label>

                    <select id="statut"
                            name="statut"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <option value="ACTIVE"
                            {{ old('statut', 'ACTIVE') === 'ACTIVE' ? 'selected' : '' }}>

                            ACTIVE

                        </option>

                        <option value="INACTIVE"
                            {{ old('statut') === 'INACTIVE' ? 'selected' : '' }}>

                            INACTIVE

                        </option>

                    </select>

                    @error('statut')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Boutons --}}

                <div class="flex items-center justify-between">

                    <a href="{{ route('categories-frais.index') }}"
                       class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">

                        ← Annuler

                        
                    </a>


                    <button type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                        💾 Enregistrer


                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


</x-app-layout>

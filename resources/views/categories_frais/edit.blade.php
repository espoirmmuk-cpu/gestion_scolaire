<x-app-layout>

<x-slot name="header">

    <div>

        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Modifier la catégorie de frais
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Modifiez les informations de cette catégorie de frais scolaires.
        </p>

    </div>

</x-slot>


<div class="max-w-3xl mx-auto sm:px-6 lg:px-8">


    {{-- Message d'erreur général --}}

    @if(session('error'))

        <div class="mb-6 bg-red-100 border border-red-200
                    text-red-700 px-5 py-4 rounded-lg">

            {{ session('error') }}

        </div>

    @endif


    {{-- Erreurs de validation --}}

    @if($errors->any())

        <div class="mb-6 bg-red-100 border border-red-200
                    text-red-700 px-5 py-4 rounded-lg">

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


        <form
            action="{{ route('categories-frais.update', $categorieFrais) }}"
            method="POST">

            @csrf

            @method('PUT')


            {{-- Libellé --}}

            <div class="mb-6">

                <label
                    for="libelle"
                    class="block text-sm font-medium text-gray-700 mb-2">

                    Libellé
                    <span class="text-red-500">*</span>

                </label>


                <input
                    type="text"
                    id="libelle"
                    name="libelle"
                    value="{{ old('libelle', $categorieFrais->libelle) }}"
                    placeholder="Exemple : Minerval"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-gray-500 focus:ring-gray-500">


                @error('libelle')

                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Description --}}

            <div class="mb-6">

                <label
                    for="description"
                    class="block text-sm font-medium text-gray-700 mb-2">

                    Description

                </label>


                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Description facultative..."
                    class="w-full rounded-lg border-gray-300
                           focus:border-gray-500 focus:ring-gray-500">{{ old('description', $categorieFrais->description) }}</textarea>


                @error('description')

                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Statut --}}

            <div class="mb-8">

                <label
                    for="statut"
                    class="block text-sm font-medium text-gray-700 mb-2">

                    Statut
                    <span class="text-red-500">*</span>

                </label>


                <select
                    id="statut"
                    name="statut"
                    required
                    class="w-full rounded-lg border-gray-300
                           focus:border-gray-500 focus:ring-gray-500">


                    <option
                        value="ACTIVE"
                        {{ old(
                            'statut',
                            $categorieFrais->statut
                        ) === 'ACTIVE' ? 'selected' : '' }}>

                        ACTIVE

                    </option>


                    <option
                        value="INACTIVE"
                        {{ old(
                            'statut',
                            $categorieFrais->statut
                        ) === 'INACTIVE' ? 'selected' : '' }}>

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


                <a
                    href="{{ route('categories-frais.index', $categorieFrais) }}"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700
                           rounded-lg hover:bg-gray-200 transition">

                    ← Annuler

                </a>


                <button
                    type="submit"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                    💾 Enregistrer les modifications

                </button>


            </div>

        </form>

    </div>

</div>

</x-app-layout>

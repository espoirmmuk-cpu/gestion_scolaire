<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-2xl text-gray-800">
                    Modifier le personnel
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Modification complète du dossier
                </p>
            </div>

            <a
                href="{{ route('personnel.show', $personnel) }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg
                       border border-gray-200 hover:bg-gray-200 transition">
                ← Retour
            </a>

        </div>

    </x-slot>

<div class="py-8">

    {{-- En-tête --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Modifier le personnel
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Modification complète du dossier
                </p>
            </div>

            <a
                href="{{ route('personnel.show', $personnel) }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg
                       border border-gray-200 hover:bg-gray-200 transition">
                ← Retour
            </a>

        </div>

    </div>


    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Erreurs --}}
        @if($errors->any())

            <div class="mb-6 bg-red-50 border border-red-200
                        text-red-700 rounded-lg p-4">

                <p class="font-semibold mb-2">
                    Veuillez corriger les erreurs suivantes :
                </p>

                <ul class="list-disc list-inside text-sm space-y-1">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('personnel.update', $personnel) }}">

            @csrf
            @method('PUT')


            <div class="bg-white rounded-xl shadow-sm overflow-hidden">


                {{-- Informations d'identification --}}
                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Identification
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Informations principales du membre du personnel
                    </p>

                </div>


                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- Établissement --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Établissement
                        </label>

                        <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-700">
                            {{ $personnel->etablissement?->nom ?? 'Établissement non défini' }}
                        </div>

                        <p class="mt-1 text-xs text-gray-500">
                            L'établissement est automatiquement associé au compte utilisateur.
                        </p>

                    </div>


                    {{-- Matricule --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Matricule <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="matricule"
                            value="{{ old('matricule', $personnel->matricule) }}"
                            required
                            maxlength="50"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('matricule')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Statut --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Statut <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="statut"
                            required
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                            <option value="ACTIF"
                                {{ old('statut', $personnel->statut) === 'ACTIF' ? 'selected' : '' }}>
                                Actif
                            </option>

                            <option value="INACTIF"
                                {{ old('statut', $personnel->statut) === 'INACTIF' ? 'selected' : '' }}>
                                Inactif
                            </option>

                            <option value="SUSPENDU"
                                {{ old('statut', $personnel->statut) === 'SUSPENDU' ? 'selected' : '' }}>
                                Suspendu
                            </option>

                        </select>

                        @error('statut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Nom --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="nom"
                            value="{{ old('nom', $personnel->nom) }}"
                            required
                            maxlength="100"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Postnom --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Postnom
                        </label>

                        <input
                            type="text"
                            name="postnom"
                            value="{{ old('postnom', $personnel->postnom) }}"
                            maxlength="100"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('postnom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Prénom --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Prénom
                        </label>

                        <input
                            type="text"
                            name="prenom"
                            value="{{ old('prenom', $personnel->prenom) }}"
                            maxlength="100"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Sexe --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Sexe <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="sexe"
                            required
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                            <option value="M"
                                {{ old('sexe', $personnel->sexe) === 'M' ? 'selected' : '' }}>
                                Masculin
                            </option>

                            <option value="F"
                                {{ old('sexe', $personnel->sexe) === 'F' ? 'selected' : '' }}>
                                Féminin
                            </option>

                        </select>

                        @error('sexe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                </div>


                {{-- Informations professionnelles --}}
                <div class="px-6 py-5 border-t border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Informations professionnelles
                    </h3>

                </div>


                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- Fonction --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fonction <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="fonction"
                            value="{{ old('fonction', $personnel->fonction) }}"
                            required
                            maxlength="100"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('fonction')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Qualification --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Qualification
                        </label>

                        <input
                            type="text"
                            name="qualification"
                            value="{{ old('qualification', $personnel->qualification) }}"
                            maxlength="150"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('qualification')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Date engagement --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Date d'engagement
                        </label>

                        <input
                            type="date"
                            name="date_engagement"
                            value="{{ old('date_engagement', $personnel->date_engagement ? \Carbon\Carbon::parse($personnel->date_engagement)->format('Y-m-d') : '') }}"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('date_engagement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Photo --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Photo
                        </label>

                        <input
                            type="text"
                            name="photo"
                            value="{{ old('photo', $personnel->photo) }}"
                            maxlength="255"
                            placeholder="Chemin ou nom de la photo"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                </div>


                {{-- Coordonnées --}}
                <div class="px-6 py-5 border-t border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Coordonnées
                    </h3>

                </div>


                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- Téléphone --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Téléphone
                        </label>

                        <input
                            type="text"
                            name="telephone"
                            value="{{ old('telephone', $personnel->telephone) }}"
                            maxlength="50"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            E-mail
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $personnel->email) }}"
                            maxlength="150"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Adresse --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Adresse
                        </label>

                        <textarea
                            name="adresse"
                            rows="3"
                            maxlength="255"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-gray-500 focus:ring-gray-500">{{ old('adresse', $personnel->adresse) }}</textarea>

                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                    </div>

                </div>


                {{-- Boutons --}}
                <div class="px-6 py-5 bg-gray-50 border-t border-gray-200
                            flex flex-col sm:flex-row sm:justify-end gap-3">

                    <a
                        href="{{ route('personnel.show', $personnel) }}"
                        class="px-5 py-2.5 text-center bg-white text-gray-700
                               border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 text-center bg-white text-gray-700
                               hover:bg-gray-800 transition">
                        Enregistrer les modifications
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

</x-app-layout>

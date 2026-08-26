<x-app-layout>


<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800">
                Ajouter un enseignant
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Enregistrer un nouveau membre du personnel
            </p>
        </div>

        <a href="{{ route('personnel.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            Retour
        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


        {{-- Erreurs --}}
        @if($errors->any())

            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">

                <p class="font-semibold mb-2">
                    Veuillez corriger les erreurs suivantes :
                </p>

                <ul class="list-disc list-inside text-sm">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <div class="bg-white shadow-sm rounded-xl overflow-hidden">

            <form method="POST" action="{{ route('personnel.store') }}">

                @csrf


                {{-- Informations personnelles --}}
                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-6">
                        Informations personnelles
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                        {{-- Matricule --}}
                        <div>

                            <label for="matricule"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Matricule *
                            </label>

                            <input
                                type="text"
                                id="matricule"
                                name="matricule"
                                value="{{ old('matricule') }}"
                                placeholder="Exemple : ENS-001"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Nom --}}
                        <div>

                            <label for="nom"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Nom *
                            </label>

                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="{{ old('nom') }}"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Postnom --}}
                        <div>

                            <label for="postnom"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Postnom
                            </label>

                            <input
                                type="text"
                                id="postnom"
                                name="postnom"
                                value="{{ old('postnom') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Prénom --}}
                        <div>

                            <label for="prenom"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Prénom
                            </label>

                            <input
                                type="text"
                                id="prenom"
                                name="prenom"
                                value="{{ old('prenom') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Sexe --}}
                        <div>

                            <label for="sexe"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Sexe *
                            </label>

                            <select
                                id="sexe"
                                name="sexe"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                <option value="M"
                                    {{ old('sexe') === 'M' ? 'selected' : '' }}>
                                    Masculin
                                </option>

                                <option value="F"
                                    {{ old('sexe') === 'F' ? 'selected' : '' }}>
                                    Féminin
                                </option>

                            </select>

                        </div>


                        {{-- Date engagement --}}
                        <div>

                            <label for="date_engagement"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Date d'engagement
                            </label>

                            <input
                                type="date"
                                id="date_engagement"
                                name="date_engagement"
                                value="{{ old('date_engagement') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>

                    </div>

                </div>


                {{-- Informations professionnelles --}}
                <div class="p-6 border-t border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800 mb-6">
                        Informations professionnelles
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- Fonction --}}
                        <div>

                            <label for="fonction"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Fonction *
                            </label>

                            <input
                                type="text"
                                id="fonction"
                                name="fonction"
                                value="{{ old('fonction', 'Enseignant') }}"
                                placeholder="Exemple : Enseignant"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Qualification --}}
                        <div>

                            <label for="qualification"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Qualification
                            </label>

                            <input
                                type="text"
                                id="qualification"
                                name="qualification"
                                value="{{ old('qualification') }}"
                                placeholder="Exemple : Gradué, Licencié..."
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Statut --}}
                        <div>

                            <label for="statut"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Statut *
                            </label>

                            <select
                                id="statut"
                                name="statut"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                                <option value="ACTIF"
                                    {{ old('statut', 'ACTIF') === 'ACTIF' ? 'selected' : '' }}>
                                    Actif
                                </option>

                                <option value="INACTIF"
                                    {{ old('statut') === 'INACTIF' ? 'selected' : '' }}>
                                    Inactif
                                </option>

                                <option value="SUSPENDU"
                                    {{ old('statut') === 'SUSPENDU' ? 'selected' : '' }}>
                                    Suspendu
                                </option>

                            </select>

                        </div>

                        {{-- Établissement --}}
                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Établissement
                            </label>

                            <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-700">
                                {{ auth()->user()->etablissement?->nom ?? 'Établissement non défini' }}
                            </div>

                            <p class="mt-1 text-xs text-gray-500">
                                Le personnel sera automatiquement rattaché à cet établissement.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Coordonnées --}}
                <div class="p-6 border-t border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800 mb-6">
                        Coordonnées
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- Téléphone --}}
                        <div>

                            <label for="telephone"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Téléphone
                            </label>

                            <input
                                type="text"
                                id="telephone"
                                name="telephone"
                                value="{{ old('telephone') }}"
                                placeholder="+243..."
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Email --}}
                        <div>

                            <label for="email"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        {{-- Adresse --}}
                        <div class="md:col-span-2">

                            <label for="adresse"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Adresse
                            </label>

                            <textarea
                                id="adresse"
                                name="adresse"
                                rows="3"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('adresse') }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- Boutons --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">

                    <a
                        href="{{ route('personnel.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Enregistrer l'enseignant
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


</x-app-layout>

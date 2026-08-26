<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Ajouter un établissement
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Enregistrer une nouvelle école
                </p>
            </div>

            <a href="{{ route('etablissements.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg
                      hover:bg-gray-700 transition">

                ← Retour

            </a>

        </div>

    </x-slot>


    <div class="py-6 bg-gray-100 min-h-screen">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages d'erreur --}}

            @if ($errors->any())

                <div class="mb-6 bg-red-50 border border-red-300
                            text-red-700 rounded-lg p-4">

                    <p class="font-bold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </p>

                    <ul class="list-disc list-inside text-sm">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="bg-white shadow-lg rounded-xl overflow-hidden">

                {{-- =====================================================
                     EN-TÊTE DU FORMULAIRE
                ====================================================== --}}

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-bold text-gray-800">
                        Informations de l'établissement
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Renseignez les informations générales de l'école.
                    </p>

                </div>


                {{-- =====================================================
                     FORMULAIRE
                ====================================================== --}}

                <form action="{{ route('etablissements.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf


                    <div class="p-6 space-y-8">


                        {{-- =================================================
                             INFORMATIONS PRINCIPALES
                        ================================================== --}}

                        <div>

                            <h4 class="text-md font-bold text-gray-800
                                       border-b border-gray-200 pb-2 mb-5">

                                Informations générales

                            </h4>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                                {{-- Nom --}}

                                <div class="md:col-span-2">

                                    <label for="nom"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Nom de l'établissement
                                        <span class="text-red-500">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        name="nom"
                                        id="nom"
                                        value="{{ old('nom') }}"
                                        required
                                        autofocus
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="Ex. Complexe Scolaire La Réussite"
                                    >

                                </div>


                                {{-- Code --}}

                                <div>

                                    <label for="code"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Code de l'établissement

                                    </label>

                                    <input
                                        type="text"
                                        name="code"
                                        id="code"
                                        value="{{ old('code') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="Ex. CSR001"
                                    >

                                </div>


                                {{-- Type --}}

                                <div>

                                    <label for="type"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Type d'établissement

                                        <span class="text-red-500">*</span>

                                    </label>

                                    <select
                                        name="type"
                                        id="type"
                                        required
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500">

                                        <option value="">
                                            -- Sélectionner --
                                        </option>

                                        <option value="École primaire"
                                            {{ old('type') == 'École primaire' ? 'selected' : '' }}>
                                            École primaire
                                        </option>

                                        <option value="École secondaire"
                                            {{ old('type') == 'École secondaire' ? 'selected' : '' }}>
                                            École secondaire
                                        </option>

                                        <option value="Complexe scolaire"
                                            {{ old('type') == 'Complexe scolaire' ? 'selected' : '' }}>
                                            Complexe scolaire
                                        </option>

                                        <option value="Institut"
                                            {{ old('type') == 'Institut' ? 'selected' : '' }}>
                                            Institut
                                        </option>

                                        <option value="Autre"
                                            {{ old('type') == 'Autre' ? 'selected' : '' }}>
                                            Autre
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             LOCALISATION
                        ================================================== --}}

                        <div>

                            <h4 class="text-md font-bold text-gray-800
                                       border-b border-gray-200 pb-2 mb-5">

                                Localisation

                            </h4>


                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                                {{-- Province --}}

                                <div>

                                    <label for="province"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Province

                                    </label>

                                    <input
                                        type="text"
                                        name="province"
                                        id="province"
                                        value="{{ old('province') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="Ex. Kinshasa"
                                    >

                                </div>


                                {{-- Ville --}}

                                <div>

                                    <label for="ville"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Ville

                                    </label>

                                    <input
                                        type="text"
                                        name="ville"
                                        id="ville"
                                        value="{{ old('ville') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="Ex. Kinshasa"
                                    >

                                </div>


                                {{-- Commune --}}

                                <div>

                                    <label for="commune"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Commune

                                    </label>

                                    <input
                                        type="text"
                                        name="commune"
                                        id="commune"
                                        value="{{ old('commune') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="Ex. Limete"
                                    >

                                </div>


                                {{-- Adresse --}}

                                <div class="md:col-span-3">

                                    <label for="adresse"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Adresse

                                    </label>

                                    <input
                                        type="text"
                                        name="adresse"
                                        id="adresse"
                                        value="{{ old('adresse') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="Adresse complète de l'établissement"
                                    >

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             CONTACT
                        ================================================== --}}

                        <div>

                            <h4 class="text-md font-bold text-gray-800
                                       border-b border-gray-200 pb-2 mb-5">

                                Coordonnées et direction

                            </h4>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                                {{-- Téléphone --}}

                                <div>

                                    <label for="telephone"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Téléphone

                                    </label>

                                    <input
                                        type="text"
                                        name="telephone"
                                        id="telephone"
                                        value="{{ old('telephone') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="+243 ..."
                                    >

                                </div>


                                {{-- Email --}}

                                <div>

                                    <label for="email"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Adresse e-mail

                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="ecole@example.com"
                                    >

                                </div>


                                {{-- Directeur --}}

                                <div class="md:col-span-2">

                                    <label for="directeur"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Directeur / Chef d'établissement

                                    </label>

                                    <input
                                        type="text"
                                        name="directeur"
                                        id="directeur"
                                        value="{{ old('directeur') }}"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500"
                                        placeholder="Nom complet du directeur"
                                    >

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             LOGO ET STATUT
                        ================================================== --}}

                        <div>

                            <h4 class="text-md font-bold text-gray-800
                                       border-b border-gray-200 pb-2 mb-5">

                                Logo et statut

                            </h4>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                                {{-- Logo --}}

                                <div>

                                    <label for="logo"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Logo de l'établissement

                                    </label>

                                    <input
                                        type="file"
                                        name="logo"
                                        id="logo"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="w-full rounded-lg border border-gray-300
                                               bg-white p-2 text-sm">

                                    <p class="text-xs text-gray-500 mt-1">
                                        Formats acceptés : JPG, JPEG, PNG ou WEBP.
                                    </p>

                                </div>


                                {{-- Statut --}}

                                <div>

                                    <label for="statut"
                                           class="block text-sm font-semibold text-gray-700 mb-1">

                                        Statut

                                        <span class="text-red-500">*</span>

                                    </label>

                                    <select
                                        name="statut"
                                        id="statut"
                                        required
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-gray-500 focus:ring-gray-500">

                                        <option value="ACTIF"
                                            {{ old('statut', 'ACTIF') == 'ACTIF' ? 'selected' : '' }}>
                                            Actif
                                        </option>

                                        <option value="INACTIF"
                                            {{ old('statut') == 'INACTIF' ? 'selected' : '' }}>
                                            Inactif
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>


                    </div>


                    {{-- =====================================================
                         BOUTONS
                    ====================================================== --}}

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200
                                flex items-center justify-end gap-3">

                        <a href="{{ route('etablissements.index') }}"
                           class="px-5 py-2.5 bg-gray-200 text-gray-700
                                  rounded-lg hover:bg-gray-300 transition">

                            Annuler

                        </a>


                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-gray-800 text-white
                                   rounded-lg hover:bg-gray-900 transition">

                            Enregistrer l'établissement

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
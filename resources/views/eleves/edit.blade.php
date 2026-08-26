<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-2xl text-gray-800">
                    Modifier l'élève
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Modifier les informations de l'élève
                </p>

            </div>


            <a
                href="{{ route('eleves.show', $eleve) }}"
                class="px-4 py-2 bg-gray-100 text-gray-700
                       border border-gray-200 rounded-lg
                       hover:bg-gray-200 transition">

                ← Retour

            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ERREURS --}}
            @if($errors->any())

                <div
                    class="mb-6 bg-red-50 border border-red-200
                           text-red-700 px-5 py-4 rounded-xl">

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


            <div class="bg-white rounded-xl shadow-sm overflow-hidden">


                {{-- EN-TÊTE --}}
                <div class="px-6 py-6 border-b border-gray-200">

                    <div class="flex items-center gap-5">

                        <div
                            class="w-20 h-20 rounded-full bg-gray-100
                                   flex items-center justify-center
                                   border-4 border-gray-50">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-10 h-10 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0
                                       3.75 3.75 0 0 1 7.5 0ZM4.5
                                       20.25a8.25 8.25 0 0 1 15 0" />

                            </svg>

                        </div>


                        <div>

                            <h3 class="text-2xl font-bold text-gray-800">

                                {{ $eleve->nom }}
                                {{ $eleve->postnom }}
                                {{ $eleve->prenom }}

                            </h3>

                            <p class="text-gray-500 mt-1">
                                Matricule :
                                <span class="font-medium text-gray-700">
                                    {{ $eleve->matricule }}
                                </span>
                            </p>

                        </div>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('eleves.update', $eleve) }}">

                    @csrf
                    @method('PUT')


                    {{-- INFORMATIONS PERSONNELLES --}}
                    <div class="p-6">

                        <div
                            class="border border-gray-200
                                   rounded-xl overflow-hidden">

                            <div
                                class="px-5 py-4 bg-gray-50
                                       border-b border-gray-200">

                                <h4 class="font-semibold text-gray-800">
                                    Informations personnelles
                                </h4>

                            </div>


                            <div class="p-5">

                                <div
                                    class="grid grid-cols-1
                                           lg:grid-cols-2 gap-5">


                                    {{-- MATRICULE --}}
                                    <div>

                                        <label
                                            for="matricule"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Matricule *

                                        </label>

                                        <input
                                            type="text"
                                            id="matricule"
                                            name="matricule"
                                            value="{{ old('matricule', $eleve->matricule) }}"
                                            required
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- NOM --}}
                                    <div>

                                        <label
                                            for="nom"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Nom *

                                        </label>

                                        <input
                                            type="text"
                                            id="nom"
                                            name="nom"
                                            value="{{ old('nom', $eleve->nom) }}"
                                            required
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- POSTNOM --}}
                                    <div>

                                        <label
                                            for="postnom"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Postnom

                                        </label>

                                        <input
                                            type="text"
                                            id="postnom"
                                            name="postnom"
                                            value="{{ old('postnom', $eleve->postnom) }}"
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- PRÉNOM --}}
                                    <div>

                                        <label
                                            for="prenom"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Prénom

                                        </label>

                                        <input
                                            type="text"
                                            id="prenom"
                                            name="prenom"
                                            value="{{ old('prenom', $eleve->prenom) }}"
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- SEXE --}}
                                    <div>

                                        <label
                                            for="sexe"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Sexe *

                                        </label>

                                        <select
                                            id="sexe"
                                            name="sexe"
                                            required
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                            <option value="M"
                                                {{ old('sexe', $eleve->sexe) === 'M' ? 'selected' : '' }}>
                                                Masculin
                                            </option>

                                            <option value="F"
                                                {{ old('sexe', $eleve->sexe) === 'F' ? 'selected' : '' }}>
                                                Féminin
                                            </option>

                                        </select>

                                    </div>


                                    {{-- DATE NAISSANCE --}}
                                    <div>

                                        <label
                                            for="date_naissance"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Date de naissance

                                        </label>

                                        <input
                                            type="date"
                                            id="date_naissance"
                                            name="date_naissance"
                                            value="{{ old('date_naissance', $eleve->date_naissance?->format('Y-m-d')) }}"
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- LIEU NAISSANCE --}}
                                    <div>

                                        <label
                                            for="lieu_naissance"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Lieu de naissance

                                        </label>

                                        <input
                                            type="text"
                                            id="lieu_naissance"
                                            name="lieu_naissance"
                                            value="{{ old('lieu_naissance', $eleve->lieu_naissance) }}"
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- TÉLÉPHONE --}}
                                    <div>

                                        <label
                                            for="telephone"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Téléphone

                                        </label>

                                        <input
                                            type="text"
                                            id="telephone"
                                            name="telephone"
                                            value="{{ old('telephone', $eleve->telephone) }}"
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- EMAIL --}}
                                    <div>

                                        <label
                                            for="email"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            E-mail

                                        </label>

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', $eleve->email) }}"
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">

                                    </div>


                                    {{-- ADRESSE --}}
                                    <div class="lg:col-span-2">

                                        <label
                                            for="adresse"
                                            class="block text-sm font-medium
                                                   text-gray-700 mb-1">

                                            Adresse

                                        </label>

                                        <textarea
                                            id="adresse"
                                            name="adresse"
                                            rows="3"
                                            class="w-full rounded-lg
                                                   border-gray-300
                                                   focus:border-gray-500
                                                   focus:ring-gray-500">{{ old('adresse', $eleve->adresse) }}</textarea>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- STATUT --}}
                    <div class="px-6 pb-6">

                        <div
                            class="border border-gray-200
                                   rounded-xl overflow-hidden">

                            <div
                                class="px-5 py-4 bg-gray-50
                                       border-b border-gray-200">

                                <h4 class="font-semibold text-gray-800">
                                    Statut de l'élève
                                </h4>

                            </div>


                            <div class="p-5">

                                <label
                                    for="statut"
                                    class="block text-sm font-medium
                                           text-gray-700 mb-1">

                                    Statut *

                                </label>


                                <select
                                    id="statut"
                                    name="statut"
                                    required
                                    class="w-full lg:w-1/2 rounded-lg
                                           border-gray-300
                                           focus:border-gray-500
                                           focus:ring-gray-500">

                                    <option value="ACTIF"
                                        {{ old('statut', $eleve->statut) === 'ACTIF' ? 'selected' : '' }}>
                                        Actif
                                    </option>

                                    <option value="INACTIF"
                                        {{ old('statut', $eleve->statut) === 'INACTIF' ? 'selected' : '' }}>
                                        Inactif
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- ACTIONS --}}
                    <div
                        class="px-6 py-4 bg-gray-50
                               border-t border-gray-200
                               flex flex-col sm:flex-row
                               sm:justify-end gap-3">

                        <a
                            href="{{ route('eleves.show', $eleve) }}"
                            class="px-5 py-2.5 text-center
                                   bg-white text-gray-700
                                   border border-gray-200 rounded-lg
                                   hover:bg-gray-100 transition">

                            Annuler

                        </a>


                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">


                            Enregistrer les modifications

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
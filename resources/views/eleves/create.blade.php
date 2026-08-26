<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-2xl text-gray-800">
                    Ajouter un élève
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Enregistrer un nouvel élève
                </p>

            </div>

            <a href="{{ route('eleves.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                ← Retour

            </a>

        </div>

    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                @if($errors->any())

                    <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">

                        <ul class="list-disc list-inside">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <form method="POST" action="{{ route('eleves.store') }}">

                    @csrf


                    <!-- Informations personnelles -->

                    <h3 class="text-lg font-bold text-gray-800 mb-5">
                        Informations personnelles
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <!-- Matricule -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Matricule *
                            </label>

                            <input
                                type="text"
                                name="matricule"
                                value="{{ old('matricule') }}"
                                required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        <!-- Nom -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Nom *
                            </label>

                            <input
                                type="text"
                                name="nom"
                                value="{{ old('nom') }}"
                                required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                        </div>


                        <!-- Postnom -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Postnom
                            </label>

                            <input
                                type="text"
                                name="postnom"
                                value="{{ old('postnom') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                        </div>


                        <!-- Prénom -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Prénom
                            </label>

                            <input
                                type="text"
                                name="prenom"
                                value="{{ old('prenom') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                        </div>


                        <!-- Sexe -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Sexe *
                            </label>

                            <select
                                name="sexe"
                                required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                                <option value="">Sélectionner</option>

                                <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>
                                    Masculin
                                </option>

                                <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>
                                    Féminin
                                </option>

                            </select>

                        </div>


                        <!-- Date naissance -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Date de naissance
                            </label>

                            <input
                                type="date"
                                name="date_naissance"
                                value="{{ old('date_naissance') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                        </div>


                        <!-- Lieu naissance -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Lieu de naissance
                            </label>

                            <input
                                type="text"
                                name="lieu_naissance"
                                value="{{ old('lieu_naissance') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                        </div>


                        <!-- Téléphone -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Téléphone
                            </label>

                            <input
                                type="text"
                                name="telephone"
                                value="{{ old('telephone') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                        </div>


                        <!-- Email -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                        </div>


                        <!-- Adresse -->

                        <div class="md:col-span-2">

                            <label class="block text-sm font-medium text-gray-700">
                                Adresse
                            </label>

                            <input
                                type="text"
                                name="adresse"
                                value="{{ old('adresse') }}"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                        </div>


                        <!-- Statut -->

                        <div>

                            <label class="block text-sm font-medium text-gray-700">
                                Statut *
                            </label>

                            <select
                                name="statut"
                                required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm">

                                <option value="ACTIF" {{ old('statut', 'ACTIF') === 'ACTIF' ? 'selected' : '' }}>
                                    ACTIF
                                </option>

                                <option value="INACTIF" {{ old('statut') === 'INACTIF' ? 'selected' : '' }}>
                                    INACTIF
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- Boutons -->

                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t">

                        <a href="{{ route('eleves.index') }}"
                           class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">

                            Annuler

                        </a>


                        <button
                            type="submit"
                            class="px-5 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                            Enregistrer l'élève

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
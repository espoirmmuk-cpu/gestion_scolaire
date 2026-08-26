<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Nouvelle année scolaire
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Ajouter une nouvelle année scolaire à l'établissement.
            </p>
        </div>
    </x-slot>


    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">


            {{-- =========================================================
                 MESSAGE DE SUCCÈS
            ========================================================== --}}

            @if(session('success'))

                <div class="mb-6 bg-green-50 border border-green-200
                            text-green-700 px-5 py-4 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            {{-- =========================================================
                 MESSAGE D'ERREUR
            ========================================================== --}}

            @if(session('error'))

                <div class="mb-6 bg-red-50 border border-red-200
                            text-red-700 px-5 py-4 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            {{-- =========================================================
                 ERREURS DE VALIDATION
            ========================================================== --}}

            @if($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200
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


            {{-- =========================================================
                 FORMULAIRE
            ========================================================== --}}

            <div class="bg-white rounded-xl shadow-sm">

                <div class="p-6">

                    <form
                        action="{{ route('annees-scolaires.store') }}"
                        method="POST"
                    >

                        @csrf


                        {{-- =================================================
                             ANNÉE SCOLAIRE
                        ================================================== --}}

                        <div class="mb-6">

                            <label
                                for="libelle"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >

                                Année scolaire

                                <span class="text-red-500">*</span>

                            </label>


                            <input
                                type="text"
                                id="libelle"
                                name="libelle"
                                value="{{ old('libelle') }}"
                                maxlength="20"
                                placeholder="Exemple : 2026-2027"
                                required
                                class="w-full rounded-lg border-gray-300
                                       shadow-sm
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >


                            <p class="mt-1 text-xs text-gray-500">
                                Exemple : 2026-2027
                            </p>


                            @error('libelle')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             DATES
                        ================================================== --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            {{-- DATE DE DÉBUT --}}

                            <div>

                                <label
                                    for="date_debut"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >

                                    Date de début

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    type="date"
                                    id="date_debut"
                                    name="date_debut"
                                    value="{{ old('date_debut') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           shadow-sm
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >


                                @error('date_debut')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- DATE DE FIN --}}

                            <div>

                                <label
                                    for="date_fin"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >

                                    Date de fin

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    type="date"
                                    id="date_fin"
                                    name="date_fin"
                                    value="{{ old('date_fin') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300
                                           shadow-sm
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                >


                                @error('date_fin')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>


                        {{-- =================================================
                             ANNÉE ACTIVE
                        ================================================== --}}

                        <div class="mt-6">

                            <label class="flex items-center">

                                <input
                                    type="checkbox"
                                    name="est_active"
                                    value="1"
                                    {{ old('est_active') ? 'checked' : '' }}
                                    class="rounded border-gray-300
                                           text-gray-700
                                           shadow-sm
                                           focus:ring-gray-500"
                                >

                                <span class="ml-3 text-sm text-gray-700">
                                    Définir comme année scolaire active
                                </span>

                            </label>


                            <p class="mt-2 ml-7 text-xs text-gray-500">
                                Une seule année scolaire peut être active
                                pour un établissement.
                            </p>

                        </div>


                        {{-- =================================================
                             INFORMATION
                        ================================================== --}}

                        <div class="mt-6 bg-gray-50 rounded-lg p-4">

                            <p class="text-sm text-gray-600">
                                L'année scolaire sera automatiquement
                                associée à votre établissement.
                            </p>

                        </div>


                        {{-- =================================================
                             BOUTONS
                        ================================================== --}}

                        <div class="mt-8 flex items-center justify-between">


                            <a
                                href="{{ route('annees-scolaires.index') }}"
                                class="px-5 py-2.5 bg-gray-100
                                       text-gray-700 rounded-lg
                                       hover:bg-gray-200 transition"
                            >
                                ← Annuler
                            </a>


                            <button
                                type="submit"
                                class="px-5 py-2.5 bg-gray-600
                                       text-white rounded-lg
                                       hover:bg-gray-700 transition"
                            >
                                💾 Enregistrer l'année scolaire
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
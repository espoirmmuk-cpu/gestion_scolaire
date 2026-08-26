<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Ajouter un utilisateur
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Créer un nouveau compte utilisateur
                </p>
            </div>

            <a
                href="{{ route('utilisateurs.index') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg
                       hover:bg-gray-700 transition"
            >
                ← Retour
            </a>

        </div>

    </x-slot>


    <div class="py-6 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-xl overflow-hidden">

                {{-- =====================================================
                     EN-TÊTE
                ====================================================== --}}

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Informations du compte
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Renseignez les informations du nouvel utilisateur.
                    </p>

                </div>


                {{-- =====================================================
                     FORMULAIRE
                ====================================================== --}}

                <form
                    action="{{ route('utilisateurs.store') }}"
                    method="POST"
                >

                    @csrf


                    <div class="p-6 space-y-6">


                        {{-- =================================================
                             NOM
                        ================================================== --}}

                        <div>

                            <label
                                for="nom"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Nom complet
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="nom"
                                id="nom"
                                value="{{ old('nom') }}"
                                required
                                maxlength="150"
                                class="mt-1 block w-full rounded-lg
                                       border-gray-300 shadow-sm
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                                placeholder="Ex. Jean Mukendi"
                            >

                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- =================================================
                             EMAIL
                        ================================================== --}}

                        <div>

                            <label
                                for="email"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Adresse e-mail
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                required
                                maxlength="150"
                                class="mt-1 block w-full rounded-lg
                                       border-gray-300 shadow-sm
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                                placeholder="exemple@email.com"
                            >

                            @error('email')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- =================================================
                             ÉTABLISSEMENT
                        ================================================== --}}

                        <div>

                            <label
                                for="id_etablissement"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Établissement
                            </label>

                            <select
                                name="id_etablissement"
                                id="id_etablissement"
                                class="mt-1 block w-full rounded-lg
                                       border-gray-300 shadow-sm
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                                <option value="">
                                    — Tous les établissements —
                                </option>

                                @foreach($etablissements as $etablissement)

                                    <option
                                        value="{{ $etablissement->id_etablissement }}"
                                        {{ old('id_etablissement') == $etablissement->id_etablissement ? 'selected' : '' }}
                                    >

                                        {{ $etablissement->nom }}

                                        @if($etablissement->ville)
                                            — {{ $etablissement->ville }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <p class="mt-1 text-xs text-gray-500">
                                Laissez vide pour un utilisateur ayant accès
                                à tous les établissements.
                            </p>

                            @error('id_etablissement')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- =================================================
                             RÔLE
                        ================================================== --}}

                        <div>

                            <label
                                for="id_role"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Rôle
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="id_role"
                                id="id_role"
                                required
                                class="mt-1 block w-full rounded-lg
                                       border-gray-300 shadow-sm
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                                <option value="">
                                    — Sélectionner un rôle —
                                </option>

                                @foreach($roles as $role)

                                    <option
                                        value="{{ $role->id_role }}"
                                        {{ old('id_role') == $role->id_role ? 'selected' : '' }}
                                    >

                                        {{ $role->nom }}

                                        @if($role->description)
                                            — {{ $role->description }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('id_role')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- =================================================
                             MOT DE PASSE
                        ================================================== --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            <div>

                                <label
                                    for="mot_de_passe"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Mot de passe
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="password"
                                    name="mot_de_passe"
                                    id="mot_de_passe"
                                    required
                                    minlength="6"
                                    class="mt-1 block w-full rounded-lg
                                           border-gray-300 shadow-sm
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                    placeholder="Minimum 6 caractères"
                                >

                                @error('mot_de_passe')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div>

                                <label
                                    for="mot_de_passe_confirmation"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Confirmer le mot de passe
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="password"
                                    name="mot_de_passe_confirmation"
                                    id="mot_de_passe_confirmation"
                                    required
                                    minlength="6"
                                    class="mt-1 block w-full rounded-lg
                                           border-gray-300 shadow-sm
                                           focus:border-gray-500
                                           focus:ring-gray-500"
                                    placeholder="Répétez le mot de passe"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                             STATUT
                        ================================================== --}}

                        <div>

                            <label
                                for="statut"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Statut
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="statut"
                                id="statut"
                                required
                                class="mt-1 block w-full rounded-lg
                                       border-gray-300 shadow-sm
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                                <option
                                    value="ACTIF"
                                    {{ old('statut', 'ACTIF') === 'ACTIF' ? 'selected' : '' }}
                                >
                                    Actif
                                </option>

                                <option
                                    value="INACTIF"
                                    {{ old('statut') === 'INACTIF' ? 'selected' : '' }}
                                >
                                    Inactif
                                </option>

                                <option
                                    value="BLOQUE"
                                    {{ old('statut') === 'BLOQUE' ? 'selected' : '' }}
                                >
                                    Bloqué
                                </option>

                            </select>

                            @error('statut')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                    </div>


                    {{-- =====================================================
                         BOUTONS
                    ====================================================== --}}

                    <div class="px-6 py-4 bg-gray-50 border-t
                                border-gray-200 flex items-center
                                justify-end gap-3">

                        <a
                            href="{{ route('utilisateurs.index') }}"
                            class="px-5 py-2.5 bg-gray-200
                                   text-gray-700 rounded-lg
                                   hover:bg-gray-300 transition"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-gray-800
                                   text-white rounded-lg
                                   hover:bg-gray-900 transition"
                        >
                            Enregistrer l'utilisateur
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
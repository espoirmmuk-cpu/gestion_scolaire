<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-xl font-semibold text-gray-800">

                    Modifier l'établissement

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    {{ $etablissement->nom }}

                </p>

            </div>


            <a href="{{ route('etablissements.show', $etablissement) }}"
               class="px-4 py-2 bg-gray-600 text-white
                      rounded-lg hover:bg-gray-700">

                ← Retour

            </a>

        </div>

    </x-slot>


    <div class="py-6 bg-gray-100">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


            {{-- ERREURS --}}

            @if($errors->any())

                <div class="mb-6 p-4 bg-red-100
                            border border-red-300
                            text-red-800 rounded-lg">

                    <p class="font-bold mb-2">

                        Veuillez corriger les erreurs suivantes :

                    </p>

                    <ul class="list-disc list-inside">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="bg-white shadow-lg rounded-xl overflow-hidden">


                {{-- =====================================================
                     TITRE
                ====================================================== --}}

                <div class="p-6 border-b border-gray-200">

                    <h3 class="text-lg font-bold text-gray-800">

                        Informations de l'établissement

                    </h3>

                    <p class="text-sm text-gray-500 mt-1">

                        Modifiez les informations ci-dessous.

                    </p>

                </div>


                {{-- FORMULAIRE --}}

                <form
                    method="POST"
                    action="{{ route('etablissements.update', $etablissement) }}"
                    enctype="multipart/form-data"
                >

                    @csrf

                    @method('PUT')


                    <div class="p-6 space-y-6">


                        {{-- =================================================
                             INFORMATIONS PRINCIPALES
                        ================================================== --}}

                        <div>

                            <h4 class="font-bold text-gray-800 mb-4">

                                Informations générales

                            </h4>


                            <div class="grid grid-cols-1 md:grid-cols-2
                                        gap-5">


                                {{-- NOM --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Nom de l'établissement
                                        <span class="text-red-500">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        name="nom"
                                        value="{{ old('nom', $etablissement->nom) }}"
                                        required
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                    @error('nom')

                                        <p class="text-sm text-red-600 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- CODE --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Code
                                        <span class="text-red-500">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        name="code"
                                        value="{{ old('code', $etablissement->code) }}"
                                        required
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                    @error('code')

                                        <p class="text-sm text-red-600 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- TYPE --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Type d'établissement

                                    </label>

                                    <input
                                        type="text"
                                        name="type"
                                        value="{{ old('type', $etablissement->type) }}"
                                        placeholder="Ex : École secondaire"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                    @error('type')

                                        <p class="text-sm text-red-600 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- DIRECTEUR --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Directeur

                                    </label>

                                    <input
                                        type="text"
                                        name="directeur"
                                        value="{{ old('directeur', $etablissement->directeur) }}"
                                        placeholder="Nom du directeur"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                    @error('directeur')

                                        <p class="text-sm text-red-600 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                            </div>

                        </div>


                        {{-- =================================================
                             LOCALISATION
                        ================================================== --}}

                        <div class="border-t pt-6">

                            <h4 class="font-bold text-gray-800 mb-4">

                                Localisation

                            </h4>


                            <div class="grid grid-cols-1 md:grid-cols-2
                                        gap-5">


                                {{-- PROVINCE --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Province

                                    </label>

                                    <input
                                        type="text"
                                        name="province"
                                        value="{{ old('province', $etablissement->province) }}"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                </div>


                                {{-- VILLE --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Ville

                                    </label>

                                    <input
                                        type="text"
                                        name="ville"
                                        value="{{ old('ville', $etablissement->ville) }}"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                </div>


                                {{-- COMMUNE --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Commune

                                    </label>

                                    <input
                                        type="text"
                                        name="commune"
                                        value="{{ old('commune', $etablissement->commune) }}"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                </div>


                                {{-- ADRESSE --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Adresse

                                    </label>

                                    <input
                                        type="text"
                                        name="adresse"
                                        value="{{ old('adresse', $etablissement->adresse) }}"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                </div>


                            </div>

                        </div>


                        {{-- =================================================
                             CONTACT
                        ================================================== --}}

                        <div class="border-t pt-6">

                            <h4 class="font-bold text-gray-800 mb-4">

                                Coordonnées

                            </h4>


                            <div class="grid grid-cols-1 md:grid-cols-2
                                        gap-5">


                                {{-- TELEPHONE --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Téléphone

                                    </label>

                                    <input
                                        type="text"
                                        name="telephone"
                                        value="{{ old('telephone', $etablissement->telephone) }}"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                </div>


                                {{-- EMAIL --}}

                                <div>

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-1">

                                        Email

                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email', $etablissement->email) }}"
                                        class="w-full rounded-lg
                                               border-gray-300
                                               focus:border-blue-500
                                               focus:ring-blue-500"
                                    >

                                    @error('email')

                                        <p class="text-sm text-red-600 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                            </div>

                        </div>


                        {{-- =================================================
                             LOGO
                        ================================================== --}}

                        <div class="border-t pt-6">

                            <h4 class="font-bold text-gray-800 mb-4">

                                Logo

                            </h4>


                            <div class="flex flex-col md:flex-row
                                        items-start gap-6">


                                {{-- ANCIEN LOGO --}}

                                <div>

                                    <p class="text-sm font-semibold
                                              text-gray-700 mb-2">

                                        Logo actuel

                                    </p>

                                    @if($etablissement->logo)

                                        <img
                                            src="{{ asset('storage/' . $etablissement->logo) }}"
                                            class="w-32 h-32 object-contain
                                                   border rounded-lg p-2"
                                        >

                                    @else

                                        <div class="w-32 h-32
                                                    border rounded-lg
                                                    flex items-center
                                                    justify-center
                                                    text-gray-400">

                                            Aucun logo

                                        </div>

                                    @endif

                                </div>


                                {{-- NOUVEAU LOGO --}}

                                <div class="flex-1">

                                    <label class="block text-sm
                                                  font-semibold
                                                  text-gray-700 mb-2">

                                        Nouveau logo

                                    </label>

                                    <input
                                        type="file"
                                        name="logo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        class="w-full border
                                               border-gray-300
                                               rounded-lg p-2"
                                    >

                                    <p class="text-xs text-gray-500 mt-2">

                                        JPG, JPEG, PNG ou WEBP.
                                        Taille maximale : 2 Mo.

                                    </p>

                                    @error('logo')

                                        <p class="text-sm text-red-600 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             STATUT
                        ================================================== --}}

                        <div class="border-t pt-6">

                            <label class="block text-sm
                                          font-semibold
                                          text-gray-700 mb-2">

                                Statut

                            </label>

                            <select
                                name="statut"
                                required
                                class="w-full md:w-1/2 rounded-lg
                                       border-gray-300
                                       focus:border-blue-500
                                       focus:ring-blue-500"
                            >

                                <option value="ACTIF"
                                    {{ old('statut', $etablissement->statut) === 'ACTIF'
                                        ? 'selected'
                                        : '' }}>

                                    ACTIF

                                </option>

                                <option value="INACTIF"
                                    {{ old('statut', $etablissement->statut) === 'INACTIF'
                                        ? 'selected'
                                        : '' }}>

                                    INACTIF

                                </option>

                            </select>

                            @error('statut')

                                <p class="text-sm text-red-600 mt-1">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                    </div>


                    {{-- =====================================================
                         BOUTONS
                    ====================================================== --}}

                    <div class="px-6 py-4 bg-gray-50
                                border-t flex justify-end gap-3">

                        <a
                            href="{{ route('etablissements.show', $etablissement) }}"
                            class="px-5 py-2.5 bg-gray-600
                                   text-white rounded-lg
                                   hover:bg-gray-700"
                        >

                            Annuler

                        </a>


                        <button
                            type="submit"
                            class="px-5 py-2.5 bg-blue-600
                                   text-white rounded-lg
                                   hover:bg-blue-700"
                        >

                            💾 Enregistrer les modifications

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
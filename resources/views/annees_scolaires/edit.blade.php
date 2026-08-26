<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier l'année scolaire
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Modifier les informations de l'année scolaire
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Erreurs --}}
            @if($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200
                            text-red-800 px-4 py-3 rounded-lg">

                    <div class="font-semibold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </div>

                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <form
                        method="POST"
                        action="{{ route('annees-scolaires.update', $annee->id_annee_scolaire) }}"
                    >

                        @csrf

                        @method('PUT')


                        {{-- Libellé --}}
                        <div class="mb-6">

                            <label
                                for="libelle"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Année scolaire
                            </label>

                            <input
                                type="text"
                                id="libelle"
                                name="libelle"
                                value="{{ old('libelle', $annee->libelle) }}"
                                maxlength="20"
                                required
                                class="w-full rounded-md border-gray-300
                                       shadow-sm focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                            @error('libelle')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Dates --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>

                                <label
                                    for="date_debut"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Date de début
                                </label>

                                <input
                                    type="date"
                                    id="date_debut"
                                    name="date_debut"
                                    value="{{ old(
                                        'date_debut',
                                        \Carbon\Carbon::parse($annee->date_debut)->format('Y-m-d')
                                    ) }}"
                                    required
                                    class="w-full rounded-md border-gray-300
                                           shadow-sm focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                                @error('date_debut')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div>

                                <label
                                    for="date_fin"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Date de fin
                                </label>

                                <input
                                    type="date"
                                    id="date_fin"
                                    name="date_fin"
                                    value="{{ old(
                                        'date_fin',
                                        \Carbon\Carbon::parse($annee->date_fin)->format('Y-m-d')
                                    ) }}"
                                    required
                                    class="w-full rounded-md border-gray-300
                                           shadow-sm focus:border-gray-500
                                           focus:ring-gray-500"
                                >

                                @error('date_fin')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>


                        {{-- Année active --}}
                        <div class="mt-6">

                            <label class="flex items-center">

                                <input
                                    type="checkbox"
                                    name="est_active"
                                    value="1"
                                    {{ old(
                                        'est_active',
                                        $annee->est_active
                                    ) ? 'checked' : '' }}
                                    class="rounded border-gray-300
                                           text-gray-700 shadow-sm
                                           focus:ring-gray-500"
                                >

                                <span class="ml-3 text-sm text-gray-700">

                                    Année scolaire active

                                </span>

                            </label>

                            <p class="mt-2 ml-7 text-xs text-gray-500">
                                Une seule année scolaire peut être active
                                pour un établissement.
                            </p>

                        </div>


                        {{-- Informations --}}
                        <div class="mt-6 bg-gray-50 rounded-lg p-4">

                            <div class="text-sm text-gray-600">

                                <strong>ID :</strong>
                                {{ $annee->id_annee_scolaire }}

                            </div>

                            @if(isset($annee->date_creation))

                                <div class="text-sm text-gray-600 mt-1">

                                    <strong>Date de création :</strong>

                                    {{ \Carbon\Carbon::parse($annee->date_creation)->format('d/m/Y H:i') }}

                                </div>

                            @endif

                        </div>


                        {{-- Boutons --}}
                        <div class="mt-8 flex items-center justify-end gap-3">

                            <a
                                href="{{ route('annees-scolaires.index') }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700
                                       rounded-md hover:bg-gray-200 transition"
                            >
                                Annuler
                            </a>

                            <button
                                type="submit"
                                class="px-5 py-2 bg-gray-800 text-white
                                       rounded-md hover:bg-gray-700 transition"
                            >
                                Enregistrer les modifications
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
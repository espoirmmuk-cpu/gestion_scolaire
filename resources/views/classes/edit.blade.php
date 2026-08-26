<x-app-layout>

```
<x-slot name="header">

    <div class="flex justify-between items-center">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800">
                Modifier la classe
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Modifier les informations de la classe
            </p>
        </div>

        <a href="{{ route('classes.show', $classe) }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            Retour
        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


        {{-- Erreurs de validation --}}
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


        <div class="bg-white shadow-sm rounded-lg overflow-hidden">

            <form method="POST"
                  action="{{ route('classes.update', $classe) }}">

                @csrf

                @method('PUT')


                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-6">
                        Informations de la classe
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- Année scolaire --}}
                        <div>

                            <label for="id_annee_scolaire"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Année scolaire *
                            </label>

                            <select
                                id="id_annee_scolaire"
                                name="id_annee_scolaire"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                @foreach($anneesScolaires as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        {{ old('id_annee_scolaire', $classe->id_annee_scolaire) == $annee->id_annee_scolaire ? 'selected' : '' }}>

                                        {{ $annee->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Niveau --}}
                        <div>

                            <label for="id_niveau"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Niveau *
                            </label>

                            <select
                                id="id_niveau"
                                name="id_niveau"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                @foreach($niveaux as $niveau)

                                    <option
                                        value="{{ $niveau->id_niveau }}"
                                        {{ old('id_niveau', $classe->id_niveau) == $niveau->id_niveau ? 'selected' : '' }}>

                                        {{ $niveau->libelle }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Libellé --}}
                        <div>

                            <label for="libelle"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Libellé de la classe *
                            </label>

                            <input
                                type="text"
                                id="libelle"
                                name="libelle"
                                value="{{ old('libelle', $classe->libelle) }}"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                        </div>


                        {{-- Option --}}
                        <div>

                            <label for="option_classe"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Option
                            </label>

                            <input
                                type="text"
                                id="option_classe"
                                name="option_classe"
                                value="{{ old('option_classe', $classe->option_classe) }}"
                                placeholder="Exemple : Scientifique"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                        </div>


                        {{-- Capacité --}}
                        <div>

                            <label for="capacite"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Capacité *
                            </label>

                            <input
                                type="number"
                                id="capacite"
                                name="capacite"
                                value="{{ old('capacite', $classe->capacite) }}"
                                min="1"
                                max="200"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

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
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                <option value="ACTIVE"
                                    {{ old('statut', $classe->statut) === 'ACTIVE' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="INACTIVE"
                                    {{ old('statut', $classe->statut) === 'INACTIVE' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- Boutons --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">

                    <a href="{{ route('classes.show', $classe) }}"
                       class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Enregistrer les modifications
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
```

</x-app-layout>

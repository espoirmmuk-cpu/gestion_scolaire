<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Modifier l'utilisateur
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $utilisateur->nom }}
                </p>
            </div>

            <a href="{{ route('utilisateurs.show', $utilisateur) }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                ← Retour
            </a>

        </div>
    </x-slot>


    <div class="py-6 bg-gray-100">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-xl overflow-hidden">

                <div class="p-6">

                    {{-- Messages d'erreur --}}

                    @if($errors->any())

                        <div class="mb-6 bg-red-50 border border-red-300
                                    text-red-700 rounded-lg p-4">

                            <p class="font-bold mb-2">
                                Veuillez corriger les erreurs suivantes :
                            </p>

                            <ul class="list-disc ml-5 text-sm">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form method="POST"
                          action="{{ route('utilisateurs.update', $utilisateur) }}">

                        @csrf
                        @method('PUT')


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            {{-- Établissement --}}

                            <div class="md:col-span-2">

                                <label for="id_etablissement"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Établissement

                                </label>

                                <select name="id_etablissement"
                                        id="id_etablissement"
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-blue-500 focus:ring-blue-500">

                                    <option value="">
                                        Aucun établissement
                                    </option>

                                    @foreach($etablissements as $etablissement)

                                        <option value="{{ $etablissement->id_etablissement }}"
                                            {{ old(
                                                'id_etablissement',
                                                $utilisateur->id_etablissement
                                            ) == $etablissement->id_etablissement
                                                ? 'selected'
                                                : '' }}>

                                            {{ $etablissement->nom }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Nom --}}

                            <div>

                                <label for="nom"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Nom

                                </label>

                                <input type="text"
                                       name="nom"
                                       id="nom"
                                       value="{{ old('nom', $utilisateur->nom) }}"
                                       required
                                       maxlength="150"
                                       class="w-full rounded-lg border-gray-300
                                              focus:border-blue-500 focus:ring-blue-500">

                            </div>


                            {{-- Email --}}

                            <div>

                                <label for="email"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Adresse e-mail

                                </label>

                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', $utilisateur->email) }}"
                                       required
                                       maxlength="150"
                                       class="w-full rounded-lg border-gray-300
                                              focus:border-blue-500 focus:ring-blue-500">

                            </div>


                            {{-- Nouveau mot de passe --}}

                            <div>

                                <label for="mot_de_passe"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Nouveau mot de passe

                                </label>

                                <input type="password"
                                       name="mot_de_passe"
                                       id="mot_de_passe"
                                       minlength="6"
                                       class="w-full rounded-lg border-gray-300
                                              focus:border-blue-500 focus:ring-blue-500">

                                <p class="text-xs text-gray-500 mt-1">
                                    Laisser vide pour conserver le mot de passe actuel.
                                </p>

                            </div>


                            {{-- Confirmation --}}

                            <div>

                                <label for="mot_de_passe_confirmation"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Confirmer le nouveau mot de passe

                                </label>

                                <input type="password"
                                       name="mot_de_passe_confirmation"
                                       id="mot_de_passe_confirmation"
                                       minlength="6"
                                       class="w-full rounded-lg border-gray-300
                                              focus:border-blue-500 focus:ring-blue-500">

                            </div>


                            {{-- Rôle --}}

                            <div>

                                <label for="id_role"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Rôle

                                </label>

                                <select name="id_role"
                                        id="id_role"
                                        required
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-blue-500 focus:ring-blue-500">

                                    @foreach($roles as $role)

                                        <option value="{{ $role->id_role }}"
                                            {{ old(
                                                'id_role',
                                                optional($utilisateur->roles->first())->id_role
                                            ) == $role->id_role
                                                ? 'selected'
                                                : '' }}>

                                            {{ $role->nom }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Statut --}}

                            <div>

                                <label for="statut"
                                       class="block text-sm font-semibold text-gray-700 mb-2">

                                    Statut

                                </label>

                                <select name="statut"
                                        id="statut"
                                        required
                                        class="w-full rounded-lg border-gray-300
                                               focus:border-blue-500 focus:ring-blue-500">

                                    <option value="ACTIF"
                                        {{ old('statut', $utilisateur->statut) === 'ACTIF'
                                            ? 'selected'
                                            : '' }}>
                                        ACTIF
                                    </option>

                                    <option value="INACTIF"
                                        {{ old('statut', $utilisateur->statut) === 'INACTIF'
                                            ? 'selected'
                                            : '' }}>
                                        INACTIF
                                    </option>

                                    <option value="BLOQUE"
                                        {{ old('statut', $utilisateur->statut) === 'BLOQUE'
                                            ? 'selected'
                                            : '' }}>
                                        BLOQUÉ
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Boutons --}}

                        <div class="flex justify-end gap-3 mt-8 pt-6 border-t">

                            <a href="{{ route('utilisateurs.show', $utilisateur) }}"
                               class="px-5 py-2.5 bg-gray-500 text-white rounded-lg
                                      hover:bg-gray-600">

                                Annuler

                            </a>

                            <button type="submit"
                                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg
                                           hover:bg-blue-700">

                                💾 Enregistrer les modifications

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
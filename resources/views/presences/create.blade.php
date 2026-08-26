<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Saisie collective des présences') }}
            </h2>

            <a href="{{ route('presences.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages d'erreur --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                    <div class="font-semibold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </div>

                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Message de succès --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ============================================================
                 ÉTAPE 1 : CHOIX DE LA CLASSE ET DE LA DATE
            ============================================================= --}}

            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    1. Sélectionner la classe
                </h3>

                <form method="GET"
                      action="{{ route('presences.create') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Classe --}}
                        <div>
                            <label for="id_classe"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Classe
                            </label>

                            <select name="id_classe"
                                    id="id_classe"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                <option value="">
                                    -- Sélectionner une classe --
                                </option>

                                @foreach ($classes as $classe)

                                    <option value="{{ $classe->id_classe }}"
                                        {{ request('id_classe') == $classe->id_classe ? 'selected' : '' }}>

                                        {{ $classe->libelle }}

                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- Date --}}
                        <div>
                            <label for="date_presence"
                                   class="block text-sm font-medium text-gray-700 mb-1">
                                Date
                            </label>

                            <input type="date"
                                   name="date_presence"
                                   id="date_presence"
                                   value="{{ request('date_presence', now()->format('Y-m-d')) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>


                        {{-- Bouton charger --}}
                        <div class="flex items-end">

                            <button type="submit"
                                    class="w-full px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                                Charger les élèves

                            </button>

                        </div>

                    </div>

                </form>

            </div>


            {{-- ============================================================
                 ÉTAPE 2 : LISTE DES ÉLÈVES
            ============================================================= --}}

            @if(request('id_classe') && isset($eleves) && $eleves->count() > 0)

                <form method="POST"
                      action="{{ route('presences.store') }}">

                    @csrf

                    {{-- Classe et date envoyées au contrôleur --}}
                    <input type="hidden"
                           name="id_classe"
                           value="{{ request('id_classe') }}">

                    <input type="hidden"
                           name="date_presence"
                           value="{{ request('date_presence') }}">


                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">

                        <div class="p-6 border-b bg-gray-50">

                            <div class="flex items-center justify-between">

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        2. Saisir les présences
                                    </h3>

                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $eleves->count() }}
                                        élève(s) trouvé(s)
                                    </p>
                                </div>

                                <div class="text-sm text-gray-600">
                                    Date :
                                    <strong>
                                        {{ \Carbon\Carbon::parse(request('date_presence'))->format('d/m/Y') }}
                                    </strong>
                                </div>

                            </div>

                        </div>


                        {{-- Tableau --}}
                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-100">

                                    <tr>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                            #
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                            Matricule
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                            Élève
                                        </th>

                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                            Statut
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                            Motif
                                        </th>

                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                            Observation
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach($eleves as $index => $eleve)

                                        <tr class="hover:bg-gray-50">

                                            {{-- Numéro --}}
                                            <td class="px-4 py-3 text-sm text-gray-600">
                                                {{ $index + 1 }}
                                            </td>


                                            {{-- Matricule --}}
                                            <td class="px-4 py-3 text-sm font-medium text-gray-800">

                                                {{ $eleve->matricule }}

                                            </td>


                                            {{-- Élève --}}
                                            <td class="px-4 py-3 text-sm text-gray-800">

                                                {{ trim(
                                                    $eleve->nom . ' ' .
                                                    ($eleve->postnom ?? '') . ' ' .
                                                    ($eleve->prenom ?? '')
                                                ) }}

                                            </td>


                                            {{-- Statut --}}
                                            <td class="px-4 py-3 text-center">

                                                <select
                                                    name="presences[{{ $eleve->id_eleve }}][statut]"
                                                    required
                                                    class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                                    <option value="PRESENT">
                                                        Présent
                                                    </option>

                                                    <option value="ABSENT">
                                                        Absent
                                                    </option>

                                                    <option value="RETARD">
                                                        Retard
                                                    </option>

                                                    <option value="JUSTIFIE">
                                                        Absence justifiée
                                                    </option>

                                                </select>

                                            </td>


                                            {{-- Motif --}}
                                            <td class="px-4 py-3">

                                                <input type="text"
                                                       name="presences[{{ $eleve->id_eleve }}][motif]"
                                                       maxlength="255"
                                                       placeholder="Motif..."
                                                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                                            </td>


                                            {{-- Observation --}}
                                            <td class="px-4 py-3">

                                                <input type="text"
                                                       name="presences[{{ $eleve->id_eleve }}][observation]"
                                                       maxlength="1000"
                                                       placeholder="Observation..."
                                                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                        {{-- Boutons --}}
                        <div class="p-6 bg-gray-50 border-t flex justify-end items-center gap-3">

                            {{-- Annuler --}}
                            <a href="{{ route('presences.create') }}"
                            class="inline-flex items-center px-5 py-2.5
                                    bg-gray-600 text-white font-semibold
                                    rounded-lg shadow-sm
                                    hover:bg-gray-700
                                    focus:outline-none focus:ring-2 focus:ring-gray-700">

                                Annuler

                            </a>


                            {{-- Enregistrer --}}
                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5
                                        bg-gray-600 text-white font-semibold
                                        rounded-lg shadow-sm
                                        hover:bg-gray-700
                                        focus:outline-none focus:ring-2 focus:ring-gray-700">

                                Enregistrer les présences

                            </button>

                        </div>

                    </div>

                </form>


            @elseif(request('id_classe') && isset($eleves) && $eleves->count() === 0)

                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg p-6">

                    <div class="font-semibold">
                        Aucun élève trouvé
                    </div>

                    <p class="mt-1 text-sm">
                        Aucun élève n'est actuellement inscrit dans cette classe.
                    </p>

                </div>

            @endif

        </div>
    </div>

</x-app-layout>
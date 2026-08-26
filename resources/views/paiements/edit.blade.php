<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-2xl text-gray-800">
                    Modifier le paiement
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Modifier les informations du paiement scolaire
                </p>

            </div>

            <a
                href="{{ route('paiements.index', $paiement) }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">

                ← Retour

            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


            {{-- Messages d'erreur --}}

            @if ($errors->any())

                <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">

                    <p class="font-semibold mb-2">
                        Veuillez corriger les erreurs suivantes :
                    </p>

                    <ul class="list-disc list-inside">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            @if (session('error'))

                <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">

                    {{ session('error') }}

                </div>

            @endif


            {{-- Formulaire --}}

            <div class="bg-white shadow-sm rounded-xl overflow-hidden">

                <form
                    method="POST"
                    action="{{ route('paiements.update', $paiement) }}">

                    @csrf

                    @method('PUT')


                    <div class="p-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-6">
                            Informations du paiement
                        </h3>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                            {{-- Élève --}}

                            <div class="md:col-span-2">

                                <label
                                    for="id_eleve"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Élève *

                                </label>


                                <select
                                    id="id_eleve"
                                    name="id_eleve"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                    <option value="">
                                        -- Sélectionner un élève --
                                    </option>


                                    @foreach ($eleves as $eleve)

                                        <option
                                            value="{{ $eleve->id_eleve }}"
                                            {{ old('id_eleve', $paiement->id_eleve) == $eleve->id_eleve ? 'selected' : '' }}>

                                            {{ $eleve->matricule }}
                                            -
                                            {{ $eleve->nom }}
                                            {{ $eleve->postnom }}
                                            {{ $eleve->prenom }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('id_eleve')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Frais scolaires --}}

                            <div class="md:col-span-2">

                                <h4 class="font-semibold text-gray-800 mb-3">
                                    Frais scolaires
                                </h4>


                                <div
                                    id="chargement-frais"
                                    class="hidden bg-blue-50 border border-blue-200 text-blue-700 rounded-lg p-4 mb-3">

                                    Chargement des frais de l'élève...

                                </div>


                                <div
                                    id="message-frais"
                                    class="bg-gray-50 border border-gray-200 rounded-lg p-5 text-center text-gray-500">

                                    Chargement des frais...

                                </div>


                                <div
                                    id="liste-frais"
                                    class="hidden space-y-3">
                                </div>


                                <div class="mt-5">

                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                        Frais scolaires à payer
                                    </h3>


                                    <div class="overflow-x-auto border border-gray-200 rounded-lg">

                                        <table class="min-w-full divide-y divide-gray-200">

                                            <thead class="bg-gray-50">

                                                <tr>

                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Payer
                                                    </th>

                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                        Frais
                                                    </th>

                                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                        Montant
                                                    </th>

                                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                        Déjà payé
                                                    </th>

                                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                        Solde
                                                    </th>

                                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                        À payer
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody
                                                id="frais-list"
                                                class="bg-white divide-y divide-gray-200">

                                            </tbody>

                                        </table>

                                    </div>


                                    <p
                                        id="frais-message"
                                        class="mt-3 text-sm text-gray-500">
                                    </p>

                                </div>

                            </div>


                            {{-- Numéro reçu --}}

                            <div>

                                <label
                                    for="numero_recu"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Numéro du reçu *

                                </label>


                                <input
                                    type="text"
                                    id="numero_recu"
                                    name="numero_recu"
                                    value="{{ old('numero_recu', $paiement->numero_recu) }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">


                                @error('numero_recu')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Date --}}

                            <div>

                                <label
                                    for="date_paiement"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Date du paiement *

                                </label>


                                <input
                                    type="datetime-local"
                                    id="date_paiement"
                                    name="date_paiement"
                                    value="{{ old('date_paiement', $paiement->date_paiement ? $paiement->date_paiement->format('Y-m-d\TH:i') : '') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">


                                @error('date_paiement')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Montant total --}}

                            <div>

                                <label
                                    for="montant_total"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Montant total *

                                </label>


                                <input
                                    type="number"
                                    id="montant_total"
                                    name="montant_total"
                                    value="{{ old('montant_total', $paiement->montant_total) }}"
                                    min="0.01"
                                    step="0.01"
                                    required
                                    readonly
                                    class="w-full rounded-lg border-gray-300 bg-gray-50">


                                <p class="text-xs text-gray-400 mt-1">
                                    Calculé automatiquement à partir des frais sélectionnés.
                                </p>


                                @error('montant_total')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Devise --}}

                            <div>

                                <label
                                    for="devise"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Devise *

                                </label>


                                <select
                                    id="devise"
                                    name="devise"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                    <option
                                        value="USD"
                                        {{ old('devise', $paiement->devise) === 'USD' ? 'selected' : '' }}>

                                        USD - Dollar américain

                                    </option>


                                    <option
                                        value="CDF"
                                        {{ old('devise', $paiement->devise) === 'CDF' ? 'selected' : '' }}>

                                        CDF - Franc congolais

                                    </option>

                                </select>


                                @error('devise')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Mode de paiement --}}

                            <div>

                                <label
                                    for="mode_paiement"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Mode de paiement *

                                </label>


                                <select
                                    id="mode_paiement"
                                    name="mode_paiement"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">

                                    <option
                                        value="ESPECES"
                                        {{ old('mode_paiement', $paiement->mode_paiement) === 'ESPECES' ? 'selected' : '' }}>

                                        Espèces

                                    </option>


                                    <option
                                        value="BANQUE"
                                        {{ old('mode_paiement', $paiement->mode_paiement) === 'BANQUE' ? 'selected' : '' }}>

                                        Banque

                                    </option>


                                    <option
                                        value="MOBILE_MONEY"
                                        {{ old('mode_paiement', $paiement->mode_paiement) === 'MOBILE_MONEY' ? 'selected' : '' }}>

                                        Mobile Money

                                    </option>


                                    <option
                                        value="CHEQUE"
                                        {{ old('mode_paiement', $paiement->mode_paiement) === 'CHEQUE' ? 'selected' : '' }}>

                                        Chèque

                                    </option>


                                    <option
                                        value="AUTRE"
                                        {{ old('mode_paiement', $paiement->mode_paiement) === 'AUTRE' ? 'selected' : '' }}>

                                        Autre

                                    </option>

                                </select>


                                @error('mode_paiement')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Référence --}}

                            <div>

                                <label
                                    for="reference"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Référence

                                </label>


                                <input
                                    type="text"
                                    id="reference"
                                    name="reference"
                                    value="{{ old('reference', $paiement->reference) }}"
                                    placeholder="Exemple : TXN123456"
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500">


                                @error('reference')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- Observation --}}

                            <div class="md:col-span-2">

                                <label
                                    for="observation"
                                    class="block text-sm font-medium text-gray-700 mb-1">

                                    Observation

                                </label>


                                <textarea
                                    id="observation"
                                    name="observation"
                                    rows="4"
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                                    placeholder="Observation éventuelle...">{{ old('observation', $paiement->observation) }}</textarea>


                                @error('observation')

                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                        </div>

                    </div>


                    {{-- Boutons --}}

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">

                        <a
                            href="{{ route('paiements.show', $paiement) }}"
                            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">

                            Annuler

                        </a>


                        <button
                            type="submit"
                            id="btn-enregistrer"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                            Enregistrer les modifications

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DONNÉES DU PAIEMENT POUR JAVASCRIPT                       --}}
    {{-- ========================================================= --}}

    <script>

        const paiementId = @json($paiement->id_paiement ?? $paiement->id);

        const eleveId = @json($paiement->id_eleve);

        console.log('Paiement :', paiementId);

        console.log('Élève :', eleveId);

    </script>

</x-app-layout>
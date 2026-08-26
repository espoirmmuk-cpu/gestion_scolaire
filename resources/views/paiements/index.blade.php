<x-app-layout>
    
<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800">
                Paiements
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gestion des paiements scolaires
            </p>

        </div>


        <a href="{{ route('paiements.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

            + Nouveau paiement

        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Message de succès --}}
        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        {{-- Recherche et filtres --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">

            <form method="GET"
                  action="{{ route('paiements.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">


                    {{-- Recherche --}}
                    <div class="md:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Recherche
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="N° reçu, matricule ou nom..."
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                    </div>


                    {{-- Devise --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Devise
                        </label>

                        <select
                            name="devise"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                Toutes
                            </option>

                            <option value="USD"
                                {{ request('devise') === 'USD' ? 'selected' : '' }}>
                                USD
                            </option>

                            <option value="CDF"
                                {{ request('devise') === 'CDF' ? 'selected' : '' }}>
                                CDF
                            </option>

                        </select>

                    </div>


                    {{-- Mode --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mode
                        </label>

                        <select
                            name="mode_paiement"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                            <option value="">
                                Tous
                            </option>

                            <option value="ESPECES"
                                {{ request('mode_paiement') === 'ESPECES' ? 'selected' : '' }}>
                                Espèces
                            </option>

                            <option value="BANQUE"
                                {{ request('mode_paiement') === 'BANQUE' ? 'selected' : '' }}>
                                Banque
                            </option>

                            <option value="MOBILE_MONEY"
                                {{ request('mode_paiement') === 'MOBILE_MONEY' ? 'selected' : '' }}>
                                Mobile Money
                            </option>

                            <option value="CHEQUE"
                                {{ request('mode_paiement') === 'CHEQUE' ? 'selected' : '' }}>
                                Chèque
                            </option>

                            <option value="AUTRE"
                                {{ request('mode_paiement') === 'AUTRE' ? 'selected' : '' }}>
                                Autre
                            </option>

                        </select>

                    </div>


                    {{-- Bouton --}}
                    <div class="flex items-end">

                        <button
                            type="submit"
                            class="w-full px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">

                            Rechercher

                        </button>

                    </div>

                </div>


                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">


                    {{-- Date début --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Du
                        </label>

                        <input
                            type="date"
                            name="date_debut"
                            value="{{ request('date_debut') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                    </div>


                    {{-- Date fin --}}
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Au
                        </label>

                        <input
                            type="date"
                            name="date_fin"
                            value="{{ request('date_fin') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                    </div>


                    {{-- Réinitialiser --}}
                    <div class="flex items-end">

                        <a
                            href="{{ route('paiements.index') }}"
                            class="w-full text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">

                            Réinitialiser

                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- Tableau --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Liste des paiements
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $paiements->total() }} paiement(s)
                        </p>

                    </div>

                </div>

            </div>


            @if($paiements->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Reçu
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Élève
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                    Montant
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Mode
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($paiements as $paiement)

                                <tr class="hover:bg-gray-50">


                                    {{-- Reçu --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="font-medium text-gray-800">
                                            {{ $paiement->numero_recu }}
                                        </div>

                                        @if($paiement->reference)

                                            <div class="text-xs text-gray-400">
                                                Réf. {{ $paiement->reference }}
                                            </div>

                                        @endif

                                    </td>


                    

                                    
                                    <td class="px-6 py-4">

                                        @if($paiement->eleve)

                                            <div class="font-medium text-gray-800">

                                                {{ $paiement->eleve->nom }}

                                                {{ $paiement->eleve->postnom }}

                                                {{ $paiement->eleve->prenom }}

                                            </div>

                                            <div class="text-xs text-gray-400">

                                                {{ $paiement->eleve->matricule }}

                                            </div>

                                        @else

                                            <span class="text-red-500">
                                                Élève introuvable
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Date --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <div class="text-sm text-gray-800">

                                            {{ $paiement->date_paiement?->format('d/m/Y') }}

                                        </div>

                                        <div class="text-xs text-gray-400">

                                            {{ $paiement->date_paiement?->format('H:i') }}

                                        </div>

                                    </td>


                                    {{-- Montant --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right">

                                        <span class="font-semibold text-gray-800">

                                            {{ number_format($paiement->montant_total, 2, ',', ' ') }}

                                            {{ $paiement->devise }}

                                        </span>

                                    </td>


                                    {{-- Mode --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @switch($paiement->mode_paiement)

                                            @case('ESPECES')

                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                                    Espèces
                                                </span>

                                                @break

                                            @case('BANQUE')

                                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                                    Banque
                                                </span>

                                                @break

                                            @case('MOBILE_MONEY')

                                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                                                    Mobile Money
                                                </span>

                                                @break

                                            @case('CHEQUE')

                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                                    Chèque
                                                </span>

                                                @break

                                            @default

                                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                                    Autre
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">

                                        <div class="flex items-center justify-center gap-2">


                                            <a
                                                href="{{ route('paiements.show', $paiement) }}"
                                                class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">

                                                Voir

                                            </a>


                                            <a
                                                href="{{ route('paiements.edit', $paiement) }}"
                                                class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200">

                                                Modifier

                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route('paiements.destroy', $paiement) }}"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce paiement ?');">

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200">

                                                    Supprimer

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">

                    {{ $paiements->links() }}

                </div>

            @else

                <div class="p-12 text-center">

                    <div class="text-5xl mb-4">
                        💰
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        Aucun paiement trouvé
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Aucun paiement ne correspond aux critères sélectionnés.
                    </p>

                    <a
                        href="{{ route('paiements.create') }}"
                        class="inline-block mt-5 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">

                        Enregistrer un paiement

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

</x-app-layout>

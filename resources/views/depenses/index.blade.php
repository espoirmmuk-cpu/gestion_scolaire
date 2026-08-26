<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des dépenses
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gestion des sorties financières de l'établissement
            </p>

        </div>

        @can('create', App\Models\Depense::class)

            <a href="{{ route('depenses.create') }}"
               class="inline-flex items-center px-5 py-2.5
                      bg-gray-600 text-white rounded-lg
                      hover:bg-gray-700 transition">

                <span class="mr-2 text-lg">+</span>

                Nouvelle dépense

            </a>

        @endcan

        <a href="{{ route('caisse.index') }}"
            class="inline-flex items-center px-4 py-2.5
                    bg-gray-600 text-white rounded-lg
                    hover:bg-gray-700 transition">

                📊 Situation de caisse 

            </a>

        </div>

</x-slot>


<div class="py-6 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- MESSAGES --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="mb-5 px-4 py-3
                        bg-green-100 border border-green-300
                        text-green-800 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="mb-5 px-4 py-3
                        bg-red-100 border border-red-300
                        text-red-800 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        @if($errors->any())

            <div class="mb-5 px-4 py-3
                        bg-red-100 border border-red-300
                        text-red-800 rounded-lg">

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



        {{-- ========================================================= --}}
        {{-- SITUATION DE CAISSE --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">


            {{-- CAISSE USD --}}

            <div class="bg-white rounded-xl shadow-sm p-6">

                <div class="flex items-center justify-between mb-4">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Situation de caisse USD
                        </h3>

                        <p class="text-sm text-gray-500">
                            Recettes - Dépenses
                        </p>

                    </div>

                    <div class="text-3xl">
                        💰
                    </div>

                </div>


                <div class="space-y-3">

                    <div class="flex justify-between">

                        <span class="text-sm text-gray-600">
                            Total recettes
                        </span>

                        <span class="font-semibold text-gray-800">

                            {{ number_format(
                                (float) $totalRecettesUSD,
                                2,
                                ',',
                                ' '
                            ) }}

                            USD

                        </span>

                    </div>


                    <div class="flex justify-between">

                        <span class="text-sm text-gray-600">
                            Total dépenses
                        </span>

                        <span class="font-semibold text-gray-800">

                            {{ number_format(
                                (float) $totalDepensesUSD,
                                2,
                                ',',
                                ' '
                            ) }}

                            USD

                        </span>

                    </div>


                    <div class="border-t pt-3 flex justify-between">

                        <span class="font-semibold text-gray-700">
                            Solde
                        </span>

                        <span class="font-bold
                            {{ $soldeUSD >= 0
                                ? 'text-green-600'
                                : 'text-red-600' }}">

                            {{ number_format(
                                (float) $soldeUSD,
                                2,
                                ',',
                                ' '
                            ) }}

                            USD

                        </span>

                    </div>

                </div>

            </div>


            {{-- CAISSE CDF --}}

            <div class="bg-white rounded-xl shadow-sm p-6">

                <div class="flex items-center justify-between mb-4">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Situation de caisse CDF
                        </h3>

                        <p class="text-sm text-gray-500">
                            Recettes - Dépenses
                        </p>

                    </div>

                    <div class="text-3xl">
                        💵
                    </div>

                </div>


                <div class="space-y-3">

                    <div class="flex justify-between">

                        <span class="text-sm text-gray-600">
                            Total recettes
                        </span>

                        <span class="font-semibold text-gray-800">

                            {{ number_format(
                                (float) $totalRecettesCDF,
                                2,
                                ',',
                                ' '
                            ) }}

                            CDF

                        </span>

                    </div>


                    <div class="flex justify-between">

                        <span class="text-sm text-gray-600">
                            Total dépenses
                        </span>

                        <span class="font-semibold text-gray-800">

                            {{ number_format(
                                (float) $totalDepensesCDF,
                                2,
                                ',',
                                ' '
                            ) }}

                            CDF

                        </span>

                    </div>


                    <div class="border-t pt-3 flex justify-between">

                        <span class="font-semibold text-gray-700">
                            Solde
                        </span>

                        <span class="font-bold
                            {{ $soldeCDF >= 0
                                ? 'text-green-600'
                                : 'text-red-600' }}">

                            {{ number_format(
                                (float) $soldeCDF,
                                2,
                                ',',
                                ' '
                            ) }}

                            CDF

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTRES --}}
        {{-- ========================================================= --}}

        <div class="bg-white shadow-sm rounded-xl mb-6">

            <div class="p-6">

                <div class="flex items-center justify-between mb-5">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Rechercher une dépense
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Les totaux ci-dessus correspondent aux filtres sélectionnés.
                        </p>

                    </div>

                </div>


                <form method="GET"
                      action="{{ route('depenses.index') }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">


                        {{-- ANNÉE SCOLAIRE --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Année scolaire
                            </label>

                            <select
                                name="id_annee_scolaire"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                                <option value="">
                                    Toutes les années
                                </option>

                                @foreach($anneesScolaires as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        @selected(
                                            request('id_annee_scolaire')
                                            == $annee->id_annee_scolaire
                                        )>

                                        {{ $annee->libelle
                                            ?? $annee->annee
                                            ?? $annee->nom
                                            ?? $annee->id_annee_scolaire }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- DATE DÉBUT --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date début
                            </label>

                            <input
                                type="date"
                                name="date_debut"
                                value="{{ request('date_debut') }}"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                        </div>


                        {{-- DATE FIN --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date fin
                            </label>

                            <input
                                type="date"
                                name="date_fin"
                                value="{{ request('date_fin') }}"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                        </div>


                        {{-- CATÉGORIE --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Catégorie
                            </label>

                            <input
                                type="text"
                                name="categorie"
                                value="{{ request('categorie') }}"
                                list="categories"
                                placeholder="Ex. fournitures..."
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500"
                            >

                            <datalist id="categories">

                                @foreach($categories as $categorie)

                                    <option value="{{ $categorie }}">

                                @endforeach

                            </datalist>

                        </div>


                        {{-- DEVISE --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Devise
                            </label>

                            <select
                                name="devise"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                                <option value="">
                                    Toutes les devises
                                </option>

                                @foreach($devises as $devise)

                                    <option
                                        value="{{ $devise }}"
                                        @selected(request('devise') == $devise)>

                                        {{ $devise }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- BOUTONS --}}

                    <div class="mt-5 flex flex-wrap gap-3">

                        <button
                            type="submit"
                            class="px-5 py-2.5
                                   bg-gray-700 text-white
                                   rounded-lg
                                   hover:bg-gray-800 transition">

                            Rechercher

                        </button>


                        @if(request()->hasAny([
                            'id_annee_scolaire',
                            'date_debut',
                            'date_fin',
                            'categorie',
                            'devise'
                        ]))

                            <a
                                href="{{ route('depenses.index') }}"
                                class="px-5 py-2.5
                                       bg-gray-200 text-gray-700
                                       rounded-lg
                                       hover:bg-gray-300 transition">

                                Réinitialiser

                            </a>

                        @endif

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- LISTE DES DÉPENSES --}}
        {{-- ========================================================= --}}

        <div class="bg-white shadow-sm rounded-xl overflow-hidden">


            {{-- EN-TÊTE --}}

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Liste des dépenses
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">

                            {{ $depenses->total() }}

                            dépense(s)

                        </p>

                    </div>

                </div>

            </div>


            {{-- TABLEAU --}}

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-600 uppercase">

                                Date

                            </th>


                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-600 uppercase">

                                Catégorie

                            </th>


                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-600 uppercase">

                                Année scolaire

                            </th>


                            <th class="px-6 py-3 text-right text-xs
                                       font-semibold text-gray-600 uppercase">

                                Montant

                            </th>


                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-600 uppercase">

                                Description

                            </th>


                            <th class="px-6 py-3 text-right text-xs
                                       font-semibold text-gray-600 uppercase">

                                Actions

                            </th>

                        </tr>

                    </thead>


                    <tbody class="bg-white divide-y divide-gray-200">


                        @forelse($depenses as $depense)

                            <tr class="hover:bg-gray-50">


                                {{-- DATE --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="text-sm font-medium text-gray-900">

                                        {{ $depense->date_depense
                                            ? $depense->date_depense->format('d/m/Y')
                                            : '-' }}

                                    </div>

                                </td>


                                {{-- CATÉGORIE --}}

                                <td class="px-6 py-4">

                                    <div class="text-sm font-semibold text-gray-900">

                                        {{ $depense->categorie }}

                                    </div>

                                </td>


                                {{-- ANNÉE SCOLAIRE --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="text-sm text-gray-700">

                                        @if($depense->anneeScolaire)

                                            {{ $depense->anneeScolaire->libelle
                                                ?? $depense->anneeScolaire->annee
                                                ?? $depense->anneeScolaire->nom
                                                ?? '-' }}

                                        @else

                                            -

                                        @endif

                                    </div>

                                </td>


                                {{-- MONTANT --}}

                                <td class="px-6 py-4 whitespace-nowrap text-right">

                                    <div class="text-sm font-bold text-gray-900">

                                        {{ number_format(
                                            (float) $depense->montant,
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                        <span class="text-xs text-gray-500">

                                            {{ $depense->devise }}

                                        </span>

                                    </div>

                                </td>


                                {{-- DESCRIPTION --}}

                                <td class="px-6 py-4">

                                    <div class="text-sm text-gray-600">

                                        @if($depense->description)

                                            {{ \Illuminate\Support\Str::limit(
                                                $depense->description,
                                                70
                                            ) }}

                                        @else

                                            -

                                        @endif

                                    </div>

                                </td>


                                {{-- ACTIONS --}}

                                <td class="px-6 py-4 whitespace-nowrap text-right">

                                    <div class="flex justify-end gap-2">


                                        @can('view', $depense)

                                            <a
                                                href="{{ route(
                                                    'depenses.show',
                                                    $depense
                                                ) }}"
                                                class="px-3 py-1.5
                                                       bg-gray-100 text-gray-700
                                                       rounded-lg
                                                       hover:bg-gray-200
                                                       text-sm">

                                                Voir

                                            </a>

                                        @endcan


                                        @can('view', $depense)

                                            <a
                                                href="{{ route(
                                                    'depenses.bon',
                                                    $depense
                                                ) }}"
                                                target="_blank"
                                                class="px-3 py-1.5
                                                       bg-gray-100 text-gray-700
                                                       rounded-lg
                                                       hover:bg-gray-200
                                                       text-sm">

                                                🖨️ Bon

                                            </a>

                                        @endcan


                                        @can('update', $depense)

                                            <a
                                                href="{{ route(
                                                    'depenses.edit',
                                                    $depense
                                                ) }}"
                                                class="px-3 py-1.5
                                                       bg-gray-600 text-white
                                                       rounded-lg
                                                       hover:bg-gray-700
                                                       text-sm">

                                                Modifier

                                            </a>

                                        @endcan


                                        @can('delete', $depense)

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'depenses.destroy',
                                                    $depense
                                                ) }}"
                                                onsubmit="return confirm(
                                                    'Voulez-vous vraiment supprimer cette dépense ?'
                                                );">

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5
                                                           bg-gray-200 text-gray-700
                                                           rounded-lg
                                                           hover:bg-gray-300
                                                           text-sm">

                                                    Supprimer

                                                </button>

                                            </form>

                                        @endcan

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-6 py-12 text-center">

                                    <div class="text-gray-400 text-4xl mb-3">
                                        💸
                                    </div>

                                    <p class="text-gray-600 font-medium">

                                        Aucune dépense trouvée.

                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">

                                        Commencez par enregistrer une nouvelle dépense.

                                    </p>


                                    @can('create', App\Models\Depense::class)

                                        <a
                                            href="{{ route('depenses.create') }}"
                                            class="inline-block mt-5
                                                   px-5 py-2.5
                                                   bg-gray-600 text-white
                                                   rounded-lg
                                                   hover:bg-gray-700">

                                            Ajouter une dépense

                                        </a>

                                    @endcan

                                </td>

                            </tr>

                        @endforelse


                    </tbody>

                </table>

            </div>


            {{-- ===================================================== --}}
            {{-- PAGINATION --}}
            {{-- ===================================================== --}}

            @if($depenses->hasPages())

                <div class="px-6 py-4 border-t border-gray-200">

                    {{ $depenses->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

</x-app-layout>
<x-app-layout>

{{-- ========================================================= --}}
{{-- EN-TÊTE ÉCRAN --}}
{{-- ========================================================= --}}

<x-slot name="header">

    <div class="flex items-center justify-between print-hidden">

        <div>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Situation de caisse
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Consultation des recettes, dépenses et soldes de l'établissement
            </p>

        </div>

        <div class="flex items-center gap-3">

            <a href="{{ route('recettes.index') }}"
               class="inline-flex items-center px-4 py-2.5
                      bg-gray-100 text-gray-700 rounded-lg
                      hover:bg-gray-200 transition">

                ← Recettes

            </a>

            <a href="{{ route('depenses.index') }}"
               class="inline-flex items-center px-4 py-2.5
                      bg-gray-100 text-gray-700 rounded-lg
                      hover:bg-gray-200 transition">

                Dépenses

            </a>

        </div>

    </div>

</x-slot>


{{-- ========================================================= --}}
{{-- CONTENU --}}
{{-- ========================================================= --}}

<div class="py-6 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- TITRE ÉCRAN --}}
        {{-- ========================================================= --}}

        <div class="mb-6 print-hidden">

            <h1 class="text-2xl font-bold text-gray-800">
                Situation de caisse / Mouvements
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Consultez les mouvements financiers et la situation de caisse
                pour la période sélectionnée.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTRES --}}
        {{-- ========================================================= --}}

        <div class="bg-white shadow-sm rounded-xl mb-6 print-hidden">

            <div class="p-6">

                <div class="mb-5">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Filtrer les mouvements
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Les totaux et le solde sont calculés selon les filtres sélectionnés.
                    </p>

                </div>


                <form method="GET"
                      action="{{ route('caisse.index') }}">

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


                        {{-- TYPE DE MOUVEMENT --}}

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mouvement
                            </label>

                            <select
                                name="type_mouvement"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-gray-500
                                       focus:ring-gray-500">

                                <option
                                    value="tous"
                                    @selected(
                                        ($typeMouvement ?? 'tous') === 'tous'
                                    )>
                                    Tous les mouvements
                                </option>

                                <option
                                    value="recettes"
                                    @selected(
                                        ($typeMouvement ?? '') === 'recettes'
                                    )>
                                    Recettes uniquement
                                </option>

                                <option
                                    value="depenses"
                                    @selected(
                                        ($typeMouvement ?? '') === 'depenses'
                                    )>
                                    Dépenses uniquement
                                </option>

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

                                <option
                                    value="USD"
                                    @selected(request('devise') === 'USD')>
                                    USD
                                </option>

                                <option
                                    value="CDF"
                                    @selected(request('devise') === 'CDF')>
                                    CDF
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- BOUTONS --}}

                    <div class="mt-5 flex flex-wrap gap-3">

                        <button
                            type="submit"
                            class="inline-flex items-center px-5 py-2.5
                                   bg-gray-600 text-white rounded-lg
                                   hover:bg-gray-700 transition">

                            🔎 Rechercher

                        </button>


                        @if(request()->hasAny([
                            'id_annee_scolaire',
                            'type_mouvement',
                            'date_debut',
                            'date_fin',
                            'devise'
                        ]))

                            <a
                                href="{{ route('caisse.index') }}"
                                class="inline-flex items-center px-5 py-2.5
                                       bg-gray-200 text-gray-700 rounded-lg
                                       hover:bg-gray-300 transition">

                                Réinitialiser

                            </a>

                        @endif

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- EN-TÊTE POUR IMPRESSION --}}
        {{-- ========================================================= --}}

        <div class="hidden print:block mb-6">

            <div class="text-center">

                <h1 class="text-2xl font-bold">
                    SITUATION DE CAISSE
                </h1>

                <p class="text-sm mt-2">
                    Situation des recettes et dépenses
                </p>

            </div>


            <div class="grid grid-cols-2 gap-x-10 gap-y-3 mt-5 text-sm">

                {{-- ANNÉE --}}

                <div>

                    <strong>Année scolaire :</strong>

                    @if(request('id_annee_scolaire'))

                        @php

                            $anneeSelectionnee = $anneesScolaires
                                ->firstWhere(
                                    'id_annee_scolaire',
                                    request('id_annee_scolaire')
                                );

                        @endphp

                        @if($anneeSelectionnee)

                            {{ $anneeSelectionnee->libelle
                                ?? $anneeSelectionnee->annee
                                ?? $anneeSelectionnee->nom
                                ?? request('id_annee_scolaire') }}

                        @else

                            {{ request('id_annee_scolaire') }}

                        @endif

                    @else

                        Toutes les années

                    @endif

                </div>


                {{-- MOUVEMENT --}}

                <div>

                    <strong>Mouvement :</strong>

                    @if(($typeMouvement ?? 'tous') === 'recettes')

                        Recettes uniquement

                    @elseif(($typeMouvement ?? 'tous') === 'depenses')

                        Dépenses uniquement

                    @else

                        Tous les mouvements

                    @endif

                </div>


                {{-- DATE DÉBUT --}}

                <div>

                    <strong>Date début :</strong>

                    @if(request('date_debut'))

                        {{ \Carbon\Carbon::parse(
                            request('date_debut')
                        )->format('d/m/Y') }}

                    @else

                        Toutes les dates

                    @endif

                </div>


                {{-- DATE FIN --}}

                <div>

                    <strong>Date fin :</strong>

                    @if(request('date_fin'))

                        {{ \Carbon\Carbon::parse(
                            request('date_fin')
                        )->format('d/m/Y') }}

                    @else

                        Toutes les dates

                    @endif

                </div>


                {{-- DEVISE --}}

                <div>

                    <strong>Devise :</strong>

                    {{ request('devise') ?: 'Toutes les devises' }}

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- RÉSUMÉ FINANCIER --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6 print-hidden">


            {{-- RECETTES USD --}}

            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-600">

                <p class="text-sm font-medium text-gray-500">
                    Total recettes USD
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">

                    {{ number_format(
                        (float) $totalRecettesUSD,
                        2,
                        ',',
                        ' '
                    ) }}

                    <span class="text-sm font-medium text-gray-500">
                        USD
                    </span>

                </p>

            </div>


            {{-- DÉPENSES USD --}}

            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-400">

                <p class="text-sm font-medium text-gray-500">
                    Total dépenses USD
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">

                    {{ number_format(
                        (float) $totalDepensesUSD,
                        2,
                        ',',
                        ' '
                    ) }}

                    <span class="text-sm font-medium text-gray-500">
                        USD
                    </span>

                </p>

            </div>


            {{-- SOLDE USD --}}

            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-700">

                <p class="text-sm font-medium text-gray-500">
                    Solde USD
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">

                    {{ number_format(
                        (float) $soldeUSD,
                        2,
                        ',',
                        ' '
                    ) }}

                    <span class="text-sm font-medium text-gray-500">
                        USD
                    </span>

                </p>

            </div>


            {{-- NOMBRE MOUVEMENTS --}}

            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-300">

                <p class="text-sm font-medium text-gray-500">
                    Nombre de mouvements
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">

                    {{ $nombreMouvements }}

                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- RÉSUMÉ CDF --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6 print-hidden">

            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-500">

                <p class="text-sm font-medium text-gray-500">
                    Total recettes CDF
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">

                    {{ number_format(
                        (float) $totalRecettesCDF,
                        2,
                        ',',
                        ' '
                    ) }}

                    <span class="text-sm font-medium text-gray-500">
                        CDF
                    </span>

                </p>

            </div>


            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-400">

                <p class="text-sm font-medium text-gray-500">
                    Total dépenses CDF
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">

                    {{ number_format(
                        (float) $totalDepensesCDF,
                        2,
                        ',',
                        ' '
                    ) }}

                    <span class="text-sm font-medium text-gray-500">
                        CDF
                    </span>

                </p>

            </div>


            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-700">

                <p class="text-sm font-medium text-gray-500">
                    Solde CDF
                </p>

                <p class="text-2xl font-bold text-gray-800 mt-2">

                    {{ number_format(
                        (float) $soldeCDF,
                        2,
                        ',',
                        ' '
                    ) }}

                    <span class="text-sm font-medium text-gray-500">
                        CDF
                    </span>

                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- LISTE DES MOUVEMENTS --}}
        {{-- ========================================================= --}}

        <div class="bg-white shadow-sm rounded-xl overflow-hidden">


            {{-- EN-TÊTE DE LA LISTE --}}

            <div class="px-6 py-4 border-b border-gray-200">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Liste des mouvements
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">

                            {{ $nombreMouvements }}
                            mouvement(s)

                        </p>

                    </div>


                    {{-- BOUTON IMPRIMER --}}

                    <button
                        type="button"
                        onclick="window.print()"
                        class="print-hidden inline-flex items-center px-4 py-2
                               bg-gray-600 text-white rounded-lg
                               hover:bg-gray-700 transition">

                        🖨️ Imprimer la situation

                    </button>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- TABLEAU --}}
            {{-- ========================================================= --}}

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
                                Type
                            </th>

                            <th class="px-6 py-3 text-left text-xs
                                       font-semibold text-gray-600 uppercase">
                                Libellé
                            </th>

                            <th class="px-6 py-3 text-right text-xs
                                       font-semibold text-gray-600 uppercase">
                                Entrée
                            </th>

                            <th class="px-6 py-3 text-right text-xs
                                       font-semibold text-gray-600 uppercase">
                                Sortie
                            </th>

                            <th class="px-6 py-3 text-right text-xs
                                       font-semibold text-gray-600 uppercase">
                                Devise
                            </th>

                        </tr>

                    </thead>


                    <tbody class="bg-white divide-y divide-gray-200">

                        @forelse($mouvements as $mouvement)

                            <tr>

                                {{-- DATE --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    {{ $mouvement['date']
                                        ? \Carbon\Carbon::parse(
                                            $mouvement['date']
                                        )->format('d/m/Y')
                                        : '-' }}

                                </td>


                                {{-- TYPE --}}

                                <td class="px-6 py-4 whitespace-nowrap">

                                    {{ $mouvement['type'] }}

                                </td>


                                {{-- LIBELLÉ --}}

                                <td class="px-6 py-4">

                                    <div class="font-semibold">

                                        {{ $mouvement['libelle'] ?? '-' }}

                                    </div>

                                    @if(!empty($mouvement['description']))

                                        <div class="text-xs text-gray-500 mt-1">

                                            {{ \Illuminate\Support\Str::limit(
                                                $mouvement['description'],
                                                70
                                            ) }}

                                        </div>

                                    @endif

                                </td>


                                {{-- ENTRÉE --}}

                                <td class="px-6 py-4 text-right">

                                    @if($mouvement['type'] === 'RECETTE')

                                        {{ number_format(
                                            (float) $mouvement['montant'],
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- SORTIE --}}

                                <td class="px-6 py-4 text-right">

                                    @if($mouvement['type'] === 'DÉPENSE')

                                        {{ number_format(
                                            (float) $mouvement['montant'],
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- DEVISE --}}

                                <td class="px-6 py-4 text-right">

                                    {{ $mouvement['devise'] }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="px-6 py-12 text-center">

                                    Aucun mouvement trouvé.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    {{-- ================================================= --}}
                    {{-- TOTAUX --}}
                    {{-- ================================================= --}}

                    @if($mouvements->count() > 0)

                        <tfoot>

                            {{-- TOTAL USD --}}

                            <tr class="font-bold">

                                <td colspan="3"
                                    class="px-6 py-3 text-right">

                                    TOTAL USD

                                </td>

                                <td class="px-6 py-3 text-right">

                                    {{ number_format(
                                        $mouvements
                                            ->where('type', 'RECETTE')
                                            ->where('devise', 'USD')
                                            ->sum('montant'),
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                </td>

                                <td class="px-6 py-3 text-right">

                                    {{ number_format(
                                        $mouvements
                                            ->where('type', 'DÉPENSE')
                                            ->where('devise', 'USD')
                                            ->sum('montant'),
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                </td>

                                <td class="px-6 py-3 text-right">
                                    USD
                                </td>

                            </tr>


                            {{-- TOTAL CDF --}}

                            <tr class="font-bold">

                                <td colspan="3"
                                    class="px-6 py-3 text-right">

                                    TOTAL CDF

                                </td>

                                <td class="px-6 py-3 text-right">

                                    {{ number_format(
                                        $mouvements
                                            ->where('type', 'RECETTE')
                                            ->where('devise', 'CDF')
                                            ->sum('montant'),
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                </td>

                                <td class="px-6 py-3 text-right">

                                    {{ number_format(
                                        $mouvements
                                            ->where('type', 'DÉPENSE')
                                            ->where('devise', 'CDF')
                                            ->sum('montant'),
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                </td>

                                <td class="px-6 py-3 text-right">
                                    CDF
                                </td>

                            </tr>


                            {{-- SOLDE USD --}}

                            <tr class="font-bold">

                                <td colspan="3"
                                    class="px-6 py-3 text-right">

                                    SOLDE USD

                                </td>

                                <td colspan="2"
                                    class="px-6 py-3 text-right">

                                    {{ number_format(
                                        $totalRecettesUSD - $totalDepensesUSD,
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                </td>

                                <td class="px-6 py-3 text-right">
                                    USD
                                </td>

                            </tr>


                            {{-- SOLDE CDF --}}

                            <tr class="font-bold">

                                <td colspan="3"
                                    class="px-6 py-3 text-right">

                                    SOLDE CDF

                                </td>

                                <td colspan="2"
                                    class="px-6 py-3 text-right">

                                    {{ number_format(
                                        $totalRecettesCDF - $totalDepensesCDF,
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                </td>

                                <td class="px-6 py-3 text-right">
                                    CDF
                                </td>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SIGNATURES --}}
        {{-- ========================================================= --}}

        <div class="hidden print:block mt-12">

            <div class="grid grid-cols-2 gap-16 text-center">

                <div>

                    <p class="font-semibold">
                        Préparé par
                    </p>

                    <div class="mt-14 border-t border-gray-400 pt-2">
                        Signature
                    </div>

                </div>


                <div>

                    <p class="font-semibold">
                        Responsable
                    </p>

                    <div class="mt-14 border-t border-gray-400 pt-2">
                        Signature
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- IMPRESSION --}}
{{-- ========================================================= --}}

<style>

    @media print {

        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        /*
         * Masquer tout ce qui appartient à l'interface
         * du tableau de bord.
         */

        .print-hidden,
        nav,
        header,
        aside,
        button,
        a {
            display: none !important;
        }


        /*
         * Afficher uniquement le contenu
         * destiné à l'impression.
         */

        .print\:block {
            display: block !important;
        }


        body {
            background: white !important;
            color: black !important;
        }


        /*
         * Supprimer les effets d'écran.
         */

        .shadow,
        .shadow-sm,
        .shadow-md,
        .shadow-lg {
            box-shadow: none !important;
        }


        /*
         * Fond blanc pour l'impression.
         */

        .bg-gray-100,
        .bg-gray-50,
        .bg-white {
            background: white !important;
        }


        /*
         * Tableau.
         */

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 11px !important;
        }


        th,
        td {
            border: 1px solid #999 !important;
            padding: 6px !important;
        }


        thead {
            display: table-header-group;
        }


        tr {
            page-break-inside: avoid;
        }


        /*
         * Éviter les coupures inutiles.
         */

        .overflow-x-auto {
            overflow: visible !important;
        }


        /*
         * Le contenu principal occupe toute la largeur.
         */

        .max-w-7xl {
            max-width: 100% !important;
        }


        /*
         * Les cartes de résumé ne sont pas imprimées.
         */

        .print-hidden {
            display: none !important;
        }

    }

</style>

</x-app-layout>

<x-app-layout>

    {{-- ==========================================================
         EN-TÊTE DE L'APPLICATION
         Ne sera pas imprimé
    =========================================================== --}}

    <x-slot name="header">

        <div class="flex items-center justify-between print:hidden">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Bulletin scolaire
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $eleve->nom }}
                    {{ $eleve->postnom }}
                    {{ $eleve->prenom }}
                </p>
            </div>

            <div class="flex gap-2">

                <button
                    onclick="window.print()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">

                    🖨️ Imprimer

                </button>

                <a
                    href="{{ route('bulletins.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

                    Retour

                </a>

            </div>

        </div>

    </x-slot>


    {{-- ==========================================================
         ZONE IMPRIMABLE
         TOUT LE BULLETIN DOIT ÊTRE À L'INTÉRIEUR
    =========================================================== --}}

    <div id="bulletin-print">

        <div class="py-6 bg-gray-100">

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

                <div
                    id="bulletin"
                    class="bg-white shadow-lg rounded-xl overflow-hidden
                           print:shadow-none print:rounded-none">


                    {{-- =================================================
                         EN-TÊTE DE L'ÉTABLISSEMENT
                    ================================================== --}}

                    <div class="p-6 border-b-4 border-gray-800">

                        <div class="grid grid-cols-12 gap-4 items-center">

                            {{-- LOGO --}}

                            <div class="col-span-2 flex justify-center">

                                @if($etablissement && $etablissement->logo)

                                    <img
                                        src="{{ asset('storage/' . $etablissement->logo) }}"
                                        alt="Logo {{ $etablissement->nom }}"
                                        class="w-24 h-24 object-contain"
                                    >

                                @else

                                    <div
                                        class="w-24 h-24 border-2 border-gray-300
                                               rounded-lg flex items-center
                                               justify-center text-gray-400
                                               text-xs text-center">

                                        LOGO

                                    </div>

                                @endif

                            </div>


                            {{-- INFORMATIONS ÉTABLISSEMENT --}}

                            <div class="col-span-8 text-center">

                                <h1 class="text-2xl font-bold uppercase text-gray-900">

                                    {{ $etablissement->nom ?? 'ÉTABLISSEMENT SCOLAIRE' }}

                                </h1>

                                @if($etablissement && $etablissement->type)

                                    <p class="text-sm font-semibold text-gray-700 mt-1">

                                        {{ $etablissement->type }}

                                    </p>

                                @endif

                                <p class="text-sm text-gray-600 mt-2">

                                    @if($etablissement && $etablissement->adresse)
                                        {{ $etablissement->adresse }}
                                    @endif

                                    @if($etablissement && $etablissement->ville)

                                        @if($etablissement->adresse)
                                            —
                                        @endif

                                        {{ $etablissement->ville }}

                                    @endif

                                    @if($etablissement && $etablissement->commune)

                                        — {{ $etablissement->commune }}

                                    @endif

                                </p>

                                <p class="text-sm text-gray-600">

                                    @if($etablissement && $etablissement->telephone)

                                        Tél. : {{ $etablissement->telephone }}

                                    @endif

                                    @if(
                                        $etablissement &&
                                        $etablissement->telephone &&
                                        $etablissement->email
                                    )

                                        &nbsp; | &nbsp;

                                    @endif

                                    @if($etablissement && $etablissement->email)

                                        {{ $etablissement->email }}

                                    @endif

                                </p>

                            </div>


                            {{-- ANNÉE SCOLAIRE --}}

                            <div class="col-span-2 text-right">

                                <p class="text-xs uppercase font-semibold text-gray-500">
                                    Année scolaire
                                </p>

                                <p class="font-bold text-lg text-gray-900 mt-1">

                                    {{ $anneeScolaire->libelle }}

                                </p>

                            </div>

                        </div>


                        {{-- TITRE --}}

                        <div class="text-center mt-1">

                            <h2
                                class="inline-block border-2 border-gray-800
                                       px-8 py-2 rounded-lg
                                       text-xl font-bold uppercase">

                                Bulletin scolaire

                            </h2>

                            <p class="mt-2 font-semibold uppercase text-gray-700">

                                {{ $periode->libelle }}

                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                         INFORMATIONS ÉLÈVE
                    ================================================== --}}

                    <div class="p-1">

                        <div class="border border-gray-400 rounded-lg overflow-hidden">

                            <div class="bg-gray-800 text-white px-4 py-2">

                                <h3 class="font-semibold uppercase text-sm">

                                    Identification de l'élève

                                </h3>

                            </div>

                            <div class="grid grid-cols-12">

                                <div class="col-span-3 p-2 border-b border-gray-300">

                                    <p class="text-xs uppercase text-gray-500 font-semibold">
                                        Élève
                                    </p>

                                    <p class="font-bold text-gray-900 text-lg mt-1">

                                        {{ $eleve->nom }}
                                        {{ $eleve->postnom }}
                                        {{ $eleve->prenom }}

                                    </p>

                                </div>

                                <div class="col-span-3 p-2 border-b border-gray-300">

                                    <p class="text-xs uppercase text-gray-500 font-semibold">
                                        Matricule
                                    </p>

                                    <p class="font-bold text-gray-900 mt-1">

                                        {{ $eleve->matricule }}

                                    </p>

                                </div>

                                <div class="col-span-3 p-2 border-r border-gray-300">

                                    <p class="text-xs uppercase text-gray-500 font-semibold">
                                        Classe
                                    </p>

                                    <p class="font-bold text-gray-900 mt-1">

                                        {{ $classe->libelle ?? '—' }}

                                    </p>

                                </div>

                                <div class="col-span-3 p-2">

                                    <p class="text-xs uppercase text-gray-500 font-semibold">
                                        Période
                                    </p>

                                    <p class="font-bold text-gray-900 mt-1">

                                        {{ $periode->libelle }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             RÉSULTATS
                        ================================================== --}}

                        <div class="mt-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-3">
                                Résultats scolaires
                            </h3>

                            @if($resultats->count() > 0)

                                <div class="overflow-x-auto">

                                    <table class="w-full border-collapse border border-gray-400 text-sm">

                                        <thead>

                                            <tr class="bg-gray-800 text-white">

                                                <th class="border border-gray-400 px-3 py-3">
                                                    N°
                                                </th>

                                                <th class="border border-gray-400 px-3 py-3 text-left">
                                                    Matière
                                                </th>

                                                <th class="border border-gray-400 px-3 py-3">
                                                    Coef.
                                                </th>

                                                <th class="border border-gray-400 px-3 py-3">
                                                    Notes
                                                </th>

                                                <th class="border border-gray-400 px-3 py-3">
                                                    Moyenne /20
                                                </th>

                                                <th class="border border-gray-400 px-3 py-3">
                                                    Points
                                                </th>

                                                <th class="border border-gray-400 px-3 py-3">
                                                    Appréciation
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($resultats as $index => $resultat)

                                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">

                                                    <td class="border border-gray-400 px-3 py-3 text-center">

                                                        {{ $index + 1 }}

                                                    </td>

                                                    <td class="border border-gray-400 px-3 py-3">

                                                        <div class="font-bold text-gray-900">

                                                            {{ $resultat['matiere']->libelle ?? '—' }}

                                                        </div>

                                                        @if($resultat['matiere']->code ?? null)

                                                            <div class="text-xs text-gray-500">

                                                                {{ $resultat['matiere']->code }}

                                                            </div>

                                                        @endif

                                                    </td>

                                                    <td class="border border-gray-400 px-3 py-3 text-center font-semibold">

                                                        {{ number_format(
                                                            $resultat['coefficient'] ?? 1,
                                                            2,
                                                            ',',
                                                            ' '
                                                        ) }}

                                                    </td>

                                                    <td class="border border-gray-400 px-2 py-2">

                                                        @if(
                                                            isset($resultat['evaluations']) &&
                                                            $resultat['evaluations']->count()
                                                        )

                                                            <div class="space-y-1">

                                                                @foreach($resultat['evaluations'] as $evaluation)

                                                                    <div class="flex justify-between gap-2 text-xs">

                                                                        <span>
                                                                            {{ $evaluation['evaluation']->libelle ?? 'Évaluation' }}
                                                                        </span>

                                                                        <strong>

                                                                            {{ number_format(
                                                                                $evaluation['note'],
                                                                                2,
                                                                                ',',
                                                                                ' '
                                                                            ) }}

                                                                            /

                                                                            {{ number_format(
                                                                                $evaluation['evaluation']->note_maximale,
                                                                                2,
                                                                                ',',
                                                                                ' '
                                                                            ) }}

                                                                        </strong>

                                                                    </div>

                                                                @endforeach

                                                            </div>

                                                        @else

                                                            <span class="text-gray-400">
                                                                —
                                                            </span>

                                                        @endif

                                                    </td>

                                                    <td class="border border-gray-400 px-3 py-3 text-center">

                                                        @if($resultat['moyenne'] !== null)

                                                            <strong>

                                                                {{ number_format(
                                                                    $resultat['moyenne'],
                                                                    2,
                                                                    ',',
                                                                    ' '
                                                                ) }}

                                                            </strong>

                                                            /20

                                                        @else

                                                            —

                                                        @endif

                                                    </td>

                                                    <td class="border border-gray-400 px-3 py-3 text-center font-bold">

                                                        @if($resultat['moyenne'] !== null)

                                                            {{ number_format(
                                                                $resultat['points'],
                                                                2,
                                                                ',',
                                                                ' '
                                                            ) }}

                                                        @else

                                                            —

                                                        @endif

                                                    </td>

                                                    <td class="border border-gray-400 px-3 py-3 text-center">

                                                        @php

                                                            $appreciation = null;

                                                            if (
                                                                isset($resultat['evaluations']) &&
                                                                $resultat['evaluations']->count()
                                                            ) {

                                                                $appreciation = $resultat['evaluations']
                                                                    ->pluck('appreciation')
                                                                    ->filter()
                                                                    ->first();

                                                            }

                                                        @endphp

                                                        {{ $appreciation ?? '—' }}

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                        <tfoot>

                                            <tr class="bg-gray-100">

                                                <td
                                                    colspan="5"
                                                    class="border border-gray-400 px-4 py-3 text-right font-bold">

                                                    Total des points

                                                </td>

                                                <td class="border border-gray-400 px-3 py-3 text-center font-bold">

                                                    {{ number_format(
                                                        $totalPoints,
                                                        2,
                                                        ',',
                                                        ' '
                                                    ) }}

                                                </td>

                                                <td class="border border-gray-400"></td>

                                            </tr>

                                            <tr class="bg-gray-200">

                                                <td
                                                    colspan="5"
                                                    class="border border-gray-400 px-4 py-4 text-right font-bold text-lg">

                                                    Moyenne générale

                                                </td>

                                                <td
                                                    colspan="2"
                                                    class="border border-gray-400 px-3 py-4 text-center">

                                                    <span class="text-xl font-bold">

                                                        @if($moyenneGenerale !== null)

                                                            {{ number_format(
                                                                $moyenneGenerale,
                                                                2,
                                                                ',',
                                                                ' '
                                                            ) }}

                                                            /20

                                                        @else

                                                            —

                                                        @endif

                                                    </span>

                                                </td>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                            @else

                                <div class="border border-gray-300 rounded-lg p-10 text-center">

                                    <div class="text-5xl mb-4">
                                        📋
                                    </div>

                                    <p class="font-semibold text-gray-700">
                                        Aucune note disponible.
                                    </p>

                                </div>

                            @endif

                        </div>


                    {{-- =====================================================
                            APPRÉCIATION
                        ====================================================== --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">

                            <div class="border border-gray-400 rounded-lg p-4">

                                <h3 class="font-bold uppercase text-sm text-gray-800 mb-3">
                                    Appréciation générale
                                </h3>

                                <div class="h-3 border-b border-gray-300"></div>

                            </div>


                            <div class="border border-gray-400 rounded-lg p-4">

                                <h3 class="font-bold uppercase text-sm text-gray-800 mb-3">
                                    Décision du conseil
                                </h3>

                                <div class="h-3 border-b border-gray-300"></div>

                            </div>

                        </div>


                        {{-- =================================================
                             SIGNATURES
                        ================================================== --}}

                        <div class="w-full mt-6">

                            <div class="flex w-full justify-between items-start">

                                <div class="pl-8 text-left">

                                    <p class="font-bold text-gray-800">
                                        Le titulaire
                                    </p>

                                    <div class="h-3"></div>

                                    <p class="text-gray-400 text-sm">
                                        Signature
                                    </p>

                                </div>

                                <div class="pr-8 text-right">

                                    <p class="font-bold text-gray-800">
                                        La direction
                                    </p>

                                    <div class="h-3"></div>

                                    <p class="text-gray-600 text-sm">

                                        {{ $etablissement->directeur ?? 'Signature et cachet' }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- PARENT / TUTEUR --}}

                        <div class="mt-8 text-left pl-8">

                            <p class="font-bold text-gray-800">
                                Le parent / tuteur
                            </p>

                            <div class="h-5"></div>

                            <div class="text-gray-400 text-sm">
                                Signature
                            </div>

                        </div>


                        {{-- PIED DE PAGE --}}

                        <div class="mt-8 pt-4 border-t border-gray-300
                                    text-center text-xs text-gray-500">

                            {{ $etablissement->nom ?? 'Établissement scolaire' }}

                            —

                            Année scolaire {{ $anneeScolaire->libelle }}

                            —

                            {{ $periode->libelle }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
         STYLE D'IMPRESSION
    =========================================================== --}}

    <style>

        @media print {

            @page {
                size: A4;
                margin: 10mm;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            /*
             * On cache absolument tout ce qui appartient
             * au layout de l'application.
             */
            body * {
                visibility: hidden !important;
            }

            /*
             * On affiche uniquement le bulletin.
             */
            #bulletin-print,
            #bulletin-print * {
                visibility: visible !important;
            }

            /*
             * Le bulletin commence tout en haut
             * de la feuille.
             */
            #bulletin-print {
                position: absolute !important;

                top: 0 !important;
                left: 0 !important;

                width: 100% !important;
                max-width: none !important;

                margin: 0 !important;
                padding: 0 !important;

                background: white !important;
            }

            /*
             * Suppression des effets écran.
             */
            #bulletin,
            #bulletin * {
                box-shadow: none !important;
            }

            /*
             * Le fond gris de l'écran devient blanc
             * à l'impression.
             */
            #bulletin-print .bg-gray-100 {
                background: white !important;
            }

            /*
             * Éviter autant que possible les coupures
             * dans les tableaux.
             */
            table {
                page-break-inside: avoid !important;
            }

            tr {
                page-break-inside: avoid !important;
            }

            /*
             * Les boutons / éléments print:hidden
             * restent masqués.
             */
            .print\:hidden {
                display: none !important;
            }

        }

    </style>

</x-app-layout>
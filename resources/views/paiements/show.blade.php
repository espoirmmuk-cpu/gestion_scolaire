@php
    $etablissement = $paiement->utilisateur?->etablissement;
    $eleve = $paiement->eleve;
    $utilisateur = $paiement->utilisateur;

    $fraisEleve = $paiement->details->first()?->fraisEleve;
    $tarif = $fraisEleve?->tarif;

    $classe = $tarif?->classe;
    $anneeScolaire = $tarif?->anneeScolaire;

    $nomEleve = trim(
        ($eleve?->nom ?? '') . ' ' .
        ($eleve?->postnom ?? '') . ' ' .
        ($eleve?->prenom ?? '')
    );

    $modePaiement = match ($paiement->mode_paiement) {
        'ESPECES' => 'Espèces',
        'BANQUE' => 'Banque',
        'MOBILE_MONEY' => 'Mobile Money',
        'CHEQUE' => 'Chèque',
        default => 'Autre',
    };

    function nombreEnLettresPaiement($nombre)
    {
        $nombre = (int) $nombre;

        $unites = [
            0 => 'zéro',
            1 => 'un',
            2 => 'deux',
            3 => 'trois',
            4 => 'quatre',
            5 => 'cinq',
            6 => 'six',
            7 => 'sept',
            8 => 'huit',
            9 => 'neuf',
            10 => 'dix',
            11 => 'onze',
            12 => 'douze',
            13 => 'treize',
            14 => 'quatorze',
            15 => 'quinze',
            16 => 'seize',
            17 => 'dix-sept',
            18 => 'dix-huit',
            19 => 'dix-neuf',
            20 => 'vingt',
            30 => 'trente',
            40 => 'quarante',
            50 => 'cinquante',
            60 => 'soixante',
            70 => 'soixante-dix',
            80 => 'quatre-vingts',
            90 => 'quatre-vingt-dix',
        ];

        if ($nombre < 21) {
            return $unites[$nombre];
        }

        if ($nombre < 100) {
            $dizaine = intdiv($nombre, 10) * 10;
            $reste = $nombre % 10;

            if ($reste === 0) {
                return $unites[$dizaine];
            }

            if ($dizaine === 70) {
                return 'soixante-' . $unites[$reste + 10];
            }

            if ($dizaine === 90) {
                return 'quatre-vingt-' . $unites[$reste + 10];
            }

            return $unites[$dizaine] .
                ($reste === 1 && $dizaine < 70
                    ? '-et-un'
                    : '-' . $unites[$reste]);
        }

        if ($nombre < 1000) {
            $centaines = intdiv($nombre, 100);
            $reste = $nombre % 100;

            $prefixe = $centaines === 1
                ? 'cent'
                : $unites[$centaines] . ' cent';

            if ($reste === 0) {
                return $prefixe;
            }

            return $prefixe . ' ' . nombreEnLettresPaiement($reste);
        }

        if ($nombre < 1000000) {
            $milliers = intdiv($nombre, 1000);
            $reste = $nombre % 1000;

            $prefixe = $milliers === 1
                ? 'mille'
                : nombreEnLettresPaiement($milliers) . ' mille';

            if ($reste === 0) {
                return $prefixe;
            }

            return $prefixe . ' ' . nombreEnLettresPaiement($reste);
        }

        return number_format($nombre, 0, ',', ' ');
    }

    $montantEntier = (int) $paiement->montant_total;

    $montantDecimal = (int) round(
        ($paiement->montant_total - $montantEntier) * 100
    );

    $montantLettres = nombreEnLettresPaiement($montantEntier);

    if ($montantDecimal > 0) {
        $montantLettres .= ' virgule ' .
            str_pad($montantDecimal, 2, '0', STR_PAD_LEFT);
    }
@endphp


<x-app-layout>

    {{-- ========================================================= --}}
    {{-- EN-TÊTE DE LA PAGE --}}
    {{-- ========================================================= --}}

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reçu de paiement
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Consultation du reçu
                    {{ $paiement->numero_recu }}
                </p>

            </div>

            <div class="flex gap-2">

                <button
                    onclick="window.print()"
                    class="px-4 py-2 bg-gray-800 text-white
                           rounded-lg hover:bg-gray-900 transition">

                    🖨 Imprimer

                </button>

                <a
                    href="{{ route('paiements.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-700
                           rounded-lg hover:bg-gray-300 transition">

                    Retour

                </a>

            </div>

        </div>

    </x-slot>


    {{-- ========================================================= --}}
    {{-- CONTENU --}}
    {{-- ========================================================= --}}

    <div class="py-6">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


            {{-- ================================================= --}}
            {{-- REÇU --}}
            {{-- ================================================= --}}

            <div
                id="recu"
                class="bg-white rounded-xl shadow-sm
                       border border-gray-300 overflow-hidden">


                {{-- ================================================= --}}
                {{-- EN-TÊTE ÉTABLISSEMENT --}}
                {{-- ================================================= --}}

                <div class="px-8 py-6 border-b-2 border-gray-800">

                    <div class="flex items-center">


                        {{-- LOGO --}}

                        <div class="w-24 flex-shrink-0">

                            @if($etablissement?->logo)

                                <img
                                    src="{{ asset('storage/' . $etablissement->logo) }}"
                                    alt="Logo de l'établissement"
                                    class="w-20 h-20 object-contain">

                            @else

                                <div
                                    class="w-20 h-20 border
                                           border-gray-300 rounded-lg
                                           flex items-center justify-center">

                                    <span class="text-xs text-gray-400">
                                        LOGO
                                    </span>

                                </div>

                            @endif

                        </div>


                        {{-- INFORMATIONS ÉTABLISSEMENT --}}

                        <div class="flex-1 text-center">

                            <p class="text-xs uppercase
                                      tracking-widest text-gray-500">

                                {{ $etablissement?->type
                                    ?? 'Établissement scolaire' }}

                            </p>

                            <h1 class="text-2xl font-black uppercase
                                       text-gray-900 mt-1">

                                {{ $etablissement?->nom
                                    ?? 'Établissement scolaire' }}

                            </h1>

                            <p class="text-sm text-gray-600 mt-2">

                                {{ $etablissement?->adresse ?? '' }}

                                @if($etablissement?->ville)
                                    — {{ $etablissement->ville }}
                                @endif

                                @if($etablissement?->commune)
                                    — {{ $etablissement->commune }}
                                @endif

                            </p>

                            <p class="text-xs text-gray-500 mt-2">

                                @if($etablissement?->telephone)

                                    Tél. :
                                    {{ $etablissement->telephone }}

                                @endif

                                @if(
                                    $etablissement?->telephone &&
                                    $etablissement?->email
                                )

                                    &nbsp; | &nbsp;

                                @endif

                                @if($etablissement?->email)

                                    {{ $etablissement->email }}

                                @endif

                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TITRE --}}
                {{-- ================================================= --}}

                <div class="px-8 py-5">

                    <div class="text-center">

                        <div
                            class="inline-block border-2 border-gray-800
                                   px-8 py-2 rounded-lg">

                            <h2 class="text-xl font-black uppercase
                                       tracking-widest">

                                REÇU DE PAIEMENT

                            </h2>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">


                        {{-- NUMÉRO --}}

                        <div
                            class="bg-gray-50 border border-gray-200
                                   rounded-lg p-4">

                            <p class="text-xs uppercase
                                      font-semibold text-gray-500">

                                Numéro du reçu

                            </p>

                            <p class="mt-1 font-bold text-gray-800">

                                {{ $paiement->numero_recu }}

                            </p>

                        </div>


                        {{-- DATE --}}

                        <div
                            class="bg-gray-50 border border-gray-200
                                   rounded-lg p-4">

                            <p class="text-xs uppercase
                                      font-semibold text-gray-500">

                                Date du paiement

                            </p>

                            <p class="mt-1 font-bold text-gray-800">

                                {{ $paiement->date_paiement?->format('d/m/Y') }}

                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- IDENTIFICATION ÉLÈVE --}}
                {{-- ================================================= --}}

                <div class="px-8">

                    <div
                        class="rounded-lg border border-gray-200
                               bg-gray-50 p-5">

                        <div class="flex items-center justify-between mb-4">

                            <div>

                                <h3 class="text-sm font-bold uppercase
                                           tracking-wide text-gray-800">

                                    Identification de l'élève

                                </h3>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">


                            {{-- ÉLÈVE --}}

                            <div>

                                <p class="text-xs uppercase
                                          font-medium text-gray-500">

                                    Nom complet

                                </p>

                                <p class="mt-1 font-bold uppercase
                                          text-gray-800">

                                    {{ $nomEleve ?: '—' }}

                                </p>

                            </div>


                            {{-- MATRICULE --}}

                            <div>

                                <p class="text-xs uppercase
                                          font-medium text-gray-500">

                                    Matricule

                                </p>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $eleve?->matricule ?? '—' }}

                                </p>

                            </div>


                            {{-- CLASSE --}}

                            <div>

                                <p class="text-xs uppercase
                                          font-medium text-gray-500">

                                    Classe

                                </p>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $classe?->libelle ?? '—' }}

                                    @if($classe?->option_classe)

                                        <span class="text-xs text-gray-500">

                                            — {{ $classe->option_classe }}

                                        </span>

                                    @endif

                                </p>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">


                            {{-- ANNÉE --}}

                            <div>

                                <p class="text-xs uppercase
                                          font-medium text-gray-500">

                                    Année scolaire

                                </p>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $anneeScolaire?->libelle ?? '—' }}

                                </p>

                            </div>


                            {{-- MODE --}}

                            <div>

                                <p class="text-xs uppercase
                                          font-medium text-gray-500">

                                    Mode de paiement

                                </p>

                                <p class="mt-1 font-semibold text-gray-800">

                                    {{ $modePaiement }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MONTANT EN LETTRES --}}
                {{-- ================================================= --}}

                <div class="px-8 pt-5">

                    <div
                        class="border border-gray-300 rounded-lg
                               px-5 py-4 text-sm leading-6">

                        Reçu de

                        <strong class="uppercase">
                            {{ $nomEleve ?: 'Élève non renseigné' }}
                        </strong>

                        , matricule

                        <strong>
                            {{ $eleve?->matricule ?? '—' }}
                        </strong>

                        , la somme de

                        <strong class="uppercase">

                            {{ $montantLettres }}

                            {{ $paiement->devise }}

                        </strong>

                        au titre de règlement des frais scolaires.

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DÉTAIL DU PAIEMENT --}}
                {{-- ================================================= --}}

                <div class="px-8 pt-6">

                    <div class="flex items-center justify-between mb-3">

                        <div>

                            <h3 class="text-sm font-bold uppercase
                                       tracking-wide text-gray-800">

                                Détail du paiement

                            </h3>

                            <p class="text-xs text-gray-500 mt-1">

                                Frais concernés par ce règlement

                            </p>

                        </div>

                    </div>


                    <div class="overflow-hidden border
                                border-gray-300 rounded-lg">

                        <table class="w-full text-sm">

                            <thead class="bg-gray-100">

                                <tr>

                                    <th
                                        class="px-4 py-3 text-left
                                               text-xs uppercase
                                               font-semibold text-gray-600">

                                        Désignation des frais

                                    </th>

                                    <th
                                        class="px-4 py-3 text-right
                                               text-xs uppercase
                                               font-semibold text-gray-600">

                                        Montant

                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-200">

                                @forelse($paiement->details as $detail)

                                    <tr>

                                        <td class="px-4 py-3 text-gray-800">

                                            {{ $detail->fraisEleve?->tarif
                                                ?->categorieFrais?->libelle
                                                ?? 'Frais scolaire' }}

                                        </td>

                                        <td
                                            class="px-4 py-3 text-right
                                                   font-semibold text-gray-800">

                                            {{ number_format(
                                                $detail->montant,
                                                2,
                                                ',',
                                                ' '
                                            ) }}

                                            {{ $paiement->devise }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="2"
                                            class="px-4 py-5 text-center
                                                   text-gray-500">

                                            Aucun détail disponible.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TOTAL --}}
                {{-- ================================================= --}}

                <div class="px-8 pt-5">

                    <div class="flex justify-end">

                        <div class="w-full md:w-1/2">

                            <div
                                class="border-2 border-gray-800
                                       rounded-lg px-5 py-4
                                       bg-gray-50">

                                <div
                                    class="flex items-center
                                           justify-between">

                                    <span
                                        class="font-black uppercase
                                               text-gray-800">

                                        Total payé

                                    </span>

                                    <span
                                        class="text-xl font-black
                                               text-gray-900">

                                        {{ number_format(
                                            $paiement->montant_total,
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                        {{ $paiement->devise }}

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- INFORMATIONS COMPLÉMENTAIRES --}}
                {{-- ================================================= --}}

                <div class="px-8 pt-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                        {{-- RÉFÉRENCE --}}

                        <div
                            class="border border-gray-200
                                   rounded-lg p-4">

                            <p class="text-xs uppercase
                                      font-medium text-gray-500">

                                Référence

                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $paiement->reference ?? '—' }}

                            </p>

                        </div>


                        {{-- AGENT --}}

                        <div
                            class="border border-gray-200
                                   rounded-lg p-4">

                            <p class="text-xs uppercase
                                      font-medium text-gray-500">

                                Paiement enregistré par

                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $utilisateur?->nom ?? '—' }}

                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- OBSERVATION --}}
                {{-- ================================================= --}}

                @if($paiement->observation)

                    <div class="px-8 pt-5">

                        <div
                            class="border border-gray-200
                                   rounded-lg p-4">

                            <p class="text-xs uppercase
                                      font-bold text-gray-500">

                                Observation

                            </p>

                            <p class="text-sm text-gray-700 mt-1">

                                {{ $paiement->observation }}

                            </p>

                        </div>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- SIGNATURES --}}
                {{-- ================================================= --}}

                <div class="px-8 pt-8 pb-6">

                    <div class="grid grid-cols-2 gap-16">


                        <div class="text-center">

                            <p class="font-bold text-sm uppercase
                                      text-gray-800">

                                Percepteur

                            </p>

                            <div class="h-5"></div>

                            <div class="border-t border-gray-400"></div>

                            <p class="text-xs text-gray-500 mt-1">

                                Signature

                            </p>

                        </div>


                        <div class="text-center">

                            <p class="font-bold text-sm uppercase
                                      text-gray-800">

                                Direction

                            </p>

                            <div class="h-5"></div>

                            <div class="border-t border-gray-400"></div>

                            <p class="text-xs text-gray-500 mt-1">

                                Signature et cachet

                            </p>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PIED DE PAGE --}}
                {{-- ================================================= --}}

                <div
                    class="border-t border-gray-200
                           px-8 py-3 text-center">

                    <p class="text-xs text-gray-500">

                        Merci pour votre paiement.

                    </p>

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
                size: A4;
                margin: 8mm;
            }

            html,
            body {
                background: white !important;
            }

            body * {
                visibility: hidden;
            }

            #recu,
            #recu * {
                visibility: visible;
            }

            #recu {
                position: absolute;
                left: 0;
                top: 0;

                width: 100% !important;
                max-width: none !important;

                margin: 0 !important;
                padding: 0 !important;

                border: 1px solid #999 !important;
                border-radius: 0 !important;

                box-shadow: none !important;
            }

            .print\:hidden {
                display: none !important;
            }

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
            }

        }

    </style>

</x-app-layout>
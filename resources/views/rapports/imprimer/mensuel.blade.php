<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Rapport mensuel</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        .no-print {
            margin-bottom: 20px;
            text-align: right;
        }

        .no-print button {
            padding: 9px 15px;
            border: none;
            background: #555;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .header h2 {
            margin: 8px 0;
            font-size: 18px;
        }

        .header p {
            margin: 4px 0;
        }

        .section-title {
            background: #eeeeee;
            padding: 9px;
            font-weight: bold;
            margin-top: 22px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
        }

        th {
            background: #eeeeee;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #eeeeee;
        }

        .summary td:first-child {
            font-weight: bold;
        }

        @media print {

            .no-print {
                display: none;
            }

            body {
                margin: 10mm;
            }

            .section-title {
                background: #eeeeee !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .total,
            th {
                background: #eeeeee !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

        }

    </style>

</head>

<body>


<div class="no-print">

    <button onclick="window.print()">
        🖨️ Imprimer
    </button>

</div>


<div class="header">

    @if($etablissement)

        <h1>
            {{ $etablissement->nom }}
        </h1>

        @if($etablissement->type)
            <p>{{ $etablissement->type }}</p>
        @endif

        <p>
            {{ $etablissement->ville ?? '' }}
            {{ $etablissement->commune ?? '' }}
        </p>

    @endif

    <h2>
        RAPPORT MENSUEL
    </h2>

    <p>
        Année scolaire :
        <strong>{{ $annee->libelle }}</strong>
    </p>

    <p>
        Mois :
        <strong>
            {{ date('F Y', strtotime($dateDebut)) }}
        </strong>
    </p>

</div>


{{-- EFFECTIF --}}

<div class="section-title">
    EFFECTIF PAR CLASSE ET PAR SEXE
</div>

<table>

    <thead>

        <tr>

            <th>Classe</th>
            <th>Option</th>
            <th>Garçons</th>
            <th>Filles</th>
            <th>Total</th>

        </tr>

    </thead>

    <tbody>

        @php

            $totalGarcons = 0;
            $totalFilles = 0;
            $totalEleves = 0;

        @endphp

        @forelse($effectifsClasses as $classe)

            @php

                $garcons = (int) $classe->garcons;
                $filles = (int) $classe->filles;
                $total = (int) $classe->total;

                $totalGarcons += $garcons;
                $totalFilles += $filles;
                $totalEleves += $total;

            @endphp

            <tr>

                <td>
                    {{ $classe->libelle }}
                </td>

                <td>
                    {{ $classe->option_classe ?? '-' }}
                </td>

                <td class="center">
                    {{ $garcons }}
                </td>

                <td class="center">
                    {{ $filles }}
                </td>

                <td class="center">
                    {{ $total }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="center">
                    Aucun élève inscrit.
                </td>

            </tr>

        @endforelse

        <tr class="total">

            <td colspan="2">
                TOTAL GÉNÉRAL
            </td>

            <td class="center">
                {{ $totalGarcons }}
            </td>

            <td class="center">
                {{ $totalFilles }}
            </td>

            <td class="center">
                {{ $totalEleves }}
            </td>

        </tr>

    </tbody>

</table>


{{-- SYNTHÈSE --}}

<div class="section-title">
    SYNTHÈSE DU MOIS
</div>

<table class="summary">

    <tbody>

        <tr>
            <td>Effectif total</td>
            <td class="center">
                {{ $nombreEleves }}
            </td>
        </tr>

        <tr>
            <td>Garçons</td>
            <td class="center">
                {{ $nombreGarcons }}
            </td>
        </tr>

        <tr>
            <td>Filles</td>
            <td class="center">
                {{ $nombreFilles }}
            </td>
        </tr>

        <tr>
            <td>Nouvelles inscriptions</td>
            <td class="center">
                {{ $nombreInscriptions }}
            </td>
        </tr>

        <tr>
            <td>Présences</td>
            <td class="center">
                {{ $nombrePresences }}
            </td>
        </tr>

        <tr>
            <td>Absences</td>
            <td class="center">
                {{ $nombreAbsences }}
            </td>
        </tr>

        <tr>
            <td>Taux de fréquentation</td>
            <td class="center">
                {{ number_format($tauxFrequentation, 2, ',', ' ') }} %
            </td>
        </tr>

        <tr>
            <td>Évaluations</td>
            <td class="center">
                {{ $nombreEvaluations }}
            </td>
        </tr>

        <tr>
            <td>Notes enregistrées</td>
            <td class="center">
                {{ $nombreNotes }}
            </td>
        </tr>

        <tr>
            <td>Moyenne générale</td>
            <td class="center">
                {{ number_format($moyenneNotes, 2, ',', ' ') }}
            </td>
        </tr>

    </tbody>

</table>


{{-- RÉSULTATS PAR CLASSE --}}

@if($resultatsClasses->count())

    <div class="section-title">
        RÉSULTATS PAR CLASSE
    </div>

    <table>

        <thead>

            <tr>

                <th>Classe</th>
                <th>Nombre de notes</th>
                <th>Moyenne</th>

            </tr>

        </thead>

        <tbody>

            @foreach($resultatsClasses as $resultat)

                <tr>

                    <td>
                        {{ $resultat->libelle }}
                    </td>

                    <td class="center">
                        {{ $resultat->nombre_notes }}
                    </td>

                    <td class="center">
                        {{ number_format($resultat->moyenne, 2, ',', ' ') }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

@endif


{{-- FINANCES --}}

<div class="section-title">
    SITUATION FINANCIÈRE DU MOIS
</div>

<table>

    <thead>

        <tr>
            <th>Élément</th>
            <th>Valeur</th>
        </tr>

    </thead>

    <tbody>

        <tr>

            <td>
                Nombre de paiements
            </td>

            <td class="center">
                {{ $nombrePaiements }}
            </td>

        </tr>

        <tr>

            <td>
                Total recettes
            </td>

            <td class="right">
                {{ number_format($totalRecettes, 2, ',', ' ') }}
            </td>

        </tr>

        <tr>

            <td>
                Total dépenses
            </td>

            <td class="right">
                {{ number_format($totalDepenses, 2, ',', ' ') }}
            </td>

        </tr>

        <tr class="total">

            <td>
                Solde
            </td>

            <td class="right">
                {{ number_format($solde, 2, ',', ' ') }}
            </td>

        </tr>

    </tbody>

</table>


{{-- PERSONNEL --}}

<div class="section-title">
    PERSONNEL
</div>

<table>

    <thead>

        <tr>
            <th>Catégorie</th>
            <th>Nombre</th>
        </tr>

    </thead>

    <tbody>

        <tr>

            <td>
                Enseignants
            </td>

            <td class="center">
                {{ $nombreEnseignants }}
            </td>

        </tr>

        <tr>

            <td>
                Autre personnel
            </td>

            <td class="center">
                {{ $nombreAutrePersonnel }}
            </td>

        </tr>

        <tr class="total">

            <td>
                Total personnel
            </td>

            <td class="center">
                {{ $nombreEnseignants + $nombreAutrePersonnel }}
            </td>

        </tr>

    </tbody>

</table>


<script>

    window.onload = function () {
        window.print();
    };

</script>

</body>

</html>
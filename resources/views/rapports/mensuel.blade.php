<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Rapport mensuel</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            margin: 0;
            padding: 30px;
            color: #222;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header h2 {
            margin: 8px 0;
            font-size: 18px;
        }

        .header p {
            margin: 4px 0;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .actions a,
        .actions button {
            border: none;
            padding: 9px 14px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            background: #555;
            color: white;
            font-size: 13px;
        }

        .actions a:hover,
        .actions button:hover {
            opacity: 0.85;
        }

        .selection {
            margin-bottom: 25px;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 6px;
        }

        .selection form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .selection input {
            padding: 8px;
            border: 1px solid #aaa;
            border-radius: 4px;
        }

        .selection button {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            background: #555;
            color: white;
            cursor: pointer;
        }

        .section-title {
            background: #eeeeee;
            padding: 10px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
        }

        .card .label {
            font-size: 13px;
            color: #666;
        }

        .card .value {
            font-size: 22px;
            font-weight: bold;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #777;
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

        @media(max-width: 800px) {

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .selection form {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>

</head>

<body>

<div class="container">


    {{-- ACTIONS --}}

    <div class="actions">

        <a href="{{ route('rapports.mensuel.pdf', ['mois' => $mois]) }}">
            PDF
        </a>

        <a href="{{ route('rapports.mensuel.imprimer', ['mois' => $mois]) }}"
           target="_blank">
            Imprimer
        </a>

        <a href="{{ route('rapports.mensuel.excel', ['mois' => $mois]) }}">
            Excel
        </a>

    </div>


    {{-- EN-TÊTE --}}

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


    {{-- SÉLECTION DU MOIS --}}

    <div class="selection">

        <form method="GET"
              action="{{ route('rapports.mensuel') }}">

            <label for="mois">
                Sélectionner le mois :
            </label>

            <input
                type="month"
                id="mois"
                name="mois"
                value="{{ $mois }}"
            >

            <button type="submit">
                Afficher
            </button>

        </form>

    </div>


    {{-- EFFECTIFS --}}

    <div class="section-title">
        EFFECTIF DU MOIS
    </div>

    <div class="cards">

        <div class="card">

            <div class="label">
                Total élèves
            </div>

            <div class="value">
                {{ $nombreEleves }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Garçons
            </div>

            <div class="value">
                {{ $nombreGarcons }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Filles
            </div>

            <div class="value">
                {{ $nombreFilles }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Nouvelles inscriptions
            </div>

            <div class="value">
                {{ $nombreInscriptions }}
            </div>

        </div>

    </div>


    {{-- EFFECTIF PAR CLASSE --}}

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


    {{-- FRÉQUENTATION --}}

    <div class="section-title">
        FRÉQUENTATION
    </div>

    <div class="cards">

        <div class="card">

            <div class="label">
                Présences
            </div>

            <div class="value">
                {{ $nombrePresences }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Absences
            </div>

            <div class="value">
                {{ $nombreAbsences }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Taux de fréquentation
            </div>

            <div class="value">
                {{ $tauxFrequentation }} %
            </div>

        </div>

    </div>


    {{-- RÉSULTATS SCOLAIRES --}}

    <div class="section-title">
        RÉSULTATS SCOLAIRES
    </div>

    <div class="cards">

        <div class="card">

            <div class="label">
                Évaluations
            </div>

            <div class="value">
                {{ $nombreEvaluations }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Notes
            </div>

            <div class="value">
                {{ $nombreNotes }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Moyenne générale
            </div>

            <div class="value">
                {{ number_format($moyenneNotes, 2, ',', ' ') }}
            </div>

        </div>

    </div>


    @if($resultatsClasses->count())

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

    <div class="cards">

        <div class="card">

            <div class="label">
                Paiements
            </div>

            <div class="value">
                {{ $nombrePaiements }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Recettes
            </div>

            <div class="value">
                {{ number_format($totalRecettes, 2, ',', ' ') }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Dépenses
            </div>

            <div class="value">
                {{ number_format($totalDepenses, 2, ',', ' ') }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Solde
            </div>

            <div class="value">
                {{ number_format($solde, 2, ',', ' ') }}
            </div>

        </div>

    </div>


    {{-- PERSONNEL --}}

    <div class="section-title">
        PERSONNEL
    </div>

    <div class="cards">

        <div class="card">

            <div class="label">
                Enseignants
            </div>

            <div class="value">
                {{ $nombreEnseignants }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Autre personnel
            </div>

            <div class="value">
                {{ $nombreAutrePersonnel }}
            </div>

        </div>

        <div class="card">

            <div class="label">
                Personnel total
            </div>

            <div class="value">
                {{ $nombreEnseignants + $nombreAutrePersonnel }}
            </div>

        </div>

    </div>

</div>

</body>

</html>
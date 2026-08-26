<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Rapport statistique</title>

    <style>

        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 15px;
        }

        .header p {
            margin: 3px 0;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            background: #eeeeee;
            border: 1px solid #cccccc;
            padding: 8px;
            font-weight: bold;
            font-size: 13px;
        }

        .cards {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .cards td {
            width: 25%;
            border: 1px solid #cccccc;
            text-align: center;
            padding: 10px;
        }

        .number {
            font-size: 18px;
            font-weight: bold;
        }

        .label {
            margin-top: 4px;
            color: #555555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th {
            background: #eeeeee;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #bbbbbb;
            padding: 7px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .financial {
            font-size: 11px;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            color: #666666;
            font-size: 9px;
            border-top: 1px solid #cccccc;
            padding-top: 8px;
        }

    </style>
</head>

<body>

    {{-- EN-TÊTE --}}
    <div class="header">

        <h1>
            {{ $etablissement->nom ?? 'ÉTABLISSEMENT SCOLAIRE' }}
        </h1>

        <h2>
            RAPPORT STATISTIQUE
        </h2>

        <p>
            Année scolaire :
            <strong>{{ $anneeScolaire->libelle }}</strong>
        </p>

        <p>
            Date de génération :
            {{ now()->format('d/m/Y H:i') }}
        </p>

    </div>


    {{-- EFFECTIFS --}}
    <div class="section">

        <div class="section-title">
            1. EFFECTIFS DES ÉLÈVES
        </div>

        <table class="cards">

            <tr>

                <td>
                    <div class="number">
                        {{ $nombreEleves }}
                    </div>

                    <div class="label">
                        Total élèves
                    </div>
                </td>

                <td>
                    <div class="number">
                        {{ $nombreGarcons }}
                    </div>

                    <div class="label">
                        Garçons
                    </div>
                </td>

                <td>
                    <div class="number">
                        {{ $nombreFilles }}
                    </div>

                    <div class="label">
                        Filles
                    </div>
                </td>

                <td>
                    <div class="number">
                        {{ $nombreClasses }}
                    </div>

                    <div class="label">
                        Classes
                    </div>
                </td>

            </tr>

        </table>

    </div>


    {{-- CLASSES --}}
    <div class="section">

        <div class="section-title">
            2. RÉPARTITION DES ÉLÈVES PAR CLASSE
        </div>

        <table>

            <thead>

                <tr>

                    <th>Classe</th>
                    <th>Option</th>
                    <th class="center">Effectif</th>

                </tr>

            </thead>

            <tbody>

                @forelse($effectifsClasses as $classe)

                    <tr>

                        <td>
                            {{ $classe->libelle }}
                        </td>

                        <td>
                            {{ $classe->option_classe ?? '-' }}
                        </td>

                        <td class="center">
                            {{ $classe->total }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="center">
                            Aucun élève inscrit.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PERSONNEL --}}
    <div class="section">

        <div class="section-title">
            3. PERSONNEL
        </div>

        <table class="cards">

            <tr>

                <td>
                    <div class="number">
                        {{ $nombrePersonnel }}
                    </div>

                    <div class="label">
                        Personnel total
                    </div>
                </td>

                <td>
                    <div class="number">
                        {{ $nombreEnseignants }}
                    </div>

                    <div class="label">
                        Enseignants
                    </div>
                </td>

                <td colspan="2">
                    <div class="number">
                        {{ $nombreAutrePersonnel }}
                    </div>

                    <div class="label">
                        Autre personnel
                    </div>
                </td>

            </tr>

        </table>

    </div>


    {{-- SCOLARITÉ --}}
    <div class="section">

        <div class="section-title">
            4. SCOLARITÉ
        </div>

        <table>

            <tr>

                <td>
                    Nombre d'inscriptions
                </td>

                <td class="center">
                    <strong>
                        {{ $nombreInscriptions }}
                    </strong>
                </td>

            </tr>

            <tr>

                <td>
                    Nombre d'élèves
                </td>

                <td class="center">
                    <strong>
                        {{ $nombreEleves }}
                    </strong>
                </td>

            </tr>

            <tr>

                <td>
                    Nombre de classes
                </td>

                <td class="center">
                    <strong>
                        {{ $nombreClasses }}
                    </strong>
                </td>

            </tr>

        </table>

    </div>


    {{-- FRÉQUENTATION --}}
    <div class="section">

        <div class="section-title">
            5. FRÉQUENTATION SCOLAIRE
        </div>

        <table class="cards">

            <tr>

                <td>
                    <div class="number">
                        {{ $nombrePresences }}
                    </div>

                    <div class="label">
                        Présences
                    </div>
                </td>

                <td>
                    <div class="number">
                        {{ $nombreAbsences }}
                    </div>

                    <div class="label">
                        Absences
                    </div>
                </td>

                <td>
                    <div class="number">
                        {{ $totalFrequentation }}
                    </div>

                    <div class="label">
                        Total
                    </div>
                </td>

                <td>
                    <div class="number">
                        {{ $tauxPresence }} %
                    </div>

                    <div class="label">
                        Taux de présence
                    </div>
                </td>

            </tr>

        </table>

    </div>


    {{-- RÉSULTATS --}}
    <div class="section">

        <div class="section-title">
            6. RÉSULTATS SCOLAIRES
        </div>

        <table>

            <thead>

                <tr>

                    <th>Indicateur</th>
                    <th class="center">Valeur</th>

                </tr>

            </thead>

            <tbody>

                <tr>
                    <td>Nombre d'évaluations</td>
                    <td class="center">
                        {{ $nombreEvaluations }}
                    </td>
                </tr>

                <tr>
                    <td>Nombre de notes</td>
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

                <tr>
                    <td>Réussites</td>
                    <td class="center">
                        {{ $nombreReussites }}
                    </td>
                </tr>

                <tr>
                    <td>Échecs</td>
                    <td class="center">
                        {{ $nombreEchecs }}
                    </td>
                </tr>

            </tbody>

        </table>

    </div>


    {{-- FINANCES --}}
    <div class="section">

        <div class="section-title">
            7. SITUATION FINANCIÈRE
        </div>

        <table class="financial">

            <thead>

                <tr>

                    <th>Devise</th>
                    <th class="right">Recettes</th>
                    <th class="right">Dépenses</th>
                    <th class="right">Solde</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        USD
                    </td>

                    <td class="right">
                        {{ number_format($recettesUSD, 2, '.', ',') }}
                    </td>

                    <td class="right">
                        {{ number_format($depensesUSD, 2, '.', ',') }}
                    </td>

                    <td class="right">
                        <strong>
                            {{ number_format($soldeUSD, 2, '.', ',') }}
                        </strong>
                    </td>

                </tr>


                <tr>

                    <td>
                        CDF
                    </td>

                    <td class="right">
                        {{ number_format($recettesCDF, 2, '.', ',') }}
                    </td>

                    <td class="right">
                        {{ number_format($depensesCDF, 2, '.', ',') }}
                    </td>

                    <td class="right">
                        <strong>
                            {{ number_format($soldeCDF, 2, '.', ',') }}
                        </strong>
                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    {{-- PIED --}}
    <div class="footer">

        Rapport statistique —
        {{ $anneeScolaire->libelle }}

        <br>

        Document généré automatiquement par le système de gestion scolaire.

    </div>

</body>

</html>
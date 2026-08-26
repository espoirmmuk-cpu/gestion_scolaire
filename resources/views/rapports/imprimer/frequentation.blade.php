<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Impression - Fiche de fréquentation</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 7px;
        }

        th {
            background: #eee;
        }

        .center {
            text-align: center;
        }

        @media print {

            .no-print {
                display: none !important;
            }

        }

    </style>

</head>

<body>

    <div class="no-print" style="margin-bottom:20px;">

        <button onclick="window.print()">
            🖨️ Imprimer
        </button>

    </div>


    <h1>
        FICHE DE FRÉQUENTATION
    </h1>


    <div class="subtitle">

        <strong>Année scolaire :</strong>
        {{ $annee->libelle ?? '-' }}

        &nbsp;&nbsp;|&nbsp;&nbsp;

        <strong>Période :</strong>
        {{ $periode->libelle ?? '-' }}

        &nbsp;&nbsp;|&nbsp;&nbsp;

        <strong>Classe :</strong>
        {{ $classe->libelle ?? '-' }}

    </div>


    <table>

        <thead>

            <tr>

                <th>N°</th>
                <th>Matricule</th>
                <th>Élève</th>
                <th>Jours</th>
                <th>Présences</th>
                <th>Absences</th>
                <th>Taux</th>

            </tr>

        </thead>

        <tbody>

            @foreach($frequentation as $index => $eleve)

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $eleve->matricule }}
                    </td>

                    <td>
                        {{ $eleve->nom }}
                        {{ $eleve->postnom }}
                        {{ $eleve->prenom }}
                    </td>

                    <td class="center">
                        {{ $eleve->total_jours }}
                    </td>

                    <td class="center">
                        {{ $eleve->presents }}
                    </td>

                    <td class="center">
                        {{ $eleve->absents }}
                    </td>

                    <td class="center">
                        {{ number_format($eleve->taux, 2, ',', ' ') }} %
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <script>

        window.onload = function () {

            window.print();

        };

    </script>

</body>

</html>
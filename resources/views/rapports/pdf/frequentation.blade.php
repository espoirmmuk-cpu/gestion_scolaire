<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Fiche de fréquentation</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            width: 100%;
            margin-bottom: 15px;
        }

        .info td {
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 6px;
        }

        th {
            background: #eeeeee;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 8px;
        }

    </style>

</head>

<body>

    <h1>
        FICHE DE FRÉQUENTATION
    </h1>

    <div class="subtitle">

        Année scolaire :
        <strong>
            {{ $annee->libelle ?? '-' }}
        </strong>

        —

        Période :
        <strong>
            {{ $periode->libelle ?? '-' }}
        </strong>

    </div>


    <table class="info">

        <tr>

            <td>
                <strong>Classe :</strong>
                {{ $classe->libelle ?? '-' }}
            </td>

            <td>
                <strong>Date début :</strong>
                {{ $periode->date_debut ?? '-' }}
            </td>

            <td>
                <strong>Date fin :</strong>
                {{ $periode->date_fin ?? '-' }}
            </td>

        </tr>

    </table>


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


    <div class="footer">

        Document généré automatiquement par le système de gestion scolaire.

    </div>

</body>

</html>
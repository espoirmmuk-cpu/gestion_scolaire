<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Palmarès des élèves</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 15px;
        }

        .header p {
            margin: 3px 0;
        }

        .infos {
            margin-bottom: 15px;
        }

        .infos table {
            width: 100%;
            border: none;
        }

        .infos td {
            border: none;
            padding: 4px;
        }

        table.resultats {
            width: 100%;
            border-collapse: collapse;
        }

        table.resultats th,
        table.resultats td {
            border: 1px solid #777;
            padding: 7px;
        }

        table.resultats th {
            background: #eeeeee;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
        }

    </style>
</head>

<body>

    <div class="header">

        <h1>
            PALMARÈS DES ÉLÈVES
        </h1>

        <h2>
            {{ $classe->libelle ?? 'Classe' }}
        </h2>

        <p>
            Année scolaire :
            <strong>{{ $annee->libelle ?? '-' }}</strong>
        </p>

        <p>
            Période :
            <strong>{{ $periode->libelle ?? '-' }}</strong>
        </p>

    </div>


    <div class="infos">

        <table>

            <tr>

                <td>
                    <strong>Nombre d'élèves :</strong>
                    {{ $palmares->count() }}
                </td>

                <td class="right">
                    <strong>Date :</strong>
                    {{ date('d/m/Y') }}
                </td>

            </tr>

        </table>

    </div>


    <table class="resultats">

        <thead>

            <tr>

                <th width="8%">Rang</th>

                <th width="15%">Matricule</th>

                <th>Élève</th>

                <th width="8%">Sexe</th>

                <th width="12%">Moyenne</th>

                <th width="12%">Pourcentage</th>

                <th width="15%">Décision</th>

            </tr>

        </thead>


        <tbody>

            @foreach($palmares as $eleve)

                <tr>

                    <td class="center">
                        {{ $eleve->rang ?? '-' }}
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
                        {{ $eleve->sexe }}
                    </td>

                    <td class="right">
                        {{ number_format((float) $eleve->moyenne, 2, ',', ' ') }}
                    </td>

                    <td class="right">
                        {{ number_format((float) $eleve->pourcentage, 2, ',', ' ') }} %
                    </td>

                    <td class="center">
                        {{ $eleve->decision ?? '-' }}
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
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Liste des enseignants</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
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
            border: 1px solid #555;
            padding: 5px;
        }

        th {
            background: #eeeeee;
            text-align: center;
        }

        .center {
            text-align: center;
        }

    </style>

</head>

<body>

    <h1>
        LISTE DES ENSEIGNANTS
    </h1>

    <div class="subtitle">

        Effectif :
        <strong>{{ $enseignants->count() }}</strong>
        enseignant(s)

    </div>


    <table>

        <thead>

            <tr>

                <th>N°</th>
                <th>Matricule</th>
                <th>Nom complet</th>
                <th>Sexe</th>
                <th>Qualification</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Engagement</th>
                <th>Statut</th>

            </tr>

        </thead>

        <tbody>

            @foreach($enseignants as $index => $enseignant)

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $enseignant->matricule }}
                    </td>

                    <td>
                        {{ $enseignant->nom }}
                        {{ $enseignant->postnom }}
                        {{ $enseignant->prenom }}
                    </td>

                    <td class="center">
                        {{ $enseignant->sexe }}
                    </td>

                    <td>
                        {{ $enseignant->qualification }}
                    </td>

                    <td>
                        {{ $enseignant->telephone }}
                    </td>

                    <td>
                        {{ $enseignant->email }}
                    </td>

                    <td class="center">
                        {{ $enseignant->date_engagement }}
                    </td>

                    <td class="center">
                        {{ $enseignant->statut }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</body>

</html>
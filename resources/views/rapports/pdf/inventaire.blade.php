<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Inventaire des biens</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }

        .resume {
            width: 100%;
            margin-bottom: 20px;
        }

        .resume td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #eeeeee;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

    </style>

</head>

<body>

    <h1>
        INVENTAIRE DES BIENS
    </h1>

    <div class="subtitle">

        État de l'inventaire de l'établissement

    </div>


    {{-- Résumé --}}

    <table class="resume">

        <tr>

            <td>

                <span class="label">
                    Nombre de biens
                </span>

                <br>

                {{ $totalBiens }}

            </td>

            <td>

                <span class="label">
                    Quantité totale
                </span>

                <br>

                {{ number_format($totalQuantite, 0, ',', ' ') }}

            </td>

        </tr>

    </table>


    {{-- Inventaire --}}

    <table>

        <thead>

            <tr>

                <th>N°</th>
                <th>Désignation</th>
                <th>Catégorie</th>
                <th>Quantité</th>
                <th>Date acquisition</th>
                <th>État</th>
                <th>Localisation</th>
                <th>Responsable</th>
                <th>Observation</th>

            </tr>

        </thead>

        <tbody>

            @forelse($biens as $index => $bien)

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $bien->designation }}
                    </td>

                    <td>
                        {{ $bien->categorie ?? '—' }}
                    </td>

                    <td class="center">
                        {{ $bien->quantite }}
                    </td>

                    <td class="center">
                        {{ $bien->date_acquisition ?? '—' }}
                    </td>

                    <td>
                        {{ $bien->etat ?? '—' }}
                    </td>

                    <td>
                        {{ $bien->localisation ?? '—' }}
                    </td>

                    <td>
                        {{ $bien->responsable ?? '—' }}
                    </td>

                    <td>
                        {{ $bien->observation ?? '—' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="9" class="center">
                        Aucun bien enregistré.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <p style="margin-top: 20px;">

        Document généré automatiquement par le système de gestion scolaire.

    </p>

</body>

</html>
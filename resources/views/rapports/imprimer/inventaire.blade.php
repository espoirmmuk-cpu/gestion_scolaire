<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Inventaire des biens</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 25px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }

        .actions {
            margin-bottom: 20px;
        }

        button {
            padding: 10px 18px;
            background: #222;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .resume {
            width: 100%;
            margin-bottom: 20px;
        }

        .resume td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }

        .label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .montant {
            font-size: 16px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eeeeee;
        }

        .center {
            text-align: center;
        }

        @media print {

            .no-print {
                display: none !important;
            }

            body {
                margin: 10px;
            }

        }

    </style>

</head>

<body>


    <div class="actions no-print">

        <button onclick="window.print()">
            🖨️ Imprimer
        </button>

    </div>


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

                <span class="montant">
                    {{ $totalBiens }}
                </span>

            </td>


            <td>

                <span class="label">
                    Quantité totale
                </span>

                <span class="montant">
                    {{ number_format($totalQuantite, 0, ',', ' ') }}
                </span>

            </td>

        </tr>

    </table>


    {{-- Tableau --}}

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


    <p style="margin-top: 25px;">

        Date d'impression :
        {{ now()->format('d/m/Y H:i') }}

    </p>


    <script>

        window.onload = function () {
            window.print();
        };

    </script>

</body>

</html>
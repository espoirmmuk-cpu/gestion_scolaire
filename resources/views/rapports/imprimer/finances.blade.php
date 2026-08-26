<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Situation financière</title>

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
            margin-bottom: 25px;
        }

        .resume {
            width: 100%;
            margin-bottom: 25px;
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

        h2 {
            margin-top: 25px;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eeeeee;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .no-print {
            margin-bottom: 20px;
        }

        @media print {

            .no-print {
                display: none !important;
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


    <h1>
        SITUATION FINANCIÈRE
    </h1>


    <div class="subtitle">

        Année scolaire :
        <strong>
            {{ $anneeSelectionnee->libelle ?? '-' }}
        </strong>

    </div>


    {{-- Résumé financier --}}

    <table class="resume">

        <tr>

            <td>

                <span class="label">
                    Total recettes
                </span>

                <span class="montant">
                    {{ number_format($totalRecettes, 2, ',', ' ') }}
                </span>

            </td>


            <td>

                <span class="label">
                    Total dépenses
                </span>

                <span class="montant">
                    {{ number_format($totalDepenses, 2, ',', ' ') }}
                </span>

            </td>


            <td>

                <span class="label">
                    Solde
                </span>

                <span class="montant">
                    {{ number_format($solde, 2, ',', ' ') }}
                </span>

            </td>

        </tr>

    </table>


    {{-- Recettes --}}

    <h2>
        Recettes
    </h2>

    <table>

        <thead>

            <tr>

                <th>Date</th>
                <th>Source</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Devise</th>

            </tr>

        </thead>

        <tbody>

            @forelse($recettes as $recette)

                <tr>

                    <td>
                        {{ $recette->date_recette }}
                    </td>

                    <td>
                        {{ $recette->source }}
                    </td>

                    <td>
                        {{ $recette->description }}
                    </td>

                    <td class="right">
                        {{ number_format($recette->montant, 2, ',', ' ') }}
                    </td>

                    <td class="center">
                        {{ $recette->devise }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="center">
                        Aucune recette enregistrée.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- Dépenses --}}

    <h2>
        Dépenses
    </h2>

    <table>

        <thead>

            <tr>

                <th>Date</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Devise</th>

            </tr>

        </thead>

        <tbody>

            @forelse($depenses as $depense)

                <tr>

                    <td>
                        {{ $depense->date_depense }}
                    </td>

                    <td>
                        {{ $depense->categorie }}
                    </td>

                    <td>
                        {{ $depense->description }}
                    </td>

                    <td class="right">
                        {{ number_format($depense->montant, 2, ',', ' ') }}
                    </td>

                    <td class="center">
                        {{ $depense->devise }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="center">
                        Aucune dépense enregistrée.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <p style="margin-top:30px;">

        <strong>
            Document généré automatiquement par le système de gestion scolaire.
        </strong>

    </p>


    <script>

        window.onload = function () {

            window.print();

        };

    </script>

</body>

</html>
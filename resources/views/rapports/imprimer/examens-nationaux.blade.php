<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Statistiques des examens nationaux</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        .no-print {
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

        h1 {
            text-align: center;
        }

        h2 {
            font-size: 15px;
            margin-top: 25px;
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

        .center {
            text-align: center;
        }

        .resume td {
            text-align: center;
            font-weight: bold;
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
        STATISTIQUES DES EXAMENS NATIONAUX
    </h1>


    <table class="resume">

        <tr>

            <td>
                Candidats<br>
                {{ $nombreCandidats }}
            </td>

            <td>
                Admis<br>
                {{ $nombreAdmis }}
            </td>

            <td>
                Échecs<br>
                {{ $nombreEchecs }}
            </td>

            <td>
                Réussite<br>
                {{ number_format($tauxReussite, 2, ',', ' ') }} %
            </td>

            <td>
                Moyenne<br>
                {{ number_format($moyenneGenerale, 2, ',', ' ') }}/20
            </td>

        </tr>

    </table>


    <h2>Résultats par classe</h2>

    <table>

        <thead>

            <tr>
                <th>Classe</th>
                <th>Candidats</th>
                <th>Admis</th>
                <th>Échecs</th>
                <th>Taux réussite</th>
            </tr>

        </thead>

        <tbody>

            @forelse($statistiquesClasses as $stat)

                <tr>

                    <td>{{ $stat->classe }}</td>

                    <td class="center">
                        {{ $stat->candidats }}
                    </td>

                    <td class="center">
                        {{ $stat->admis }}
                    </td>

                    <td class="center">
                        {{ $stat->echecs }}
                    </td>

                    <td class="center">
                        {{ number_format($stat->taux, 2, ',', ' ') }} %
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="center">
                        Aucun résultat.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    <h2>Résultats par matière</h2>

    <table>

        <thead>

            <tr>
                <th>Matière</th>
                <th>Candidats</th>
                <th>Moyenne</th>
                <th>Meilleure note</th>
                <th>Note minimale</th>
            </tr>

        </thead>

        <tbody>

            @forelse($statistiquesMatieres as $stat)

                <tr>

                    <td>{{ $stat->matiere }}</td>

                    <td class="center">
                        {{ $stat->candidats }}
                    </td>

                    <td class="center">
                        {{ number_format($stat->moyenne, 2, ',', ' ') }}/20
                    </td>

                    <td class="center">
                        {{ number_format($stat->meilleure, 2, ',', ' ') }}/20
                    </td>

                    <td class="center">
                        {{ number_format($stat->minimale, 2, ',', ' ') }}/20
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="center">
                        Aucun résultat.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>


    <h2>Détail des résultats</h2>

    <table>

        <thead>

            <tr>
                <th>Matricule</th>
                <th>Élève</th>
                <th>Classe</th>
                <th>Matière</th>
                <th>Note</th>
                <th>Max.</th>
                <th>Résultat</th>
            </tr>

        </thead>

        <tbody>

            @foreach($examens as $examen)

                @php
                    $pourcentage = $examen->note_maximale > 0
                        ? ($examen->note / $examen->note_maximale) * 100
                        : 0;
                @endphp

                <tr>

                    <td>{{ $examen->matricule }}</td>

                    <td>
                        {{ $examen->nom }}
                        {{ $examen->postnom }}
                        {{ $examen->prenom }}
                    </td>

                    <td>{{ $examen->classe }}</td>

                    <td>{{ $examen->matiere }}</td>

                    <td class="center">
                        {{ $examen->note }}
                    </td>

                    <td class="center">
                        {{ $examen->note_maximale }}
                    </td>

                    <td class="center">
                        {{ $pourcentage >= 50 ? 'ADMIS' : 'ÉCHEC' }}
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
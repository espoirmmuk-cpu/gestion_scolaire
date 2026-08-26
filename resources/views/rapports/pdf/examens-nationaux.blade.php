<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Statistiques des examens nationaux</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            margin: 20px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
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
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #eeeeee;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        h2 {
            font-size: 13px;
            margin-top: 20px;
        }

    </style>

</head>

<body>

    <h1>
        STATISTIQUES DES EXAMENS NATIONAUX
    </h1>

    <div class="subtitle">
        Résultats des examens
    </div>


    {{-- Résumé --}}

    <table class="resume">

        <tr>

            <td>
                <span class="label">Candidats</span><br>
                {{ $nombreCandidats }}
            </td>

            <td>
                <span class="label">Admis</span><br>
                {{ $nombreAdmis }}
            </td>

            <td>
                <span class="label">Échecs</span><br>
                {{ $nombreEchecs }}
            </td>

            <td>
                <span class="label">Réussite</span><br>
                {{ number_format($tauxReussite, 2, ',', ' ') }} %
            </td>

            <td>
                <span class="label">Moyenne</span><br>
                {{ number_format($moyenneGenerale, 2, ',', ' ') }}/20
            </td>

        </tr>

    </table>


    <h2>
        Résultats par classe
    </h2>

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


    <h2>
        Résultats par matière
    </h2>

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


    <h2>
        Détail des résultats
    </h2>

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

</body>

</html>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>Bulletin scolaire</title>

    <style>

        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 15px;
        }

        .header p {
            margin: 3px 0;
        }

        .separator {
            border-top: 2px solid #222;
            margin: 10px 0 15px 0;
        }

        .titre {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }

        .infos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .infos td {
            padding: 5px;
            border: 1px solid #999;
        }

        .label {
            font-weight: bold;
            background: #eeeeee;
        }

        .resultats {
            width: 100%;
            border-collapse: collapse;
        }

        .resultats th,
        .resultats td {
            border: 1px solid #777;
            padding: 6px;
        }

        .resultats th {
            background: #eeeeee;
            text-align: center;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .summary td {
            border: 1px solid #777;
            padding: 7px;
        }

        .summary .label {
            width: 35%;
        }

        .observation {
            margin-top: 15px;
            border: 1px solid #777;
            padding: 8px;
            min-height: 45px;
        }

        .signatures {
            width: 100%;
            margin-top: 45px;
        }

        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
        }

    </style>

</head>

<body>


{{-- ========================================================= --}}
{{-- EN-TÊTE --}}
{{-- ========================================================= --}}

<div class="header">

    <h1>
        ÉTABLISSEMENT SCOLAIRE
    </h1>

    <p>
        Bulletin scolaire
    </p>

    <p>
        Année scolaire :
        <strong>
            {{ $annee->libelle ?? '-' }}
        </strong>
    </p>

</div>

<div class="separator"></div>


{{-- ========================================================= --}}
{{-- TITRE --}}
{{-- ========================================================= --}}

<div class="titre">
    Bulletin de résultats
</div>


{{-- ========================================================= --}}
{{-- INFORMATIONS ÉLÈVE --}}
{{-- ========================================================= --}}

<table class="infos">

    <tr>

        <td class="label">
            Matricule
        </td>

        <td>
            {{ $eleveData->matricule }}
        </td>

        <td class="label">
            Classe
        </td>

        <td>
            {{ $classe->libelle ?? '-' }}

            @if(!empty($classe->option_classe))
                — {{ $classe->option_classe }}
            @endif
        </td>

    </tr>


    <tr>

        <td class="label">
            Nom
        </td>

        <td>
            {{ $eleveData->nom }}
        </td>

        <td class="label">
            Période
        </td>

        <td>
            {{ $periode->libelle ?? '-' }}
        </td>

    </tr>


    <tr>

        <td class="label">
            Postnom
        </td>

        <td>
            {{ $eleveData->postnom }}
        </td>

        <td class="label">
            Sexe
        </td>

        <td>
            {{ $eleveData->sexe }}
        </td>

    </tr>


    <tr>

        <td class="label">
            Prénom
        </td>

        <td>
            {{ $eleveData->prenom }}
        </td>

        <td class="label">
            Date de naissance
        </td>

        <td>
            {{ $eleveData->date_naissance
                ? date('d/m/Y', strtotime($eleveData->date_naissance))
                : '-'
            }}
        </td>

    </tr>

</table>


{{-- ========================================================= --}}
{{-- RÉSULTATS --}}
{{-- ========================================================= --}}

<table class="resultats">

    <thead>

        <tr>

            <th width="8%">
                N°
            </th>

            <th width="32%">
                Matière
            </th>

            <th width="12%">
                Total
            </th>

            <th width="12%">
                Moyenne
            </th>

            <th width="12%">
                Coef.
            </th>

            <th width="12%">
                Points
            </th>

            <th>
                Appréciation
            </th>

        </tr>

    </thead>


    <tbody>

        @forelse($details as $index => $detail)

            <tr>

                <td class="center">
                    {{ $index + 1 }}
                </td>

                <td>

                    @if(!empty($detail->code))
                        <strong>{{ $detail->code }}</strong> -
                    @endif

                    {{ $detail->libelle }}

                </td>

                <td class="center">
                    {{ $detail->total ?? '-' }}
                </td>

                <td class="right">
                    {{ $detail->moyenne !== null
                        ? number_format((float) $detail->moyenne, 2, ',', ' ')
                        : '-'
                    }}
                </td>

                <td class="center">
                    {{ $detail->coefficient ?? '-' }}
                </td>

                <td class="right">
                    {{ $detail->points !== null
                        ? number_format((float) $detail->points, 2, ',', ' ')
                        : '-'
                    }}
                </td>

                <td>
                    {{ $detail->appreciation ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7" class="center">
                    Aucun détail de bulletin disponible.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>


{{-- ========================================================= --}}
{{-- RÉSUMÉ --}}
{{-- ========================================================= --}}

<table class="summary">

    <tr>

        <td class="label">
            Moyenne générale
        </td>

        <td class="right">
            <strong>
                {{ number_format((float) $bulletin->moyenne, 2, ',', ' ') }}
            </strong>
        </td>

    </tr>


    <tr>

        <td class="label">
            Pourcentage
        </td>

        <td class="right">
            <strong>
                {{ number_format((float) $bulletin->pourcentage, 2, ',', ' ') }}
                %
            </strong>
        </td>

    </tr>


    <tr>

        <td class="label">
            Rang
        </td>

        <td class="center">
            <strong>
                {{ $bulletin->rang ?? '-' }}
            </strong>
        </td>

    </tr>


    <tr>

        <td class="label">
            Décision
        </td>

        <td class="center">
            <strong>
                {{ $bulletin->decision ?? '-' }}
            </strong>
        </td>

    </tr>

</table>


{{-- ========================================================= --}}
{{-- OBSERVATION --}}
{{-- ========================================================= --}}

<div class="observation">

    <strong>
        Observation :
    </strong>

    {{ $bulletin->observation ?? '-' }}

</div>


{{-- ========================================================= --}}
{{-- SIGNATURES --}}
{{-- ========================================================= --}}

<table class="signatures">

    <tr>

        <td>
            <strong>
                Le titulaire
            </strong>
        </td>

        <td>
            <strong>
                Le chef d'établissement
            </strong>
        </td>

    </tr>

</table>


<div class="footer">

    Bulletin généré automatiquement par le système de gestion scolaire.

</div>


</body>

</html>
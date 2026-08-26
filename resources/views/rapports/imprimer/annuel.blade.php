<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>
        Rapport annuel - {{ $annee->libelle ?? 'Année scolaire' }}
    </title>

    <style>

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            background: white;
        }

        .no-print {
            margin-bottom: 15px;
            text-align: right;
        }

        .btn-print {
            background: #333;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-print:hover {
            background: #111;
        }

        /* ================================
           EN-TÊTE
        ================================= */

        .header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #222;
        }

        .logo {
            max-width: 70px;
            max-height: 70px;
            margin-bottom: 5px;
        }

        .etablissement {
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .type {
            font-size: 11px;
            margin-bottom: 3px;
        }

        .coordonnees {
            font-size: 9px;
            color: #444;
        }

        .titre {
            font-size: 18px;
            font-weight: bold;
            margin-top: 12px;
            text-transform: uppercase;
        }

        .annee {
            font-size: 12px;
            margin-top: 5px;
        }

        /* ================================
           SECTIONS
        ================================= */

        .section {
            margin-top: 16px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #e5e5e5;
            border: 1px solid #999;
            padding: 6px 8px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .sub-title {
            font-size: 11px;
            font-weight: bold;
            margin: 10px 0 5px 0;
        }

        /* ================================
           INFORMATIONS
        ================================= */

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .info-table td {
            border: 1px solid #ccc;
            padding: 5px;
            vertical-align: top;
        }

        .info-label {
            width: 18%;
            font-weight: bold;
            background: #f5f5f5;
        }

        /* ================================
           STATISTIQUES
        ================================= */

        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
        }

        .stat-box {
            border: 1px solid #bbb;
            padding: 7px;
            text-align: center;
            background: #fafafa;
        }

        .stat-label {
            font-size: 9px;
            color: #555;
        }

        .stat-value {
            font-size: 17px;
            font-weight: bold;
            margin-top: 3px;
        }

        /* ================================
           TABLEAUX
        ================================= */

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #999;
            padding: 5px 6px;
        }

        table.data-table th {
            background: #eaeaea;
            font-weight: bold;
            text-align: center;
        }

        table.data-table td {
            vertical-align: middle;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #e5e5e5;
        }

        .empty {
            text-align: center;
            color: #666;
            padding: 10px !important;
        }

        /* ================================
           PIED DE PAGE
        ================================= */

        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #999;
            font-size: 8px;
            text-align: center;
            color: #555;
        }

        /* ================================
           IMPRESSION
        ================================= */

        @media print {

            .no-print {
                display: none !important;
            }

            body {
                font-size: 9px;
            }

            .section {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-row-group;
            }

            .section-title {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .total,
            .stat-box,
            table.data-table th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

        }

    </style>

</head>


<body>

    {{-- ============================================================
         BOUTON IMPRIMER
    ============================================================ --}}

    <div class="no-print">

        <button
            type="button"
            class="btn-print"
            onclick="window.print()"
        >
            🖨️ Imprimer le rapport
        </button>

    </div>


    {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}

    <div class="header">

        @if(isset($etablissement) && $etablissement)

            @if(!empty($etablissement->logo))

                <img
                    src="{{ asset('storage/' . $etablissement->logo) }}"
                    alt="Logo"
                    class="logo"
                >

            @endif

            <div class="etablissement">
                {{ $etablissement->nom }}
            </div>

            @if(!empty($etablissement->type))

                <div class="type">
                    {{ $etablissement->type }}
                </div>

            @endif

            <div class="coordonnees">

                @if(!empty($etablissement->province))
                    Province : {{ $etablissement->province }}
                @endif

                @if(!empty($etablissement->ville))
                    | Ville : {{ $etablissement->ville }}
                @endif

                @if(!empty($etablissement->commune))
                    | Commune : {{ $etablissement->commune }}
                @endif

                @if(!empty($etablissement->telephone))
                    | Tél. : {{ $etablissement->telephone }}
                @endif

            </div>

        @endif


        <div class="titre">
            RAPPORT ANNUEL
        </div>

        <div class="annee">

            Année scolaire :
            <strong>
                {{ $annee->libelle ?? '-' }}
            </strong>

            @if(!empty($annee->date_debut) || !empty($annee->date_fin))

                <br>

                Période :
                {{ $annee->date_debut ?? '-' }}
                →
                {{ $annee->date_fin ?? '-' }}

            @endif

        </div>

    </div>


    {{-- ============================================================
         INFORMATIONS DE L'ÉTABLISSEMENT
    ============================================================ --}}

    @if(isset($etablissement) && $etablissement)

        <div class="section">

            <div class="section-title">
                Informations de l'établissement
            </div>

            <table class="info-table">

                <tr>

                    <td class="info-label">
                        Code
                    </td>

                    <td>
                        {{ $etablissement->code ?? '-' }}
                    </td>

                    <td class="info-label">
                        Directeur
                    </td>

                    <td>
                        {{ $etablissement->directeur ?? '-' }}
                    </td>

                </tr>

                <tr>

                    <td class="info-label">
                        Province
                    </td>

                    <td>
                        {{ $etablissement->province ?? '-' }}
                    </td>

                    <td class="info-label">
                        Ville
                    </td>

                    <td>
                        {{ $etablissement->ville ?? '-' }}
                    </td>

                </tr>

                <tr>

                    <td class="info-label">
                        Commune
                    </td>

                    <td>
                        {{ $etablissement->commune ?? '-' }}
                    </td>

                    <td class="info-label">
                        Téléphone
                    </td>

                    <td>
                        {{ $etablissement->telephone ?? '-' }}
                    </td>

                </tr>

            </table>

        </div>

    @endif


    {{-- ============================================================
         STATISTIQUES GÉNÉRALES
    ============================================================ --}}

    <div class="section">

        <div class="section-title">
            Statistiques générales
        </div>

        <table class="stats-table">

            <tr>

                <td class="stat-box">

                    <div class="stat-label">
                        Élèves inscrits
                    </div>

                    <div class="stat-value">
                        {{ $nombreEleves ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Garçons
                    </div>

                    <div class="stat-value">
                        {{ $nombreGarcons ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Filles
                    </div>

                    <div class="stat-value">
                        {{ $nombreFilles ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Classes
                    </div>

                    <div class="stat-value">
                        {{ $nombreClasses ?? 0 }}
                    </div>

                </td>

            </tr>

            <tr>

                <td class="stat-box">

                    <div class="stat-label">
                        Enseignants
                    </div>

                    <div class="stat-value">
                        {{ $nombreEnseignants ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Autre personnel
                    </div>

                    <div class="stat-value">
                        {{ $nombreAutrePersonnel ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Inscriptions
                    </div>

                    <div class="stat-value">
                        {{ $nombreInscriptions ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Paiements
                    </div>

                    <div class="stat-value">
                        {{ $nombrePaiements ?? 0 }}
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- ============================================================
         EFFECTIF PAR CLASSE ET SEXE
    ============================================================ --}}

    <div class="section">

        <div class="section-title">
            Effectif par classe et par sexe
        </div>

        <table class="data-table">

            <thead>

                <tr>

                    <th>Classe</th>
                    <th>Option</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>

                </tr>

            </thead>

            <tbody>

                @php

                    $totalGarcons = 0;
                    $totalFilles = 0;
                    $totalEleves = 0;

                @endphp


                @forelse($effectifsClasses ?? [] as $classe)

                    @php

                        $garcons = (int) ($classe->garcons ?? 0);
                        $filles = (int) ($classe->filles ?? 0);
                        $total = (int) ($classe->total ?? 0);

                        $totalGarcons += $garcons;
                        $totalFilles += $filles;
                        $totalEleves += $total;

                    @endphp

                    <tr>

                        <td>
                            {{ $classe->libelle ?? '-' }}
                        </td>

                        <td>
                            {{ $classe->option_classe ?? '-' }}
                        </td>

                        <td class="center">
                            {{ $garcons }}
                        </td>

                        <td class="center">
                            {{ $filles }}
                        </td>

                        <td class="center">
                            {{ $total }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="empty">
                            Aucun élève inscrit pour cette année scolaire.
                        </td>

                    </tr>

                @endforelse

            </tbody>


            @if(isset($effectifsClasses) && $effectifsClasses->count())

                <tfoot>

                    <tr class="total">

                        <td colspan="2">
                            TOTAL GÉNÉRAL
                        </td>

                        <td class="center">
                            {{ $totalGarcons }}
                        </td>

                        <td class="center">
                            {{ $totalFilles }}
                        </td>

                        <td class="center">
                            {{ $totalEleves }}
                        </td>

                    </tr>

                </tfoot>

            @endif

        </table>

    </div>


    {{-- ============================================================
         SITUATION PÉDAGOGIQUE
    ============================================================ --}}

    <div class="section">

        <div class="section-title">
            Situation pédagogique
        </div>

        <table class="stats-table">

            <tr>

                <td class="stat-box">

                    <div class="stat-label">
                        Évaluations
                    </div>

                    <div class="stat-value">
                        {{ $nombreEvaluations ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Notes enregistrées
                    </div>

                    <div class="stat-value">
                        {{ $nombreNotes ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Élèves ayant des notes
                    </div>

                    <div class="stat-value">
                        {{ $elevesAvecNotes ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Moyenne générale
                    </div>

                    <div class="stat-value">
                        {{ number_format((float) ($moyenneNotes ?? 0), 2, ',', ' ') }}
                    </div>

                </td>

            </tr>

        </table>


        @if(isset($resultatsClasses) && $resultatsClasses->count())

            <div class="sub-title">
                Résultats par classe
            </div>

            <table class="data-table">

                <thead>

                    <tr>

                        <th>Classe</th>
                        <th>Nombre de notes</th>
                        <th>Moyenne</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($resultatsClasses as $resultat)

                        <tr>

                            <td>
                                {{ $resultat->libelle ?? '-' }}
                            </td>

                            <td class="center">
                                {{ $resultat->nombre_notes ?? 0 }}
                            </td>

                            <td class="center">
                                {{ number_format((float) ($resultat->moyenne ?? 0), 2, ',', ' ') }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @endif

    </div>


    {{-- ============================================================
         PERSONNEL
    ============================================================ --}}

    <div class="section">

        <div class="section-title">
            Personnel de l'établissement
        </div>


        <div class="sub-title">
            Personnel par fonction
        </div>

        @php

            $fonctionsPersonnel = collect($personnel ?? [])
                ->groupBy(function ($personne) {
                    return trim($personne->fonction ?? 'Non précisé');
                });

        @endphp


        <table class="data-table">

            <thead>

                <tr>

                    <th>Fonction</th>
                    <th>Effectif</th>

                </tr>

            </thead>

            <tbody>

                @forelse($fonctionsPersonnel as $fonction => $membres)

                    <tr>

                        <td>
                            {{ $fonction ?: 'Non précisé' }}
                        </td>

                        <td class="center">
                            {{ $membres->count() }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="2" class="empty">
                            Aucun personnel enregistré.
                        </td>

                    </tr>

                @endforelse

            </tbody>

            @if($fonctionsPersonnel->count())

                <tfoot>

                    <tr class="total">

                        <td>
                            TOTAL DU PERSONNEL
                        </td>

                        <td class="center">
                            {{ collect($personnel ?? [])->count() }}
                        </td>

                    </tr>

                </tfoot>

            @endif

        </table>


        {{-- LISTE DES ENSEIGNANTS --}}

        @php

            $listeEnseignants = collect($personnel ?? [])
                ->filter(function ($personne) {

                    return str_contains(
                        strtoupper(trim($personne->fonction ?? '')),
                        'ENSEIGNANT'
                    );

                });

        @endphp


        <div class="sub-title">
            Liste des enseignants
        </div>

        <table class="data-table">

            <thead>

                <tr>

                    <th>Matricule</th>
                    <th>Nom complet</th>
                    <th>Sexe</th>
                    <th>Qualification</th>
                    <th>Téléphone</th>
                    <th>Statut</th>

                </tr>

            </thead>

            <tbody>

                @forelse($listeEnseignants as $enseignant)

                    <tr>

                        <td>
                            {{ $enseignant->matricule ?? '-' }}
                        </td>

                        <td>

                            {{ $enseignant->nom ?? '' }}
                            {{ $enseignant->postnom ?? '' }}
                            {{ $enseignant->prenom ?? '' }}

                        </td>

                        <td class="center">
                            {{ $enseignant->sexe ?? '-' }}
                        </td>

                        <td>
                            {{ $enseignant->qualification ?? '-' }}
                        </td>

                        <td>
                            {{ $enseignant->telephone ?? '-' }}
                        </td>

                        <td class="center">
                            {{ $enseignant->statut ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="empty">
                            Aucun enseignant enregistré.
                        </td>

                    </tr>

                @endforelse

            </tbody>

            @if($listeEnseignants->count())

                <tfoot>

                    <tr class="total">

                        <td colspan="5">
                            TOTAL ENSEIGNANTS
                        </td>

                        <td class="center">
                            {{ $listeEnseignants->count() }}
                        </td>

                    </tr>

                </tfoot>

            @endif

        </table>

    </div>


    {{-- ============================================================
         FRÉQUENTATION
    ============================================================ --}}

    <div class="section">

        <div class="section-title">
            Fréquentation
        </div>

        <table class="stats-table">

            <tr>

                <td class="stat-box">

                    <div class="stat-label">
                        Présences
                    </div>

                    <div class="stat-value">
                        {{ $nombrePresences ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Absences
                    </div>

                    <div class="stat-value">
                        {{ $nombreAbsences ?? 0 }}
                    </div>

                </td>

            </tr>

        </table>


        @if(isset($frequentation) && $frequentation->count())

            <div class="sub-title">
                Détail de la fréquentation
            </div>

            <table class="data-table">

                <thead>

                    <tr>

                        <th>Statut</th>
                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($frequentation as $presence)

                        <tr>

                            <td>
                                {{ $presence->statut ?? '-' }}
                            </td>

                            <td class="center">
                                {{ $presence->total ?? 0 }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @endif

    </div>


    {{-- ============================================================
         SITUATION FINANCIÈRE
    ============================================================ --}}

    <div class="section">

        <div class="section-title">
            Situation financière
        </div>


        <div class="sub-title">
            Paiements des élèves
        </div>

        <table class="data-table">

            <thead>

                <tr>

                    <th>Devise</th>
                    <th>Nombre de paiements</th>
                    <th>Montant total</th>

                </tr>

            </thead>

            <tbody>

                @forelse($paiements ?? [] as $paiement)

                    <tr>

                        <td>
                            {{ $paiement->devise ?? '-' }}
                        </td>

                        <td class="center">
                            {{ $paiement->nombre ?? 0 }}
                        </td>

                        <td class="right">

                            {{ number_format(
                                (float) ($paiement->total ?? 0),
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="empty">
                            Aucun paiement enregistré.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        <div class="sub-title">
            Recettes
        </div>

        <table class="data-table">

            <thead>

                <tr>

                    <th>Devise</th>
                    <th>Montant total</th>

                </tr>

            </thead>

            <tbody>

                @forelse($recettes ?? [] as $recette)

                    <tr>

                        <td>
                            {{ $recette->devise ?? '-' }}
                        </td>

                        <td class="right">

                            {{ number_format(
                                (float) ($recette->total ?? 0),
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="2" class="empty">
                            Aucune recette enregistrée.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        <div class="sub-title">
            Dépenses
        </div>

        <table class="data-table">

            <thead>

                <tr>

                    <th>Devise</th>
                    <th>Montant total</th>

                </tr>

            </thead>

            <tbody>

                @forelse($depenses ?? [] as $depense)

                    <tr>

                        <td>
                            {{ $depense->devise ?? '-' }}
                        </td>

                        <td class="right">

                            {{ number_format(
                                (float) ($depense->total ?? 0),
                                2,
                                ',',
                                ' '
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="2" class="empty">
                            Aucune dépense enregistrée.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>


        <table class="data-table">

            <tr class="total">

                <td>
                    TOTAL RECETTES
                </td>

                <td class="right">

                    {{ number_format(
                        (float) ($totalRecettes ?? 0),
                        2,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>

            <tr class="total">

                <td>
                    TOTAL DÉPENSES
                </td>

                <td class="right">

                    {{ number_format(
                        (float) ($totalDepenses ?? 0),
                        2,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>

            <tr class="total">

                <td>
                    SOLDE
                </td>

                <td class="right">

                    {{ number_format(
                        (float) ($solde ?? 0),
                        2,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>

        </table>

    </div>


    {{-- ============================================================
         INVENTAIRE
    ============================================================ --}}

    <div class="section">

        <div class="section-title">
            Inventaire des biens
        </div>


        <table class="stats-table">

            <tr>

                <td class="stat-box">

                    <div class="stat-label">
                        Nombre de biens
                    </div>

                    <div class="stat-value">
                        {{ $nombreBiens ?? 0 }}
                    </div>

                </td>

                <td class="stat-box">

                    <div class="stat-label">
                        Quantité totale
                    </div>

                    <div class="stat-value">
                        {{ $quantiteBiens ?? 0 }}
                    </div>

                </td>

            </tr>

        </table>


        @if(isset($inventaire) && $inventaire->count())

            <div class="sub-title">
                Inventaire par catégorie
            </div>

            <table class="data-table">

                <thead>

                    <tr>

                        <th>Catégorie</th>
                        <th>Nombre de biens</th>
                        <th>Quantité</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($inventaire as $bien)

                        <tr>

                            <td>
                                {{ $bien->categorie ?? '-' }}
                            </td>

                            <td class="center">
                                {{ $bien->nombre ?? 0 }}
                            </td>

                            <td class="center">
                                {{ $bien->quantite ?? 0 }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @endif


        @if(isset($etatsInventaire) && $etatsInventaire->count())

            <div class="sub-title">
                État des biens
            </div>

            <table class="data-table">

                <thead>

                    <tr>

                        <th>État</th>
                        <th>Nombre</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($etatsInventaire as $etat)

                        <tr>

                            <td>
                                {{ $etat->etat ?? '-' }}
                            </td>

                            <td class="center">
                                {{ $etat->nombre ?? 0 }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @endif

    </div>


    {{-- ============================================================
         PIED DE PAGE
    ============================================================ --}}

    <div class="footer">

        Rapport annuel généré automatiquement par le système de gestion scolaire.

        <br>

        Établissement :
        {{ $etablissement->nom ?? '-' }}

        |

        Année scolaire :
        {{ $annee->libelle ?? '-' }}

        <br>

        Généré le :
        {{ date('d/m/Y à H:i') }}

    </div>


    {{-- ============================================================
         SCRIPT D'IMPRESSION AUTOMATIQUE
    ============================================================ --}}

    <script>

        window.onload = function () {

            setTimeout(function () {

                window.print();

            }, 500);

        };

    </script>

</body>

</html>
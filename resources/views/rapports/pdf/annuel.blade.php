<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Rapport annuel</title>

    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h2 {
            margin: 6px 0;
            font-size: 15px;
        }

        .header p {
            margin: 3px 0;
        }

        .section-title {
            background: #eeeeee;
            padding: 7px;
            font-weight: bold;
            font-size: 11px;
            margin-top: 16px;
            margin-bottom: 7px;
            border: 1px solid #cccccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 5px;
        }

        th {
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

        .total {
            font-weight: bold;
            background: #eeeeee;
        }

        .summary-table td {
            padding: 7px;
        }

        .label {
            font-weight: bold;
            background: #f5f5f5;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
        }

        .page-break {
            page-break-before: always;
        }

        .no-border td {
            border: none;
        }
    </style>
</head>

<body>

{{-- ============================================================
     EN-TÊTE
============================================================ --}}

<div class="header">

    @if($etablissement)

        @if(!empty($etablissement->logo))
            <img
                src="{{ public_path('storage/' . $etablissement->logo) }}"
                class="logo"
                alt="Logo"
            >
        @endif

        <h1>
            {{ $etablissement->nom }}
        </h1>

        @if(!empty($etablissement->type))
            <p>{{ $etablissement->type }}</p>
        @endif

        <p>
            {{ $etablissement->province ?? '' }}

            @if(!empty($etablissement->ville))
                — {{ $etablissement->ville }}
            @endif

            @if(!empty($etablissement->commune))
                — {{ $etablissement->commune }}
            @endif
        </p>

        @if(!empty($etablissement->telephone))
            <p>
                Tél. : {{ $etablissement->telephone }}
            </p>
        @endif

    @endif

    <h2>RAPPORT ANNUEL DE L'ÉTABLISSEMENT</h2>

    <p>
        Année scolaire :
        <strong>{{ $annee->libelle ?? '-' }}</strong>
    </p>

    @if(!empty($annee->date_debut) || !empty($annee->date_fin))
        <p>
            Période :
            {{ $annee->date_debut ?? '-' }}
            au
            {{ $annee->date_fin ?? '-' }}
        </p>
    @endif

</div>


{{-- ============================================================
     INFORMATIONS DE L'ÉTABLISSEMENT
============================================================ --}}

<div class="section-title">
    INFORMATIONS DE L'ÉTABLISSEMENT
</div>

<table class="summary-table">

    <tr>
        <td class="label">Établissement</td>
        <td>{{ $etablissement->nom ?? '-' }}</td>

        <td class="label">Code</td>
        <td>{{ $etablissement->code ?? '-' }}</td>
    </tr>

    <tr>
        <td class="label">Province</td>
        <td>{{ $etablissement->province ?? '-' }}</td>

        <td class="label">Ville</td>
        <td>{{ $etablissement->ville ?? '-' }}</td>
    </tr>

    <tr>
        <td class="label">Commune</td>
        <td>{{ $etablissement->commune ?? '-' }}</td>

        <td class="label">Directeur</td>
        <td>{{ $etablissement->directeur ?? '-' }}</td>
    </tr>

    <tr>
        <td class="label">Téléphone</td>
        <td colspan="3">
            {{ $etablissement->telephone ?? '-' }}
        </td>
    </tr>

</table>


{{-- ============================================================
     STATISTIQUES GÉNÉRALES
============================================================ --}}

<div class="section-title">
    STATISTIQUES GÉNÉRALES
</div>

<table>

    <thead>
        <tr>
            <th>Indicateur</th>
            <th>Effectif / Valeur</th>

            <th>Indicateur</th>
            <th>Effectif / Valeur</th>
        </tr>
    </thead>

    <tbody>

        <tr>
            <td>Élèves inscrits</td>
            <td class="center">
                {{ $nombreEleves ?? 0 }}
            </td>

            <td>Classes</td>
            <td class="center">
                {{ $nombreClasses ?? 0 }}
            </td>
        </tr>

        <tr>
            <td>Garçons</td>
            <td class="center">
                {{ $nombreGarcons ?? 0 }}
            </td>

            <td>Filles</td>
            <td class="center">
                {{ $nombreFilles ?? 0 }}
            </td>
        </tr>

        <tr>
            <td>Enseignants</td>
            <td class="center">
                {{ $nombreEnseignants ?? 0 }}
            </td>

            <td>Autre personnel</td>
            <td class="center">
                {{ $nombreAutrePersonnel ?? 0 }}
            </td>
        </tr>

        <tr>
            <td>Inscriptions</td>
            <td class="center">
                {{ $nombreInscriptions ?? 0 }}
            </td>

            <td>Matières</td>
            <td class="center">
                {{ $nombreMatieres ?? 0 }}
            </td>
        </tr>

        <tr>
            <td>Évaluations</td>
            <td class="center">
                {{ $nombreEvaluations ?? 0 }}
            </td>

            <td>Notes enregistrées</td>
            <td class="center">
                {{ $nombreNotes ?? 0 }}
            </td>
        </tr>

        <tr>
            <td>Moyenne générale</td>
            <td class="center">
                {{ number_format((float) ($moyenneNotes ?? 0), 2, ',', ' ') }}
            </td>

            <td>Élèves ayant des notes</td>
            <td class="center">
                {{ $elevesAvecNotes ?? 0 }}
            </td>
        </tr>

    </tbody>

</table>


{{-- ============================================================
     EFFECTIFS PAR CLASSE
============================================================ --}}

<div class="section-title">
    EFFECTIF PAR CLASSE ET PAR SEXE
</div>

<table>

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
                <td colspan="5" class="center">
                    Aucun élève inscrit pour cette année scolaire.
                </td>
            </tr>

        @endforelse

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

    </tbody>

</table>


{{-- ============================================================
     SITUATION PÉDAGOGIQUE
============================================================ --}}

<div class="section-title">
    SITUATION PÉDAGOGIQUE
</div>

<table>

    <thead>
        <tr>
            <th>Classe</th>
            <th>Nombre de notes</th>
            <th>Moyenne</th>
        </tr>
    </thead>

    <tbody>

        @forelse($resultatsClasses ?? [] as $resultat)

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

        @empty

            <tr>
                <td colspan="3" class="center">
                    Aucun résultat pédagogique enregistré.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>


{{-- ============================================================
     PERSONNEL
============================================================ --}}

<div class="section-title">
    PERSONNEL DE L'ÉTABLISSEMENT
</div>

<table>

    <thead>
        <tr>
            <th>Fonction</th>
            <th>Effectif</th>
        </tr>
    </thead>

    <tbody>

        @php
            $fonctionsPersonnel = collect($personnel ?? [])
                ->groupBy(function ($personne) {
                    return trim($personne->fonction ?? 'Non précisé');
                });
        @endphp

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
                <td colspan="2" class="center">
                    Aucun personnel enregistré.
                </td>
            </tr>

        @endforelse

        @if($fonctionsPersonnel->count())

            <tr class="total">

                <td>
                    TOTAL DU PERSONNEL
                </td>

                <td class="center">
                    {{ collect($personnel ?? [])->count() }}
                </td>

            </tr>

        @endif

    </tbody>

</table>


{{-- ============================================================
     LISTE DES ENSEIGNANTS
============================================================ --}}

<div class="section-title">
    LISTE DES ENSEIGNANTS
</div>

@php
    $listeEnseignants = collect($personnel ?? [])
        ->filter(function ($personne) {
            return str_contains(
                strtoupper(trim($personne->fonction ?? '')),
                'ENSEIGNANT'
            );
        });
@endphp

<table>

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
                <td colspan="6" class="center">
                    Aucun enseignant enregistré.
                </td>
            </tr>

        @endforelse

        @if($listeEnseignants->count())

            <tr class="total">

                <td colspan="5">
                    TOTAL ENSEIGNANTS
                </td>

                <td class="center">
                    {{ $listeEnseignants->count() }}
                </td>

            </tr>

        @endif

    </tbody>

</table>


{{-- ============================================================
     FRÉQUENTATION
============================================================ --}}

<div class="section-title">
    FRÉQUENTATION
</div>

<table>

    <thead>
        <tr>
            <th>Statut</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>

        @forelse($frequentation ?? [] as $presence)

            <tr>
                <td>
                    {{ $presence->statut ?? '-' }}
                </td>

                <td class="center">
                    {{ $presence->total ?? 0 }}
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="2" class="center">
                    Aucune donnée de fréquentation.
                </td>
            </tr>

        @endforelse

        <tr class="total">

            <td>
                Présences
            </td>

            <td class="center">
                {{ $nombrePresences ?? 0 }}
            </td>

        </tr>

        <tr class="total">

            <td>
                Absences
            </td>

            <td class="center">
                {{ $nombreAbsences ?? 0 }}
            </td>

        </tr>

    </tbody>

</table>


{{-- ============================================================
     SITUATION FINANCIÈRE
============================================================ --}}

<div class="section-title">
    SITUATION FINANCIÈRE
</div>

<table>

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
                <td colspan="3" class="center">
                    Aucun paiement enregistré.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>


<table>

    <tr class="total">
        <td>Recettes</td>
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
        <td>Dépenses</td>
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
        <td>Solde</td>
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


{{-- ============================================================
     RECETTES
============================================================ --}}

@if(isset($recettes) && $recettes->count())

    <div class="section-title">
        RECETTES PAR DEVISE
    </div>

    <table>

        <thead>
            <tr>
                <th>Devise</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            @foreach($recettes as $recette)

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

            @endforeach

        </tbody>

    </table>

@endif


{{-- ============================================================
     DÉPENSES
============================================================ --}}

@if(isset($depenses) && $depenses->count())

    <div class="section-title">
        DÉPENSES PAR DEVISE
    </div>

    <table>

        <thead>
            <tr>
                <th>Devise</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            @foreach($depenses as $depense)

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

            @endforeach

        </tbody>

    </table>

@endif


{{-- ============================================================
     INVENTAIRE
============================================================ --}}

<div class="section-title">
    INVENTAIRE DES BIENS
</div>

<table>

    <thead>

        <tr>
            <th>Catégorie</th>
            <th>Nombre de biens</th>
            <th>Quantité</th>
        </tr>

    </thead>

    <tbody>

        @forelse($inventaire ?? [] as $bien)

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

        @empty

            <tr>
                <td colspan="3" class="center">
                    Aucun bien enregistré dans l'inventaire.
                </td>
            </tr>

        @endforelse

        <tr class="total">

            <td>
                TOTAL
            </td>

            <td class="center">
                {{ $nombreBiens ?? 0 }}
            </td>

            <td class="center">
                {{ $quantiteBiens ?? 0 }}
            </td>

        </tr>

    </tbody>

</table>


{{-- ============================================================
     ÉTAT DE L'INVENTAIRE
============================================================ --}}

@if(isset($etatsInventaire) && $etatsInventaire->count())

    <div class="section-title">
        ÉTAT DES BIENS
    </div>

    <table>

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


{{-- ============================================================
     PIED DE PAGE
============================================================ --}}

<div class="footer">

    Rapport généré le {{ date('d/m/Y à H:i') }}

</div>

</body>
</html>
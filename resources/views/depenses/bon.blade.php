<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
    Bon de sortie - Dépense #{{ $depense->id_depense }}
</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 30px;
        background: #f3f4f6;
        font-family: Arial, Helvetica, sans-serif;
        color: #1f2937;
    }

    .document {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
    }

    .entete {
        text-align: center;
        border-bottom: 2px solid #374151;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .etablissement {
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .titre {
        font-size: 20px;
        font-weight: bold;
        margin-top: 15px;
    }

    .numero {
        margin-top: 8px;
        font-size: 14px;
        color: #6b7280;
    }

    .informations {
        margin-top: 25px;
    }

    .ligne {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        padding: 12px 0;
    }

    .libelle {
        width: 35%;
        font-weight: bold;
        color: #4b5563;
    }

    .valeur {
        width: 65%;
    }

    .montant {
        margin: 30px 0;
        padding: 20px;
        border: 2px solid #374151;
        text-align: center;
    }

    .montant-label {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .montant-valeur {
        font-size: 30px;
        font-weight: bold;
    }

    .description {
        margin-top: 25px;
        padding: 15px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
    }

    .description-titre {
        font-weight: bold;
        margin-bottom: 8px;
    }

    .signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 70px;
        text-align: center;
    }

    .signature {
        width: 30%;
    }

    .signature-ligne {
        margin-top: 60px;
        border-top: 1px solid #374151;
        padding-top: 8px;
    }

    .pied {
        margin-top: 40px;
        padding-top: 15px;
        border-top: 1px solid #d1d5db;
        text-align: center;
        font-size: 12px;
        color: #6b7280;
    }

    .actions {
        max-width: 800px;
        margin: 20px auto;
        text-align: right;
    }

    .btn {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-imprimer {
        background: #374151;
        color: white;
    }

    .btn-retour {
        background: #e5e7eb;
        color: #374151;
        margin-right: 8px;
    }

    @media print {

        body {
            background: white;
            padding: 0;
        }

        .document {
            max-width: none;
            box-shadow: none;
            padding: 20px;
        }

        .actions {
            display: none;
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    }
</style>


</head>

<body>


<div class="actions">

    <a href="{{ route('depenses.index') }}"
       class="btn btn-retour">

        Retour

    </a>

    <button
        type="button"
        onclick="window.print()"
        class="btn btn-imprimer">

        🖨️ Imprimer le bon

    </button>

</div>


<div class="document">

    {{-- ===================================================== --}}
    {{-- ENTÊTE --}}
    {{-- ===================================================== --}}

    <div class="entete">

        <div class="etablissement">

            {{ $depense->etablissement->nom
                ?? 'ÉTABLISSEMENT SCOLAIRE' }}

        </div>

        <div class="titre">

            BON DE SORTIE DE CAISSE

        </div>

        <div class="numero">

            N° DÉPENSE :

            <strong>
                {{ $depense->id_depense }}
            </strong>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- INFORMATIONS --}}
    {{-- ===================================================== --}}

    <div class="informations">

        <div class="ligne">

            <div class="libelle">
                Date
            </div>

            <div class="valeur">

                {{ $depense->date_depense
                    ? \Carbon\Carbon::parse($depense->date_depense)->format('d/m/Y H:i')
                    : '-' }}

            </div>

        </div>


        <div class="ligne">

            <div class="libelle">
                Catégorie
            </div>

            <div class="valeur">

                {{ $depense->categorie }}

            </div>

        </div>


        <div class="ligne">

            <div class="libelle">
                Année scolaire
            </div>

            <div class="valeur">

                {{ $depense->anneeScolaire->libelle
                    ?? $depense->anneeScolaire->nom
                    ?? '-' }}

            </div>

        </div>


        <div class="ligne">

            <div class="libelle">
                Enregistré par
            </div>

            <div class="valeur">

                {{ $depense->utilisateur->nom
                    ?? $depense->utilisateur->name
                    ?? '-' }}

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- MONTANT --}}
    {{-- ===================================================== --}}

    <div class="montant">

        <div class="montant-label">
            MONTANT SORTI DE CAISSE
        </div>

        <div class="montant-valeur">

            {{ number_format(
                $depense->montant,
                2,
                ',',
                ' '
            ) }}

            {{ $depense->devise }}

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- DESCRIPTION --}}
    {{-- ===================================================== --}}

    @if($depense->description)

        <div class="description">

            <div class="description-titre">
                Motif / Description
            </div>

            <div>
                {{ $depense->description }}
            </div>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- SIGNATURES --}}
    {{-- ===================================================== --}}

    <div class="signatures">

        <div class="signature">

            Demandeur

            <div class="signature-ligne">
                Signature
            </div>

        </div>


        <div class="signature">

            Caissier

            <div class="signature-ligne">
                Signature
            </div>

        </div>


        <div class="signature">

            Responsable

            <div class="signature-ligne">
                Signature et cachet
            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- PIED --}}
    {{-- ===================================================== --}}

    <div class="pied">

        Document généré par le système de gestion scolaire.

        <br>

        Date d'impression :
        {{ now()->format('d/m/Y H:i') }}

    </div>

</div>

</body>

</html>

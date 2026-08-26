<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails de l'activité
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Consultation détaillée du journal d'activité
            </p>
        </div>

        <a href="{{ route('journaux-activites.index') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            Retour au journal
        </a>
    </div>
</x-slot>


<div class="py-6">

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        @php
            /*
            |--------------------------------------------------------------------------
            | Décodage des valeurs JSON
            |--------------------------------------------------------------------------
            */

            $anciennesValeurs = [];

            if ($journal->anciennes_valeurs) {
                $anciennesValeurs = json_decode(
                    $journal->anciennes_valeurs,
                    true
                ) ?? [];
            }

            $nouvellesValeurs = [];

            if ($journal->nouvelles_valeurs) {
                $nouvellesValeurs = json_decode(
                    $journal->nouvelles_valeurs,
                    true
                ) ?? [];
            }


            /*
            |--------------------------------------------------------------------------
            | Recherche des informations liées
            |--------------------------------------------------------------------------
            */

            $eleve = null;
            $matiere = null;
            $evaluation = null;
            $classe = null;
            $periode = null;
            $inscription = null;
            $paiement = null;
            $tarif = null;
            $categorie = null;


            /*
            |--------------------------------------------------------------------------
            | Élève
            |--------------------------------------------------------------------------
            */

            if ($journal->table_concernee === 'eleves') {

                $idEleve =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_eleve'] ?? null)
                    ?? ($anciennesValeurs['id_eleve'] ?? null);

                if ($idEleve) {
                    $eleve = \App\Models\Eleve::find($idEleve);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Matière
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'matieres') {

                $idMatiere =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_matiere'] ?? null)
                    ?? ($anciennesValeurs['id_matiere'] ?? null);

                if ($idMatiere) {
                    $matiere = \App\Models\Matiere::find($idMatiere);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Évaluation
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'evaluations') {

                $idEvaluation =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_evaluation'] ?? null)
                    ?? ($anciennesValeurs['id_evaluation'] ?? null);

                if ($idEvaluation) {
                    $evaluation = \App\Models\Evaluation::with([
                        'matiere',
                        'classe',
                        'periode',
                    ])->find($idEvaluation);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Note
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'notes') {

                $idNote =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_note'] ?? null)
                    ?? ($anciennesValeurs['id_note'] ?? null);

                if ($idNote) {
                    $noteObjet = \App\Models\Note::with([
                        'eleve',
                        'evaluation.matiere',
                        'evaluation.classe',
                        'evaluation.periode',
                    ])->find($idNote);

                    if ($noteObjet) {
                        $eleve = $noteObjet->eleve;
                        $evaluation = $noteObjet->evaluation;

                        if ($evaluation) {
                            $matiere = $evaluation->matiere;
                            $classe = $evaluation->classe;
                            $periode = $evaluation->periode;
                        }
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Classe
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'classes') {

                $idClasse =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_classe'] ?? null)
                    ?? ($anciennesValeurs['id_classe'] ?? null);

                if ($idClasse) {
                    $classe = \App\Models\Classe::find($idClasse);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Période scolaire
            |--------------------------------------------------------------------------
            */

            elseif (
                $journal->table_concernee === 'periodes_scolaires'
            ) {

                $idPeriode =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_periode'] ?? null)
                    ?? ($anciennesValeurs['id_periode'] ?? null)
                    ?? ($nouvellesValeurs['id_periode_scolaire'] ?? null)
                    ?? ($anciennesValeurs['id_periode_scolaire'] ?? null);

                if ($idPeriode) {
                    $periode = \App\Models\PeriodeScolaire::find($idPeriode);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Inscription
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'inscriptions') {

                $idInscription =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_inscription'] ?? null)
                    ?? ($anciennesValeurs['id_inscription'] ?? null);

                if ($idInscription) {
                    $inscription = \App\Models\Inscription::with([
                        'eleve',
                        'classe',
                        'anneeScolaire',
                    ])->find($idInscription);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Paiement
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'paiements') {

                $idPaiement =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_paiement'] ?? null)
                    ?? ($anciennesValeurs['id_paiement'] ?? null);

                if ($idPaiement) {
                    $paiement = \App\Models\Paiement::with([
                        'eleve',
                    ])->find($idPaiement);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Tarif scolaire
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'tarifs_scolaires') {

                $idTarif =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_tarif'] ?? null)
                    ?? ($anciennesValeurs['id_tarif'] ?? null)
                    ?? ($nouvellesValeurs['id_tarif_scolaire'] ?? null)
                    ?? ($anciennesValeurs['id_tarif_scolaire'] ?? null);

                if ($idTarif) {
                    $tarif = \App\Models\TarifScolaire::with([
                        'anneeScolaire',
                        'classe',
                        'categorieFrais',
                    ])->find($idTarif);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Catégorie de frais
            |--------------------------------------------------------------------------
            */

            elseif ($journal->table_concernee === 'categories_frais') {

                $idCategorie =
                    $journal->id_enregistrement
                    ?? ($nouvellesValeurs['id_categorie'] ?? null)
                    ?? ($anciennesValeurs['id_categorie'] ?? null)
                    ?? ($nouvellesValeurs['id_categorie_frais'] ?? null)
                    ?? ($anciennesValeurs['id_categorie_frais'] ?? null);

                if ($idCategorie) {
                    $categorie = \App\Models\CategorieFrais::find($idCategorie);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Nom de l'enregistrement
            |--------------------------------------------------------------------------
            */

            $nomEnregistrement = null;


            if ($eleve) {

                $nomEnregistrement = trim(
                    $eleve->nom . ' ' .
                    ($eleve->postnom ?? '') . ' ' .
                    ($eleve->prenom ?? '')
                );

            } elseif ($matiere) {

                $nomEnregistrement =
                    ($matiere->code ?? '') .
                    ' — ' .
                    ($matiere->libelle ?? '');

            } elseif ($evaluation) {

                $nomEnregistrement =
                    $evaluation->libelle ?? 'Évaluation';

            } elseif ($classe) {

                $nomEnregistrement =
                    $classe->libelle ?? 'Classe';

            } elseif ($periode) {

                $nomEnregistrement =
                    $periode->libelle ?? 'Période';

            } elseif ($inscription) {

                $nomEnregistrement = $inscription->eleve
                    ? trim(
                        $inscription->eleve->nom . ' ' .
                        ($inscription->eleve->postnom ?? '') . ' ' .
                        ($inscription->eleve->prenom ?? '')
                    )
                    : 'Inscription';

            } elseif ($paiement) {

                $nomEnregistrement = $paiement->numero_recu
                    ?? 'Paiement';

            } elseif ($tarif) {

                $nomEnregistrement = $tarif->categorieFrais->libelle
                    ?? 'Tarif scolaire';

            } elseif ($categorie) {

                $nomEnregistrement =
                    $categorie->libelle ?? 'Catégorie de frais';
            }


            /*
            |--------------------------------------------------------------------------
            | Fonction d'affichage des valeurs
            |--------------------------------------------------------------------------
            */

            $afficherValeur = function ($cle, $valeur) use (
                $eleve,
                $matiere,
                $evaluation,
                $classe,
                $periode,
                $inscription,
                $paiement,
                $tarif,
                $categorie
            ) {

                if (is_null($valeur) || $valeur === '') {
                    return '—';
                }


                /*
                |--------------------------------------------------------------------------
                | ID élève
                |--------------------------------------------------------------------------
                */

                if (
                    in_array($cle, [
                        'id_eleve',
                    ])
                ) {

                    if ($eleve) {

                        return trim(
                            $eleve->nom . ' ' .
                            ($eleve->postnom ?? '') . ' ' .
                            ($eleve->prenom ?? '')
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID matière
                |--------------------------------------------------------------------------
                */

                if (
                    in_array($cle, [
                        'id_matiere',
                    ])
                ) {

                    if ($matiere) {
                        return $matiere->libelle ?? $valeur;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID évaluation
                |--------------------------------------------------------------------------
                */

                if (
                    in_array($cle, [
                        'id_evaluation',
                    ])
                ) {

                    if ($evaluation) {
                        return $evaluation->libelle ?? $valeur;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID classe
                |--------------------------------------------------------------------------
                */

                if (
                    in_array($cle, [
                        'id_classe',
                    ])
                ) {

                    if ($classe) {
                        return $classe->libelle ?? $valeur;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID période
                |--------------------------------------------------------------------------
                */

                if (
                    in_array($cle, [
                        'id_periode',
                        'id_periode_scolaire',
                    ])
                ) {

                    if ($periode) {
                        return $periode->libelle ?? $valeur;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID inscription
                |--------------------------------------------------------------------------
                */

                if (
                    $cle === 'id_inscription'
                ) {

                    if ($inscription) {
                        return 'Inscription #' .
                            $inscription->id_inscription;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID paiement
                |--------------------------------------------------------------------------
                */

                if (
                    $cle === 'id_paiement'
                ) {

                    if ($paiement) {

                        return $paiement->numero_recu
                            ?? 'Paiement #' . $valeur;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID tarif
                |--------------------------------------------------------------------------
                */

                if (
                    in_array($cle, [
                        'id_tarif',
                        'id_tarif_scolaire',
                    ])
                ) {

                    if ($tarif) {
                        return 'Tarif scolaire';
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ID catégorie
                |--------------------------------------------------------------------------
                */

                if (
                    in_array($cle, [
                        'id_categorie',
                        'id_categorie_frais',
                    ])
                ) {

                    if ($categorie) {
                        return $categorie->libelle ?? $valeur;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Statuts
                |--------------------------------------------------------------------------
                */

                if ($cle === 'statut') {

                    return match (strtoupper((string) $valeur)) {
                        'ACTIF' => 'Actif',
                        'INACTIF' => 'Inactif',
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                        default => $valeur,
                    };
                }


                /*
                |--------------------------------------------------------------------------
                | Date
                |--------------------------------------------------------------------------
                */

                if (
                    str_contains($cle, 'date')
                    && is_string($valeur)
                ) {

                    try {

                        return \Carbon\Carbon::parse($valeur)
                            ->format('d/m/Y H:i');

                    } catch (\Throwable $e) {
                        return $valeur;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Booléen
                |--------------------------------------------------------------------------
                */

                if (is_bool($valeur)) {
                    return $valeur ? 'Oui' : 'Non';
                }


                /*
                |--------------------------------------------------------------------------
                | Tableau / JSON
                |--------------------------------------------------------------------------
                */

                if (is_array($valeur)) {

                    return json_encode(
                        $valeur,
                        JSON_UNESCAPED_UNICODE |
                        JSON_PRETTY_PRINT
                    );
                }


                return $valeur;
            };


            /*
            |--------------------------------------------------------------------------
            | Traduction des noms de champs
            |--------------------------------------------------------------------------
            */

            $labels = [
                'id_note' => 'ID de la note',
                'id_eleve' => 'Élève',
                'id_evaluation' => 'Évaluation',
                'id_matiere' => 'Matière',
                'id_classe' => 'Classe',
                'id_periode' => 'Période',
                'id_periode_scolaire' => 'Période scolaire',
                'id_inscription' => 'Inscription',
                'id_paiement' => 'Paiement',
                'id_tarif' => 'Tarif scolaire',
                'id_tarif_scolaire' => 'Tarif scolaire',
                'id_categorie' => 'Catégorie',
                'id_categorie_frais' => 'Catégorie de frais',
                'note' => 'Note',
                'appreciation' => 'Appréciation',
                'nom' => 'Nom',
                'postnom' => 'Postnom',
                'prenom' => 'Prénom',
                'matricule' => 'Matricule',
                'sexe' => 'Sexe',
                'email' => 'E-mail',
                'telephone' => 'Téléphone',
                'adresse' => 'Adresse',
                'lieu_naissance' => 'Lieu de naissance',
                'date_naissance' => 'Date de naissance',
                'date_creation' => 'Date de création',
                'date_modification' => 'Date de modification',
                'libelle' => 'Libellé',
                'code' => 'Code',
                'coefficient' => 'Coefficient',
                'statut' => 'Statut',
                'note_maximale' => 'Note maximale',
                'type_evaluation' => 'Type d’évaluation',
                'date_evaluation' => 'Date d’évaluation',
            ];
        @endphp


        {{-- ========================================================= --}}
        {{-- INFORMATIONS GÉNÉRALES --}}
        {{-- ========================================================= --}}

        <div class="bg-white shadow-sm sm:rounded-lg mb-6">

            <div class="p-6">

                <div class="flex items-start justify-between">

                    <div>

                        <h1 class="text-2xl font-bold text-gray-800">
                            {{ $journal->action }}
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Activité #{{ $journal->id_journal }}
                        </p>

                    </div>


                    @if($journal->table_concernee)

                        <span class="px-3 py-1 rounded-full
                                     bg-gray-100 text-gray-700
                                     text-sm font-medium">

                            {{ ucfirst(str_replace(
                                '_',
                                ' ',
                                $journal->table_concernee
                            )) }}

                        </span>

                    @endif

                </div>


                @if($nomEnregistrement)

                    <div class="mt-5 p-4 bg-gray-50 rounded-lg">

                        <p class="text-xs uppercase font-semibold text-gray-500">
                            Enregistrement concerné
                        </p>

                        <p class="mt-1 text-lg font-semibold text-gray-800">
                            {{ $nomEnregistrement }}
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- UTILISATEUR ET DATE --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">


            <div class="bg-white shadow-sm sm:rounded-lg p-5">

                <p class="text-xs uppercase font-semibold text-gray-500">
                    Utilisateur
                </p>

                <p class="mt-2 font-semibold text-gray-800">

                    {{ $journal->utilisateur?->nom
                        ?? 'Utilisateur inconnu' }}

                </p>

                @if($journal->utilisateur?->email)

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $journal->utilisateur->email }}
                    </p>

                @endif

            </div>

            

            <div class="bg-white shadow-sm sm:rounded-lg p-5">

                <p class="text-xs uppercase font-semibold text-gray-500">
                    Date et heure
                </p>

                <p class="mt-2 font-semibold text-gray-800">

                    {{ $journal->date_heure
                        ? $journal->date_heure->format('d/m/Y H:i:s')
                        : '—' }}

                </p>

            </div>


            <div class="bg-white shadow-sm sm:rounded-lg p-5">

                <p class="text-xs uppercase font-semibold text-gray-500">
                    Adresse IP
                </p>

                <p class="mt-2 font-semibold text-gray-800">

                    {{ $journal->adresse_ip ?? '—' }}

                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMATIONS DE L'ENREGISTREMENT --}}
        {{-- ========================================================= --}}

        <div class="bg-white shadow-sm sm:rounded-lg mb-6">

            <div class="p-6">

                <h2 class="text-lg font-bold text-gray-800 mb-5">
                    Informations de l'enregistrement
                </h2>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-xs uppercase font-semibold text-gray-500">
                            Table concernée
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $journal->table_concernee ?? '—' }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-xs uppercase font-semibold text-gray-500">
                            ID de l'enregistrement
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            #{{ $journal->id_enregistrement ?? '—' }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-xs uppercase font-semibold text-gray-500">
                            ID du journal
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            #{{ $journal->id_journal }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- NOTES : AFFICHAGE INTELLIGENT --}}
        {{-- ========================================================= --}}

        @if($journal->table_concernee === 'notes')

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-5">
                        Détails de la note
                    </h2>


                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Élève
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                @if($eleve)

                                    {{ trim(
                                        $eleve->nom . ' ' .
                                        ($eleve->postnom ?? '') . ' ' .
                                        ($eleve->prenom ?? '')
                                    ) }}

                                @else

                                    Élève #{{ $nouvellesValeurs['id_eleve'] ?? '—' }}

                                @endif

                            </p>

                        </div>


                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Matière
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $matiere?->libelle ?? '—' }}

                            </p>

                        </div>


                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Évaluation
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $evaluation?->libelle ?? '—' }}

                            </p>

                        </div>


                        <div class="bg-gray-50 rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Note
                            </p>

                            <p class="mt-1 text-xl font-bold text-gray-800">

                                {{ $nouvellesValeurs['note'] ?? '—' }}

                                @if($evaluation?->note_maximale)

                                    <span class="text-sm font-normal text-gray-500">
                                        /
                                        {{ $evaluation->note_maximale }}
                                    </span>

                                @endif

                            </p>

                        </div>

                    </div>


                    @if(array_key_exists('appreciation', $nouvellesValeurs))

                        <div class="mt-5 bg-gray-50 rounded-lg p-4">

                            <p class="text-xs uppercase font-semibold text-gray-500">
                                Appréciation
                            </p>

                            <p class="mt-1 text-gray-800">

                                {{ $nouvellesValeurs['appreciation'] ?? '—' }}

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ANCIENNES / NOUVELLES VALEURS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">


            {{-- ANCIENNES VALEURS --}}

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">

                        <h2 class="text-lg font-bold text-gray-800">
                            Anciennes valeurs
                        </h2>

                        @if(count($anciennesValeurs) > 0)

                            <span class="px-2 py-1 bg-yellow-100
                                         text-yellow-800 rounded-full
                                         text-xs font-semibold">

                                Avant

                            </span>

                        @endif

                    </div>


                    @if(count($anciennesValeurs) > 0)

                        <div class="space-y-3">

                            @foreach($anciennesValeurs as $cle => $valeur)

                                <div class="border-b border-gray-100 pb-3">

                                    <p class="text-xs font-semibold
                                              uppercase text-gray-500">

                                        {{ $labels[$cle] ?? ucfirst(
                                            str_replace('_', ' ', $cle)
                                        ) }}

                                    </p>

                                    <p class="mt-1 text-sm text-gray-800 break-words">

                                        {{ $afficherValeur($cle, $valeur) }}

                                    </p>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-8">

                            <p class="text-gray-400 text-sm">
                                Aucune ancienne valeur.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- NOUVELLES VALEURS --}}

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">

                        <h2 class="text-lg font-bold text-gray-800">
                            Nouvelles valeurs
                        </h2>

                        @if(count($nouvellesValeurs) > 0)

                            <span class="px-2 py-1 bg-green-100
                                         text-green-800 rounded-full
                                         text-xs font-semibold">

                                Après

                            </span>

                        @endif

                    </div>


                    @if(count($nouvellesValeurs) > 0)

                        <div class="space-y-3">

                            @foreach($nouvellesValeurs as $cle => $valeur)

                                <div class="border-b border-gray-100 pb-3">

                                    <p class="text-xs font-semibold
                                              uppercase text-gray-500">

                                        {{ $labels[$cle] ?? ucfirst(
                                            str_replace('_', ' ', $cle)
                                        ) }}

                                    </p>

                                    <p class="mt-1 text-sm text-gray-800 break-words">

                                        {{ $afficherValeur($cle, $valeur) }}

                                    </p>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-8">

                            <p class="text-gray-400 text-sm">
                                Aucune nouvelle valeur.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MODIFICATION : COMPARAISON AVANT / APRÈS --}}
        {{-- ========================================================= --}}

        @if(
            count($anciennesValeurs) > 0 &&
            count($nouvellesValeurs) > 0
        )

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-5">
                        Comparaison des modifications
                    </h2>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs
                                               font-semibold text-gray-600 uppercase">
                                        Champ
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                               font-semibold text-gray-600 uppercase">
                                        Avant
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs
                                               font-semibold text-gray-600 uppercase">
                                        Après
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach(
                                    array_unique(
                                        array_merge(
                                            array_keys($anciennesValeurs),
                                            array_keys($nouvellesValeurs)
                                        )
                                    ) as $cle
                                )

                                    @php
                                        $ancienne =
                                            $anciennesValeurs[$cle]
                                            ?? null;

                                        $nouvelle =
                                            $nouvellesValeurs[$cle]
                                            ?? null;

                                        $modifie =
                                            (string) $ancienne !==
                                            (string) $nouvelle;
                                    @endphp


                                    <tr class="{{ $modifie ? 'bg-yellow-50' : '' }}">

                                        <td class="px-4 py-3 text-sm font-semibold text-gray-700">

                                            {{ $labels[$cle] ?? ucfirst(
                                                str_replace('_', ' ', $cle)
                                            ) }}

                                        </td>


                                        <td class="px-4 py-3 text-sm">

                                            <span class="{{ $modifie
                                                ? 'text-red-700'
                                                : 'text-gray-700' }}">

                                                {{ $afficherValeur(
                                                    $cle,
                                                    $ancienne
                                                ) }}

                                            </span>

                                        </td>


                                        <td class="px-4 py-3 text-sm">

                                            <span class="{{ $modifie
                                                ? 'text-green-700 font-semibold'
                                                : 'text-gray-700' }}">

                                                {{ $afficherValeur(
                                                    $cle,
                                                    $nouvelle
                                                ) }}

                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- INFORMATIONS TECHNIQUES --}}
        {{-- ========================================================= --}}

        <div class="bg-white shadow-sm sm:rounded-lg mb-6">

            <div class="p-6">

                <h2 class="text-lg font-bold text-gray-800 mb-5">
                    Informations techniques
                </h2>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-xs uppercase font-semibold text-gray-500">
                            Adresse IP
                        </p>

                        <p class="mt-1 text-sm text-gray-800">
                            {{ $journal->adresse_ip ?? '—' }}
                        </p>

                    </div>


                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-xs uppercase font-semibold text-gray-500">
                            Navigateur
                        </p>

                        <p class="mt-1 text-sm text-gray-800 break-words">
                            {{ $journal->navigateur ?? '—' }}
                        </p>

                    </div>


                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BOUTON RETOUR --}}
        {{-- ========================================================= --}}

        <div class="flex justify-end">

            <a href="{{ route('journaux-activites.index') }}"
               class="px-5 py-2.5 bg-gray-600 text-white
                      rounded-lg hover:bg-gray-700">

                ← Retour au journal

            </a>

        </div>

    </div>

</div>

</x-app-layout>

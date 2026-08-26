<x-app-layout>

    {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}
    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Rapport annuel
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Rapport annuel de l'établissement
                </p>
            </div>

        </div>

    </x-slot>


    {{-- ============================================================
         CONTENU
    ============================================================ --}}
    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ====================================================
                 BOUTONS D'ACTION
            ==================================================== --}}
            <div class="flex flex-wrap justify-end gap-2 mb-6">

                <a href="{{ route('rapports.annuel.pdf') }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">

                    <span class="mr-2">📄</span>
                    PDF

                </a>


                <a href="{{ route('rapports.annuel.imprimer') }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-900 transition">

                    <span class="mr-2">🖨️</span>
                    IMPRIMER

                </a>


                <a href="{{ route('rapports.annuel.excel') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition">

                    <span class="mr-2">📊</span>
                    EXCEL

                </a>

            </div>



            {{-- ====================================================
                 INFORMATIONS DE L'ÉTABLISSEMENT
            ==================================================== --}}
            @if(isset($etablissement) && $etablissement)

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                    <div class="flex items-center mb-6">

                        @if(!empty($etablissement->logo))

                            <img
                                src="{{ asset('storage/' . $etablissement->logo) }}"
                                alt="Logo"
                                class="h-16 w-16 object-contain mr-4"
                            >

                        @endif

                        <div>

                            <h3 class="text-xl font-bold text-gray-800">
                                {{ $etablissement->nom ?? 'Établissement' }}
                            </h3>

                            @if(!empty($etablissement->type))

                                <p class="text-sm text-gray-500">
                                    {{ $etablissement->type }}
                                </p>

                            @endif

                        </div>

                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                        <div>
                            <p class="text-xs text-gray-500 uppercase">
                                Code
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ $etablissement->code ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-500 uppercase">
                                Province
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ $etablissement->province ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-500 uppercase">
                                Ville
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ $etablissement->ville ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-500 uppercase">
                                Commune
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ $etablissement->commune ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-500 uppercase">
                                Téléphone
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ $etablissement->telephone ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-500 uppercase">
                                Directeur
                            </p>

                            <p class="font-medium text-gray-800 mt-1">
                                {{ $etablissement->directeur ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

            @endif



            {{-- ====================================================
                 ANNÉE SCOLAIRE
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>

                        <p class="text-sm text-gray-500">
                            Année scolaire
                        </p>

                        <h3 class="text-2xl font-bold text-gray-800 mt-1">

                            {{ $annee->libelle ?? 'Année scolaire active' }}

                        </h3>

                    </div>


                    @if(isset($annee))

                        <div class="text-left md:text-right">

                            <p class="text-sm text-gray-500">
                                Période scolaire
                            </p>

                            <p class="font-medium text-gray-700 mt-1">

                                {{ $annee->date_debut ?? '-' }}

                                <span class="mx-1">→</span>

                                {{ $annee->date_fin ?? '-' }}

                            </p>

                        </div>

                    @endif

                </div>

            </div>



            {{-- ====================================================
                 RÉSUMÉ GÉNÉRAL
            ==================================================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">


                {{-- Élèves --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Élèves inscrits
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $nombreEleves ?? 0 }}
                    </p>

                </div>


                {{-- Garçons --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Garçons
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $nombreGarcons ?? 0 }}
                    </p>

                </div>


                {{-- Filles --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Filles
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $nombreFilles ?? 0 }}
                    </p>

                </div>


                {{-- Classes --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

                    <p class="text-sm text-gray-500">
                        Classes
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $nombreClasses ?? 0 }}
                    </p>

                </div>

            </div>



            {{-- ====================================================
                 EFFECTIFS PAR CLASSE
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <div class="mb-5">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Effectif par classe et par sexe
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Répartition des élèves inscrits pendant l'année scolaire
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold text-gray-700">
                                    Classe
                                </th>

                                <th class="text-left px-4 py-3 font-semibold text-gray-700">
                                    Option
                                </th>

                                <th class="text-center px-4 py-3 font-semibold text-gray-700">
                                    Garçons
                                </th>

                                <th class="text-center px-4 py-3 font-semibold text-gray-700">
                                    Filles
                                </th>

                                <th class="text-center px-4 py-3 font-semibold text-gray-700">
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($effectifsClasses ?? [] as $classe)

                                <tr class="border-b hover:bg-gray-50">

                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        {{ $classe->libelle ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $classe->option_classe ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $classe->garcons ?? 0 }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $classe->filles ?? 0 }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-semibold">
                                        {{ $classe->total ?? 0 }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="px-4 py-8 text-center text-gray-500">

                                        Aucun élève inscrit pour cette année scolaire.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>


                        @if(isset($effectifsClasses) && $effectifsClasses->count())

                            <tfoot>

                                <tr class="bg-gray-100 border-t-2 font-bold">

                                    <td colspan="2"
                                        class="px-4 py-3">

                                        TOTAL GÉNÉRAL

                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $nombreGarcons ?? 0 }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $nombreFilles ?? 0 }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $nombreEleves ?? 0 }}
                                    </td>

                                </tr>

                            </tfoot>

                        @endif

                    </table>

                </div>

            </div>



            {{-- ====================================================
                 INSCRIPTIONS
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Situation des inscriptions
                </h3>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Total des élèves
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $nombreEleves ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Inscriptions validées
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $nombreInscriptions ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Classes ouvertes
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $nombreClasses ?? 0 }}
                        </p>

                    </div>

                </div>

            </div>



            {{-- ====================================================
                 PERSONNEL
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <div class="mb-5">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Personnel de l'établissement
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Répartition du personnel et liste des enseignants
                    </p>

                </div>


                {{-- Résumé --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Personnel total
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ collect($personnel ?? [])->count() }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Enseignants actifs
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $nombreEnseignants ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Autre personnel actif
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $nombreAutrePersonnel ?? 0 }}
                        </p>

                    </div>

                </div>



                {{-- Personnel par fonction --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    Répartition par fonction
                </h4>


                @php
                    $fonctionsPersonnel = collect($personnel ?? [])
                        ->groupBy(function ($personne) {
                            return trim($personne->fonction ?? 'Non précisé');
                        });
                @endphp


                <div class="overflow-x-auto mb-8">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Fonction
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Effectif
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($fonctionsPersonnel as $fonction => $membres)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $fonction ?: 'Non précisé' }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-semibold">
                                        {{ $membres->count() }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="2"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucun personnel enregistré.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>



                {{-- Liste des enseignants --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    Liste des enseignants
                </h4>


                @php
                    $listeEnseignants = collect($personnel ?? [])
                        ->filter(function ($personne) {

                            return str_contains(
                                strtoupper(trim($personne->fonction ?? '')),
                                'ENSEIGNANT'
                            );

                        });
                @endphp


                <div class="overflow-x-auto">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Matricule
                                </th>

                                <th class="text-left px-4 py-3 font-semibold">
                                    Nom complet
                                </th>

                                <th class="text-left px-4 py-3 font-semibold">
                                    Sexe
                                </th>

                                <th class="text-left px-4 py-3 font-semibold">
                                    Qualification
                                </th>

                                <th class="text-left px-4 py-3 font-semibold">
                                    Téléphone
                                </th>

                                <th class="text-left px-4 py-3 font-semibold">
                                    Statut
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($listeEnseignants as $enseignant)

                                <tr class="border-b hover:bg-gray-50">

                                    <td class="px-4 py-3">
                                        {{ $enseignant->matricule ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 font-medium">
                                        {{ $enseignant->nom ?? '' }}
                                        {{ $enseignant->postnom ?? '' }}
                                        {{ $enseignant->prenom ?? '' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $enseignant->sexe ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $enseignant->qualification ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $enseignant->telephone ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $enseignant->statut ?? '-' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="px-4 py-8 text-center text-gray-500">

                                        Aucun enseignant enregistré.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>


                        @if($listeEnseignants->count())

                            <tfoot>

                                <tr class="bg-gray-100 border-t-2 font-bold">

                                    <td colspan="5" class="px-4 py-3">
                                        TOTAL ENSEIGNANTS
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $listeEnseignants->count() }}
                                    </td>

                                </tr>

                            </tfoot>

                        @endif

                    </table>

                </div>

            </div>



            {{-- ====================================================
                 FRÉQUENTATION
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Fréquentation scolaire
                </h3>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Présences
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $nombrePresences ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Absences
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $nombreAbsences ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Total des enregistrements
                        </p>

                        <p class="text-2xl font-bold text-gray-800 mt-2">
                            {{ ($nombrePresences ?? 0) + ($nombreAbsences ?? 0) }}
                        </p>

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Statut
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Nombre
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($frequentation ?? [] as $presence)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $presence->statut ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-semibold">
                                        {{ $presence->total ?? 0 }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="2"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucune donnée de fréquentation.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- ====================================================
                 SITUATION PÉDAGOGIQUE
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Situation pédagogique
                </h3>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Évaluations
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ $nombreEvaluations ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Notes enregistrées
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ $nombreNotes ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Élèves évalués
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ $elevesAvecNotes ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Moyenne générale
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ number_format((float)($moyenneNotes ?? 0), 2, ',', ' ') }}/20
                        </p>

                    </div>

                </div>



                {{-- Résultats par classe --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    Résultats par classe
                </h4>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Classe
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Nombre de notes
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Moyenne
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($resultatsClasses ?? [] as $resultat)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $resultat->libelle ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $resultat->nombre_notes ?? 0 }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-semibold">

                                        {{ number_format(
                                            (float)($resultat->moyenne ?? 0),
                                            2,
                                            ',',
                                            ' '
                                        ) }}/20

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucun résultat scolaire disponible.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- ====================================================
                 SITUATION FINANCIÈRE
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Situation financière
                </h3>


                {{-- Résumé --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Paiements
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ $nombrePaiements ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Recettes
                        </p>

                        <p class="text-2xl font-bold mt-2">

                            {{ number_format(
                                (float)($totalRecettes ?? 0),
                                2,
                                ',',
                                ' '
                            ) }}

                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Dépenses
                        </p>

                        <p class="text-2xl font-bold mt-2">

                            {{ number_format(
                                (float)($totalDepenses ?? 0),
                                2,
                                ',',
                                ' '
                            ) }}

                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Solde
                        </p>

                        <p class="text-2xl font-bold mt-2">

                            {{ number_format(
                                (float)($solde ?? 0),
                                2,
                                ',',
                                ' '
                            ) }}

                        </p>

                    </div>

                </div>



                {{-- Paiements par devise --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    Paiements par devise
                </h4>


                <div class="overflow-x-auto mb-8">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Devise
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Nombre de paiements
                                </th>

                                <th class="text-right px-4 py-3 font-semibold">
                                    Montant total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($paiements ?? [] as $paiement)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $paiement->devise ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $paiement->nombre ?? 0 }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold">

                                        {{ number_format(
                                            (float)($paiement->total ?? 0),
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucun paiement enregistré.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>



                {{-- Recettes --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    Recettes par devise
                </h4>


                <div class="overflow-x-auto mb-8">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Devise
                                </th>

                                <th class="text-right px-4 py-3 font-semibold">
                                    Total des recettes
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($recettes ?? [] as $recette)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $recette->devise ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold">

                                        {{ number_format(
                                            (float)($recette->total ?? 0),
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="2"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucune recette enregistrée.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>



                {{-- Dépenses --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    Dépenses par devise
                </h4>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Devise
                                </th>

                                <th class="text-right px-4 py-3 font-semibold">
                                    Total des dépenses
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($depenses ?? [] as $depense)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $depense->devise ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold">

                                        {{ number_format(
                                            (float)($depense->total ?? 0),
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="2"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucune dépense enregistrée.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- ====================================================
                 INVENTAIRE
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Inventaire des biens
                </h3>


                {{-- Résumé --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Nombre de biens
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ $nombreBiens ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Quantité totale
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ $quantiteBiens ?? 0 }}
                        </p>

                    </div>


                    <div class="border rounded-lg p-5">

                        <p class="text-sm text-gray-500">
                            Catégories
                        </p>

                        <p class="text-2xl font-bold mt-2">
                            {{ collect($inventaire ?? [])->count() }}
                        </p>

                    </div>

                </div>



                {{-- Inventaire par catégorie --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    Répartition par catégorie
                </h4>


                <div class="overflow-x-auto mb-8">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    Catégorie
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Nombre de biens
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Quantité
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($inventaire ?? [] as $item)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $item->categorie ?? 'Non catégorisé' }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        {{ $item->nombre ?? 0 }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-semibold">
                                        {{ $item->quantite ?? 0 }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucun bien enregistré dans l'inventaire.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>



                {{-- État des biens --}}
                <h4 class="text-base font-semibold text-gray-700 mb-4">
                    État des biens
                </h4>


                <div class="overflow-x-auto">

                    <table class="w-full text-sm border-collapse">

                        <thead>

                            <tr class="bg-gray-50 border-b">

                                <th class="text-left px-4 py-3 font-semibold">
                                    État
                                </th>

                                <th class="text-center px-4 py-3 font-semibold">
                                    Nombre
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($etatsInventaire ?? [] as $etat)

                                <tr class="border-b">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $etat->etat ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-semibold">
                                        {{ $etat->nombre ?? 0 }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="2"
                                        class="px-4 py-6 text-center text-gray-500">

                                        Aucun état d'inventaire disponible.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            {{-- ====================================================
                 SYNTHÈSE
            ==================================================== --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-5">
                    Synthèse annuelle
                </h3>


                <div class="space-y-3 text-sm text-gray-700">

                    <p>
                        L'établissement compte
                        <strong>{{ $nombreEleves ?? 0 }}</strong>
                        élève(s) réparti(s) dans
                        <strong>{{ $nombreClasses ?? 0 }}</strong>
                        classe(s).
                    </p>


                    <p>
                        L'effectif comprend
                        <strong>{{ $nombreGarcons ?? 0 }}</strong>
                        garçon(s) et
                        <strong>{{ $nombreFilles ?? 0 }}</strong>
                        fille(s).
                    </p>


                    <p>
                        Le personnel comprend
                        <strong>{{ $nombreEnseignants ?? 0 }}</strong>
                        enseignant(s) actif(s) et
                        <strong>{{ $nombreAutrePersonnel ?? 0 }}</strong>
                        autre(s) membre(s) du personnel actif(s).
                    </p>


                    <p>
                        Durant l'année, 
                        <strong>{{ $nombreEvaluations ?? 0 }}</strong>
                        évaluation(s) et
                        <strong>{{ $nombreNotes ?? 0 }}</strong>
                        note(s) ont été enregistrées.
                    </p>


                    <p>
                        La moyenne générale des notes enregistrées est de
                        <strong>
                            {{ number_format(
                                (float)($moyenneNotes ?? 0),
                                2,
                                ',',
                                ' '
                            ) }}/20
                        </strong>.
                    </p>


                    <p>
                        La fréquentation comptabilise
                        <strong>{{ $nombrePresences ?? 0 }}</strong>
                        présence(s) et
                        <strong>{{ $nombreAbsences ?? 0 }}</strong>
                        absence(s).
                    </p>


                    <p>
                        L'établissement possède
                        <strong>{{ $nombreBiens ?? 0 }}</strong>
                        bien(s) inventorié(s), pour une quantité totale de
                        <strong>{{ $quantiteBiens ?? 0 }}</strong>.
                    </p>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>
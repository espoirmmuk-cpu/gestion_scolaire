<x-app-layout>

<!-- En-tête -->
<x-slot name="header">
    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Gestion des notes
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Consultez et gérez les notes des élèves.
            </p>
        </div>

        <a href="{{ route('notes.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

            + Ajouter une note

        </a>

    </div>
</x-slot>


<!-- Contenu -->
<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        <!-- Message succès -->
        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">

                {{ session('success') }}

            </div>

        @endif


        <!-- Message erreur -->
        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">

                {{ session('error') }}

            </div>

        @endif


        <!-- Erreurs -->
        @if($errors->any())

            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5">

                <div class="font-semibold text-red-700 mb-2">
                    Veuillez corriger les erreurs suivantes :
                </div>

                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- Tableau -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            <!-- En-tête du tableau -->
            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Liste des notes
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $notes->count() }} note(s) enregistrée(s).
                </p>

            </div>


            @if($notes->count() > 0)

                <div class="overflow-x-auto">

                    <table class="w-full text-sm text-left">

                        <thead class="bg-gray-50 border-b border-gray-200">

                            <tr>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Élève
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Évaluation
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Matière
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Classe
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Période
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600 text-center">
                                    Note
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600">
                                    Appréciation
                                </th>

                                <th class="px-6 py-4 font-semibold text-gray-600 text-right">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($notes as $note)

                                <tr class="hover:bg-gray-50 transition">


                                    <!-- Élève -->
                                    <td class="px-6 py-4">

                                        @if($note->eleve)

                                            <div class="font-semibold text-gray-800">

                                                {{ $note->eleve->nom }}

                                                {{ $note->eleve->postnom }}

                                                {{ $note->eleve->prenom }}

                                            </div>

                                            @if($note->eleve->matricule)

                                                <div class="text-xs text-gray-400 mt-1">

                                                    {{ $note->eleve->matricule }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-gray-400">
                                                Élève introuvable
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Évaluation -->
                                    <td class="px-6 py-4">

                                        @if($note->evaluation)

                                            <div class="font-medium text-gray-800">

                                                {{ $note->evaluation->libelle }}

                                            </div>

                                            @if($note->evaluation->type_evaluation)

                                                <div class="text-xs text-gray-400 mt-1">

                                                    {{ $note->evaluation->type_evaluation }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="text-gray-400">
                                                Évaluation introuvable
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Matière -->
                                    <td class="px-6 py-4">

                                        @if($note->evaluation && $note->evaluation->matiere)

                                            <div class="font-medium text-gray-800">

                                                {{ $note->evaluation->matiere->libelle }}

                                            </div>

                                            <div class="text-xs text-gray-400 mt-1">

                                                {{ $note->evaluation->matiere->code }}

                                            </div>

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Classe -->
                                    <td class="px-6 py-4">

                                        @if($note->evaluation && $note->evaluation->classe)

                                            {{ $note->evaluation->classe->libelle }}

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Période -->
                                    <td class="px-6 py-4">

                                        @if($note->evaluation && $note->evaluation->periode)

                                            {{ $note->evaluation->periode->libelle }}

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Note -->
                                    <td class="px-6 py-4 text-center">

                                        @php
                                            $noteValue = (float) $note->note;

                                            $noteMax = $note->evaluation
                                                ? (float) $note->evaluation->note_maximale
                                                : 20;

                                            $pourcentage = $noteMax > 0
                                                ? ($noteValue / $noteMax) * 100
                                                : 0;
                                        @endphp


                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold
                                            {{ $pourcentage >= 50
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700' }}">

                                            {{ number_format($noteValue, 2, ',', ' ') }}

                                            /

                                            {{ number_format($noteMax, 2, ',', ' ') }}

                                        </span>

                                    </td>


                                    <!-- Appréciation -->
                                    <td class="px-6 py-4">

                                        @if($note->appreciation)

                                            <span class="text-gray-700">

                                                {{ $note->appreciation }}

                                            </span>

                                        @else

                                            <span class="text-gray-400">
                                                Aucune
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Actions -->
                                    <td class="px-6 py-4">

                                        <div class="flex items-center justify-end gap-2">


                                            <!-- Voir -->
                                            <a href="{{ route('notes.show', $note) }}"
                                               class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">

                                                Voir

                                            </a>


                                            <!-- Modifier -->
                                            <a href="{{ route('notes.edit', $note) }}"
                                               class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">

                                                Modifier

                                            </a>


                                            <!-- Supprimer -->
                                            <form action="{{ route('notes.destroy', $note) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette note ?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">

                                                    Supprimer

                                                </button>

                                            </form>


                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <!-- Aucune note -->
                <div class="p-12 text-center">

                    <div class="text-5xl mb-4">
                        📝
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        Aucune note enregistrée
                    </h3>

                    <p class="text-sm text-gray-400 mt-2">
                        Commencez par enregistrer une note pour un élève.
                    </p>

                    <div class="mt-5">

                        <a href="{{ route('notes.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">

                            + Ajouter une note

                        </a>

                    </div>

                </div>

            @endif


        </div>

    </div>

</div>

</x-app-layout>

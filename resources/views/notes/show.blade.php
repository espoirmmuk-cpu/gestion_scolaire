<x-app-layout>

    {{-- En-tête --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Détails de la note
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Consultation des informations détaillées de la note.
                </p>
            </div>

            <a href="{{ route('notes.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                ← Retour
            </a>
        </div>
    </x-slot>


    {{-- Contenu --}}
    <div class="py-8 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Carte principale --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                {{-- En-tête --}}
                <div class="px-6 py-5 border-b border-gray-100">

                    <div class="flex items-center">

                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">
                                📝
                            </span>
                        </div>

                        <div class="ml-4">

                            <h3 class="text-lg font-bold text-gray-800">
                                Note de l'élève
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Détails de l'évaluation et de la note obtenue.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Informations --}}
                <div class="p-6 space-y-6">


                    {{-- Élève --}}
                    <div>

                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
                            Élève
                        </h4>

                        <div class="bg-gray-50 rounded-lg p-4">

                            @if($note->eleve)

                                <p class="text-lg font-bold text-gray-800">
                                    {{ $note->eleve->nom }}
                                    {{ $note->eleve->postnom }}
                                    {{ $note->eleve->prenom }}
                                </p>

                                @if($note->eleve->matricule)
                                    <p class="text-sm text-gray-500 mt-1">
                                        Matricule :
                                        <span class="font-medium text-gray-700">
                                            {{ $note->eleve->matricule }}
                                        </span>
                                    </p>
                                @endif

                            @else

                                <p class="text-sm text-gray-400">
                                    Élève introuvable
                                </p>

                            @endif

                        </div>

                    </div>


                    {{-- Informations de l'évaluation --}}
                    <div>

                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
                            Évaluation
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                            {{-- Libellé --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Évaluation
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation)
                                        {{ $note->evaluation->libelle }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>


                            {{-- Type --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Type d'évaluation
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation && $note->evaluation->type_evaluation)
                                        {{ $note->evaluation->type_evaluation }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>


                            {{-- Matière --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Matière
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation && $note->evaluation->matiere)
                                        {{ $note->evaluation->matiere->libelle }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>


                            {{-- Classe --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Classe
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation && $note->evaluation->classe)
                                        {{ $note->evaluation->classe->libelle }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>


                            {{-- Période --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Période scolaire
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation && $note->evaluation->periode)
                                        {{ $note->evaluation->periode->libelle }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>


                            {{-- Année scolaire --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Année scolaire
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation && $note->evaluation->anneeScolaire)
                                        {{ $note->evaluation->anneeScolaire->libelle }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>


                            {{-- Date --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Date de l'évaluation
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation && $note->evaluation->date_evaluation)
                                        {{ $note->evaluation->date_evaluation->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>


                            {{-- Note maximale --}}
                            <div class="bg-gray-50 rounded-lg p-4">

                                <p class="text-xs text-gray-400 uppercase">
                                    Note maximale
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">

                                    @if($note->evaluation)
                                        {{ number_format((float) $note->evaluation->note_maximale, 2, ',', ' ') }}
                                    @else
                                        —
                                    @endif

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Résultat --}}
                    <div>

                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
                            Résultat
                        </h4>

                        <div class="bg-orange-50 border border-orange-100 rounded-xl p-6">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-sm text-gray-500">
                                        Note obtenue
                                    </p>

                                    <p class="text-4xl font-bold text-gray-800 mt-1">
                                        {{ number_format((float) $note->note, 2, ',', ' ') }}
                                    </p>

                                    @if($note->evaluation)
                                        <p class="text-sm text-gray-500 mt-1">
                                            sur
                                            {{ number_format((float) $note->evaluation->note_maximale, 2, ',', ' ') }}
                                        </p>
                                    @endif

                                </div>

                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                                    <span class="text-3xl">
                                        📝
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Appréciation --}}
                    <div>

                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">
                            Appréciation
                        </h4>

                        <div class="bg-gray-50 rounded-lg p-4">

                            @if($note->appreciation)

                                <p class="text-gray-700">
                                    {{ $note->appreciation }}
                                </p>

                            @else

                                <p class="text-sm text-gray-400 italic">
                                    Aucune appréciation enregistrée.
                                </p>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Boutons --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">

                    <a href="{{ route('notes.index') }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        ← Retour
                    </a>

                    <div class="flex items-center gap-3">

                        <a href="{{ route('notes.edit', $note) }}"
                           class="px-5 py-2.5 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                            ✏️ Modifier
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
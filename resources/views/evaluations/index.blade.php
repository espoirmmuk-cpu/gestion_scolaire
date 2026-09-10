<x-app-layout>

{{-- En-tête --}}
<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Évaluations
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gérer les évaluations, contrôles, examens et autres activités scolaires.
            </p>

        </div>


        <a href="{{ route('evaluations.create') }}"
           class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">

            + Nouvelle évaluation

        </a>

    </div>

</x-slot>


{{-- Contenu --}}
<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Message de succès --}}
        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">

                {{ session('success') }}

            </div>

        @endif


        {{-- Message d'erreur --}}
        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">

                {{ session('error') }}

            </div>

        @endif


        {{-- Erreurs de validation --}}
        @if($errors->any())

            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5">

                <div class="font-semibold text-red-700 mb-2">
                    Veuillez corriger les erreurs suivantes :
                </div>

                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Carte principale --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête --}}
            <div class="px-6 py-5 border-b border-gray-100">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Liste des évaluations
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $evaluations->count() }}
                            évaluation(s) enregistrée(s).
                        </p>

                    </div>


                    <div class="w-11 h-11 bg-indigo-100 rounded-full flex items-center justify-center">

                        <span class="text-xl">
                            📝
                        </span>

                    </div>

                </div>

            </div>


            @if($evaluations->count() > 0)

                {{-- Tableau --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Évaluation
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Matière
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Classe
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Période
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Année
                                </th>

                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Note max.
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-100">

                            @foreach($evaluations as $evaluation)

                                <tr class="hover:bg-gray-50 transition">


                                    {{-- Évaluation --}}
                                    <td class="px-6 py-4">

                                        <div class="font-semibold text-gray-800">
                                            {{ $evaluation->libelle }}
                                        </div>

                                        @if($evaluation->type_evaluation)

                                            <div class="text-xs text-gray-400 mt-1">
                                                {{ $evaluation->type_evaluation }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Matière --}}
                                    <td class="px-6 py-4">

                                        @if($evaluation->matiere)

                                            <span class="font-medium text-gray-700">
                                                {{ $evaluation->matiere->libelle }}
                                            </span>

                                            @if($evaluation->matiere->code)

                                                <div class="text-xs text-gray-400 mt-1">
                                                    {{ $evaluation->matiere->code }}
                                                </div>

                                            @endif

                                        @else

                                            <span class="text-gray-400">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Classe --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        @if($evaluation->classe)

                                            {{ $evaluation->classe->libelle }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Période --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        @if($evaluation->periode)

                                            {{ $evaluation->periode->libelle }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Année scolaire --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        @if($evaluation->anneeScolaire)

                                            {{ $evaluation->anneeScolaire->libelle }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Note maximale --}}
                                    <td class="px-6 py-4 text-center">

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">

                                            {{ number_format((float) $evaluation->note_maximale, 2, ',', ' ') }}

                                        </span>

                                    </td>


                                    {{-- Date --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        @if($evaluation->date_evaluation)

                                            {{ $evaluation->date_evaluation->format('d/m/Y') }}

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-4">

                                        <div class="flex items-center justify-end gap-2">


                                            {{-- Voir --}}
                                            <a href="{{ route('evaluations.show', $evaluation) }}"
                                               class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium"
                                               title="Voir">

                                                👁️

                                            </a>


                                            {{-- Modifier --}}
                                            <a href="{{ route('evaluations.edit', $evaluation) }}"
                                               class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition text-sm font-medium"
                                               title="Modifier">

                                                ✏️

                                            </a>


                                            {{-- Supprimer --}}
                                            <form action="{{ route('evaluations.destroy', $evaluation) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette évaluation ?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium"
                                                        title="Supprimer">

                                                    🗑️

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

                {{-- Aucune évaluation --}}
                <div class="p-10 text-center">

                    <div class="text-5xl mb-4">
                        📝
                    </div>

                    <h3 class="font-semibold text-gray-700 text-lg">
                        Aucune évaluation
                    </h3>

                    <p class="text-sm text-gray-400 mt-1">
                        Aucune évaluation n'a encore été enregistrée.
                    </p>


                    <a href="{{ route('evaluations.create') }}"
                       class="inline-block mt-5 px-5 py-2.5 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">

                        + Créer la première évaluation

                    </a>

                </div>

            @endif


        </div>

    </div>

</div>

</x-app-layout>

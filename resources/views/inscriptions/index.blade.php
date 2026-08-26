<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Inscriptions
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Gestion des inscriptions des élèves.
            </p>

        </div>


        <a href="{{ route('inscriptions.create') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">

            + Nouvelle inscription
            

        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Message de succès --}}

        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        {{-- Message d'erreur --}}

        @if(session('error'))

            <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- En-tête --}}

            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Liste des inscriptions
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $inscriptions->count() }} inscription(s) enregistrée(s).
                </p>

            </div>


            @if($inscriptions->count() > 0)

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Élève
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Classe
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Année scolaire
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Statut
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="bg-white divide-y divide-gray-100">

                            @foreach($inscriptions as $inscription)

                                <tr class="hover:bg-gray-50 transition">


                                    {{-- Élève --}}

                                    <td class="px-6 py-4">

                                        <div class="font-semibold text-gray-800">

                                            {{ $inscription->eleve?->nom }}

                                            {{ $inscription->eleve?->postnom }}

                                            {{ $inscription->eleve?->prenom }}

                                        </div>

                                        <div class="text-xs text-gray-400 mt-1">

                                            Matricule :
                                            {{ $inscription->eleve?->matricule ?? '—' }}

                                        </div>

                                    </td>


                                    {{-- Classe --}}

                                    <td class="px-6 py-4 text-sm text-gray-700">

                                        {{ $inscription->classe?->libelle ?? '—' }}

                                    </td>


                                    {{-- Année --}}

                                    <td class="px-6 py-4 text-sm text-gray-700">

                                        {{ $inscription->anneeScolaire?->libelle ?? '—' }}

                                    </td>


                                    {{-- Date --}}

                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        {{ $inscription->date_inscription?->format('d/m/Y') }}

                                    </td>


                                    {{-- Statut --}}

                                    <td class="px-6 py-4">

                                        @if($inscription->statut === 'INSCRIT')

                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                INSCRIT
                                            </span>

                                        @elseif($inscription->statut === 'ABANDON')

                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                ABANDON
                                            </span>

                                        @elseif($inscription->statut === 'TRANSFERE')

                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                                TRANSFÉRÉ
                                            </span>

                                        @elseif($inscription->statut === 'RADIE')

                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                RADIÉ
                                            </span>

                                        @elseif($inscription->statut === 'DIPLOME')

                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                DIPLÔMÉ
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}

                                    <td class="px-6 py-4">

                                        <div class="flex items-center justify-end gap-2">


                                            <a href="{{ route('inscriptions.show', $inscription) }}"
                                               class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm">

                                                👁 Voir

                                            </a>


                                            <a href="{{ route('inscriptions.edit', $inscription) }}"
                                               class="px-3 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition text-sm">

                                                ✏️ Modifier

                                            </a>


                                            @if(!$inscription->fraisEleves()->exists())

                                                <form action="{{ route('inscriptions.destroy', $inscription) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cette inscription ?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm">

                                                        🗑 Supprimer

                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="p-12 text-center">

                    <div class="text-5xl mb-4">
                        🎓
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700">
                        Aucune inscription
                    </h3>

                    <p class="text-sm text-gray-400 mt-2">
                        Commencez par enregistrer une nouvelle inscription.
                    </p>


                    <a href="{{ route('inscriptions.create') }}"
                       class="inline-block mt-5 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">

                        + Nouvelle inscription

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


</x-app-layout>

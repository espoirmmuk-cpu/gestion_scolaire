<x-app-layout>

<x-slot name="header">
    <div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Détails de la matière
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Consultation des informations de la matière.
        </p>
    </div>
</x-slot>

<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

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

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            {{-- En-tête --}}
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $matiere->libelle }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Informations détaillées
                    </p>

                </div>

                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    {{ $matiere->statut === 'ACTIVE'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-gray-100 text-gray-600' }}">

                    {{ $matiere->statut }}

                </span>

            </div>

            {{-- Informations --}}
            <div class="p-6">

                <div class="divide-y divide-gray-100">

                    {{-- ID --}}
                    <div class="py-4 flex justify-between gap-6">

                        <span class="text-sm font-semibold text-gray-600">
                            Identifiant
                        </span>

                        <span class="text-sm text-gray-800">
                            #{{ $matiere->id_matiere }}
                        </span>

                    </div>

                    {{-- Code --}}
                    <div class="py-4 flex justify-between gap-6">

                        <span class="text-sm font-semibold text-gray-600">
                            Code
                        </span>

                        <span class="text-sm font-medium text-gray-800">
                            {{ $matiere->code }}
                        </span>

                    </div>

                    {{-- Libellé --}}
                    <div class="py-4 flex justify-between gap-6">

                        <span class="text-sm font-semibold text-gray-600">
                            Libellé
                        </span>

                        <span class="text-sm text-gray-800">
                            {{ $matiere->libelle }}
                        </span>

                    </div>

                    {{-- Coefficient --}}
                    <div class="py-4 flex justify-between gap-6">

                        <span class="text-sm font-semibold text-gray-600">
                            Coefficient
                        </span>

                        <span class="text-sm text-gray-800">
                            {{ number_format($matiere->coefficient, 2, ',', ' ') }}
                        </span>

                    </div>

                    {{-- Statut --}}
                    <div class="py-4 flex justify-between gap-6">

                        <span class="text-sm font-semibold text-gray-600">
                            Statut
                        </span>

                        <span class="text-sm text-gray-800">
                            {{ $matiere->statut }}
                        </span>

                    </div>

                </div>

            </div>

            {{-- Boutons --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">

                <a
                    href="{{ route('matieres.index') }}"
                    class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition"
                >
                    ← Retour
                </a>

                <a
                    href="{{ route('matieres.edit', $matiere) }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                >
                    ✎ Modifier
                </a>

            </div>

        </div>

    </div>

</div>

</x-app-layout>
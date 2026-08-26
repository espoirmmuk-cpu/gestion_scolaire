<x-app-layout>

<x-slot name="header">
    <div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Modifier la matière
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Modifier les informations de la matière.
        </p>
    </div>
</x-slot>

<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        {{-- Erreurs de validation --}}
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

        {{-- Message d'erreur --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            {{-- En-tête --}}
            <div class="px-6 py-5 border-b border-gray-100">

                <h3 class="text-lg font-bold text-gray-800">
                    Informations de la matière
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Modifiez les informations puis enregistrez les changements.
                </p>

            </div>

            <form
                action="{{ route('matieres.update', $matiere) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">

                    {{-- Code --}}
                    <div>

                        <label for="code"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Code
                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="text"
                            name="code"
                            id="code"
                            value="{{ old('code', $matiere->code) }}"
                            required
                            maxlength="50"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>

                    {{-- Libellé --}}
                    <div>

                        <label for="libelle"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Libellé
                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="text"
                            name="libelle"
                            id="libelle"
                            value="{{ old('libelle', $matiere->libelle) }}"
                            required
                            maxlength="150"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >

                    </div>

                    {{-- Coefficient et statut --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Coefficient --}}
                        <div>

                            <label for="coefficient"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Coefficient
                                <span class="text-red-500">*</span>

                            </label>

                            <input
                                type="number"
                                name="coefficient"
                                id="coefficient"
                                value="{{ old('coefficient', $matiere->coefficient) }}"
                                required
                                min="0"
                                step="0.01"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >

                        </div>

                        {{-- Statut --}}
                        <div>

                            <label for="statut"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Statut
                                <span class="text-red-500">*</span>

                            </label>

                            <select
                                name="statut"
                                id="statut"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >

                                <option value="ACTIVE"
                                    {{ old('statut', $matiere->statut) === 'ACTIVE' ? 'selected' : '' }}>
                                    ACTIVE
                                </option>

                                <option value="INACTIVE"
                                    {{ old('statut', $matiere->statut) === 'INACTIVE' ? 'selected' : '' }}>
                                    INACTIVE
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                {{-- Boutons --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">

                    <a href="{{ route('matieres.index') }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">

                        ← Annuler

                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
                    >

                        ✓ Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>
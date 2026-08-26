<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bulletin individuel
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Sélectionnez l'élève et la période pour générer son bulletin.
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Message d'erreur --}}
            @if(session('error'))

                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>

            @endif


            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                <div class="flex items-center mb-6">

                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">

                        <span class="text-2xl">
                            📝
                        </span>

                    </div>

                    <div class="ml-4">

                        <h1 class="text-xl font-bold text-gray-800">
                            Générer un bulletin individuel
                        </h1>

                        <p class="text-sm text-gray-500">
                            Choisissez l'année scolaire, la période et l'élève.
                        </p>

                    </div>

                </div>


                <form method="GET"
                      action="{{ route('rapports.bulletin.selection') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                        {{-- Année scolaire --}}
                        <div>

                            <label
                                for="annee"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Année scolaire
                            </label>

                            <select
                                id="annee"
                                name="annee"
                                onchange="this.form.submit()"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                            >

                                <option value="">
                                    -- Sélectionner l'année --
                                </option>

                                @foreach($annees as $annee)

                                    <option
                                        value="{{ $annee->id_annee_scolaire }}"
                                        {{ request('annee') == $annee->id_annee_scolaire ? 'selected' : '' }}
                                    >
                                        {{ $annee->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Période --}}
                        <div>

                            <label
                                for="periode"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Période scolaire
                            </label>

                            <select
                                id="periode"
                                name="periode"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                            >

                                <option value="">
                                    -- Sélectionner la période --
                                </option>

                                @foreach($periodes as $periode)

                                    <option
                                        value="{{ $periode->id_periode }}"
                                        {{ request('periode') == $periode->id_periode ? 'selected' : '' }}
                                    >
                                        {{ $periode->libelle }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Élève --}}
                        <div>

                            <label
                                for="eleve"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Élève
                            </label>

                            <select
                                id="eleve"
                                name="eleve"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-500 focus:ring-gray-500"
                            >

                                <option value="">
                                    -- Sélectionner l'élève --
                                </option>

                                @foreach($eleves as $eleve)

                                    <option
                                        value="{{ $eleve->id_eleve }}"
                                    >
                                        {{ $eleve->matricule }}
                                        —
                                        {{ $eleve->nom }}
                                        {{ $eleve->postnom }}
                                        {{ $eleve->prenom }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- Boutons --}}
                    <div class="mt-8 flex items-center justify-between">

                        <a
                            href="{{ route('rapports.index') }}"
                            class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                        >
                            ← Retour aux rapports
                        </a>


                        <button
                            type="submit"
                            formaction="{{ route('rapports.bulletin.selection') }}"
                            class="px-6 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                        >
                            🔎 Rechercher
                        </button>

                    </div>

                </form>


                {{-- ================================================= --}}
                {{-- BOUTON PDF --}}
                {{-- ================================================= --}}

                @if(request()->filled('eleve'))

                    <div class="mt-8 pt-6 border-t border-gray-200">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="font-semibold text-gray-800">
                                    Bulletin sélectionné
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    Cliquez sur le bouton pour générer le bulletin PDF.
                                </p>

                            </div>


                            <a
                                href="{{ route('rapports.bulletin', request('eleve')) }}?annee={{ request('annee') }}&periode={{ request('periode') }}"
                                target="_blank"
                                class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700"
                            >
                                📄 Générer le Bulletin PDF
                            </a>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
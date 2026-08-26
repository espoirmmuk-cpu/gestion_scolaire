<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Rapports
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Génération et consultation des rapports de l'établissement
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Introduction --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Rapports scolaires
                </h1>

                <p class="text-gray-500 mt-1">
                    Sélectionnez le rapport que vous souhaitez générer.
                </p>
            </div>


            {{-- Grille des rapports --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">


                {{-- Rapport annuel --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <span class="text-2xl">📘</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Rapport annuel
                            </h3>

                            <p class="text-sm text-gray-500">
                                Rapport général
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Effectifs, classes, personnel, résultats,
                        fréquentation, finances et statistiques générales.
                    </p>

                    <a href="{{ route('rapports.annuel') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer le rapport
                    </a>
                </div>


                {{-- Rapport mensuel --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <span class="text-2xl">📅</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Rapport mensuel
                            </h3>

                            <p class="text-sm text-gray-500">
                                Activités du mois
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Synthèse des activités et statistiques
                        pour le mois sélectionné.
                    </p>

                    <a href="{{ route('rapports.mensuel') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer le rapport
                    </a>
                </div>


                {{-- Rapport statistique --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <span class="text-2xl">📊</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Rapport statistique
                            </h3>

                            <p class="text-sm text-gray-500">
                                Sous-division / Province
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Statistiques scolaires destinées aux
                        autorités éducatives.
                    </p>

                    <a href="{{ route('rapports.statistique') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer le rapport
                    </a>
                </div>


                {{-- Palmarès --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <span class="text-2xl">🏆</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Palmarès des élèves
                            </h3>

                            <p class="text-sm text-gray-500">
                                Classement
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Classement des élèves selon leurs résultats
                        et leurs moyennes.
                    </p>

                    <a href="{{ route('rapports.palmares') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer le palmarès
                    </a>
                </div>


                {{-- Bulletin individuel --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                            <span class="text-2xl">📝</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Bulletin individuel
                            </h3>

                            <p class="text-sm text-gray-500">
                                Bulletin d'un élève
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Génération du bulletin individuel avec
                        les notes et résultats de l'élève.
                    </p>

                    <a href="{{ route('rapports.bulletin.selection') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer le bulletin
                    </a>
                </div>


                {{-- Fréquentation --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                            <span class="text-2xl">👥</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Fiche de fréquentation
                            </h3>

                            <p class="text-sm text-gray-500">
                                Présences et absences
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Suivi des présences, absences, retards
                        et taux de fréquentation.
                    </p>

                    <a href="{{ route('rapports.frequentation') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer la fiche
                    </a>
                </div>


                {{-- Liste des enseignants --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <span class="text-2xl">👨‍🏫</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Liste des enseignants
                            </h3>

                            <p class="text-sm text-gray-500">
                                Personnel enseignant
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Liste complète des enseignants et leurs
                        informations professionnelles.
                    </p>

                    <a href="{{ route('rapports.enseignants') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer la liste
                    </a>
                </div>


                {{-- Situation financière --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <span class="text-2xl">💰</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Situation financière
                            </h3>

                            <p class="text-sm text-gray-500">
                                Frais et paiements
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Recettes, paiements, soldes et situation
                        financière de l'établissement.
                    </p>

                    <a href="{{ route('rapports.finances') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer le rapport
                    </a>
                </div>


                {{-- Inventaire --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center">
                            <span class="text-2xl">📦</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Inventaire des biens
                            </h3>

                            <p class="text-sm text-gray-500">
                                Patrimoine
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Liste des biens, équipements, quantités,
                        états et valeurs.
                    </p>

                    <a href="{{ route('rapports.inventaire') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer l'inventaire
                    </a>
                </div>


                {{-- Examens nationaux --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">

                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-rose-100 flex items-center justify-center">
                            <span class="text-2xl">🎓</span>
                        </div>

                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">
                                Examens nationaux
                            </h3>

                            <p class="text-sm text-gray-500">
                                Statistiques
                            </p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-5">
                        Candidats, résultats, admis, échecs et
                        taux de réussite.
                    </p>

                    <a href="{{ route('rapports.examens-nationaux') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-700">
                        Générer les statistiques
                    </a>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
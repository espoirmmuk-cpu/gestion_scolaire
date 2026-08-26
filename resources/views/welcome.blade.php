<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion Scolaire</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 text-gray-800">

    <!-- ========================================================= -->
    <!-- EN-TÊTE -->
    <!-- ========================================================= -->

    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-6 py-4">

            <div class="flex items-center justify-between">

                <!-- Logo / Nom de l'application -->
                <div class="flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white shadow">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 14l9-5-9-5-9 5 9 5z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 14l6.16-3.422A12.083 12.083 0 0118 20.25M12 14L5.84 10.578A12.083 12.083 0 006 20.25M12 14v6"
                            />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-xl font-bold text-gray-900">
                            GESCO
                        </h1>

                        <p class="text-xs text-gray-500">
                            Plateforme de gestion des établissements scolaires
                        </p>
                    </div>

                </div>

                <!-- Bouton connexion -->
               
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-bold text-indigo-700 shadow-lg transition hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600"
                        >

                            Accéder à la plateforme

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                                />
                            </svg>

                             <a
                                href="{{ route('contact') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-bold text-indigo-700 shadow-lg transition hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600"
                        >

                                📞 Nous contacter

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                                />
                                </svg>

                            </a>

            </div>

        </div>
    </header>
</header>

<!-- Bande aux couleurs de la RDC -->
<div
    style="
        display: flex;
        width: 100%;
        height: 8px;
        background: linear-gradient(
            to right,
            #007fff 0%,
            #007fff 33.33%,
            #f7d618 33.33%,
            #f7d618 66.66%,
            #ce1021 66.66%,
            #ce1021 100%
        );
    "
>
</div>

<main>

    <!-- ========================================================= -->
    <!-- SECTION PRINCIPALE -->
    <!-- ========================================================= -->

    <main>

        <!-- ===================================================== -->
        <!-- HERO -->
        <!-- ===================================================== -->

        <section
            class="relative overflow-hidden"
            style="background: linear-gradient(135deg, #052890 0%, #0620b2 50%, #0a54f4 100%);"
        >

            <!-- Décorations en arrière-plan -->
            <div class="absolute inset-0 overflow-hidden">

                <div
                    class="absolute -right-20 -top-20 h-80 w-80 rounded-full"
                    style="background: rgba(255, 255, 255, 0.08);"
                ></div>

                <div
                    class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full"
                    style="background: rgba(255, 255, 255, 0.08);"
                ></div>

                <div
                    class="absolute right-1/3 top-1/2 h-40 w-40 rounded-full"
                    style="background: rgba(255, 255, 255, 0.04);"
                ></div>

            </div>


            <div class="relative mx-auto max-w-7xl px-6 py-20 lg:py-28">

                <div class="max-w-3xl">

                    <!-- Badge -->
                    <span
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium text-white ring-1 ring-white/20"
                        style="background: rgba(255, 255, 255, 0.15);"
                    >
                        Système intégré de gestion scolaire
                    </span>


                    <!-- Titre principal -->
                    <h2 class="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">

                        Gérez votre établissement scolaire

                        <span class="text-indigo-200">
                            simplement et efficacement.
                        </span>

                    </h2>


                    <!-- Description -->
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-white">
                       
                    </p>

                </div>

            </div>

        </section>


        <!-- ===================================================== -->
        <!-- MODULES -->
        <!-- ===================================================== -->

        <section class="bg-white py-16">

            <div class="mx-auto max-w-7xl px-6">

                <div class="mx-auto max-w-2xl text-center">

                    <h3 class="text-3xl font-bold tracking-tight text-gray-900">
                         
                    </h3>

                    <p class="mt-4 text-gray-600">
                        Tous les outils essentiels réunis dans une seule
                        plateforme.
                    </p>

                </div>


                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">


                    <!-- ================================================= -->
                    <!-- ÉLÈVES -->
                    <!-- ================================================= -->

                    <div
                        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 20H4v-2a4 4 0 014-4h1"
                                />

                                <circle
                                    cx="12"
                                    cy="7"
                                    r="4"
                                />
                            </svg>

                        </div>

                        <h4 class="mt-5 text-lg font-bold text-gray-900">
                            Gestion des élèves
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Enregistrez et gérez les informations des élèves,
                            leurs inscriptions et leur parcours scolaire.
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- CLASSES -->
                    <!-- ================================================= -->

                    <div
                        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1M14 7h1M9 11h1M14 11h1M9 15h1M14 15h1"
                                />
                            </svg>

                        </div>

                        <h4 class="mt-5 text-lg font-bold text-gray-900">
                            Classes
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Organisez les classes, niveaux, options et
                            effectifs de votre établissement.
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- PERSONNEL -->
                    <!-- ================================================= -->

                    <div
                        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 21a8 8 0 0116 0"
                                />
                            </svg>

                        </div>

                        <h4 class="mt-5 text-lg font-bold text-gray-900">
                            Personnel
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Gérez les enseignants, administrateurs et autres
                            membres du personnel.
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- PAIEMENTS -->
                    <!-- ================================================= -->

                    <div
                        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2m0 0c-2.21 0-4-1.343-4-3h8c0 1.657-1.79 3-4 3z"
                                />
                            </svg>

                        </div>

                        <h4 class="mt-5 text-lg font-bold text-gray-900">
                            Frais et paiements
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Suivez les frais scolaires, les tarifs,
                            les paiements et les reçus.
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- ÉVALUATIONS -->
                    <!-- ================================================= -->

                    <div
                        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12l2 2 4-4"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"
                                />
                            </svg>

                        </div>

                        <h4 class="mt-5 text-lg font-bold text-gray-900">
                            Évaluations et notes
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Créez les évaluations, saisissez les notes et
                            suivez les résultats des élèves.
                        </p>

                    </div>


                    <!-- ================================================= -->
                    <!-- BULLETINS -->
                    <!-- ================================================= -->

                    <div
                        class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                    >

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 text-red-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 8h8M8 12h8M8 16h5"
                                />
                            </svg>

                        </div>

                        <h4 class="mt-5 text-lg font-bold text-gray-900">
                            Bulletins scolaires
                        </h4>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            Consultez les résultats et générez les bulletins
                            scolaires des élèves.
                        </p>

                    </div>

                </div>

            </div>
        
        </section>
                <div>
                    -
                </div>
        <!-- ===================================================== -->
        <!-- SECTION CONNEXION -->
        <!-- ===================================================== -->

        <section class="bg-gray-50 py-16">

            <div class="mx-auto max-w-4xl px-6 text-center">

                <div
                    class="rounded-2xl px-6 py-12 shadow-xl sm:px-12"
                    style="background: linear-gradient(135deg, #0620b2 0%, #0620b2 100%);"
                >

                    <h3 class="text-3xl font-bold text-white">
                        Accédez à votre espace de gestion
                    </h3>

                    <p class="mx-auto mt-4 max-w-2xl text-white">
                        Connectez-vous pour accéder aux fonctionnalités
                        de gestion de votre établissement scolaire.
                    </p>

                    <div class="mt-8">

                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-white px-7 py-3 text-sm font-bold text-indigo-700 shadow transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600"
                        >

                            Se connecter

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                                />
                            </svg>

                        </a>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <!-- ========================================================= -->
    <!-- PIED DE PAGE -->
    <!-- ========================================================= -->

    <footer class="border-t border-gray-200 bg-white">

        <div class="mx-auto max-w-7xl px-6 py-8">

            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">

                <div>

                    <p class="font-semibold text-gray-900">
                                        GESCO
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Plateforme de gestion des établissements scolaires
                    </p>

                </div>


                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} Gestion Scolaire.
                    Tous droits réservés.
                </p>

            </div>

        </div>

    </footer>

</body>
</html>
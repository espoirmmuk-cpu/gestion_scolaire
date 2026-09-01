<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ config('app.name', 'GESCO — Gestion Scolaire Complète') }}
    </title>

    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    />

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: #f5f7fb;
        }

        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        */

        .gesco-sidebar {

            position: fixed;

            top: 0;
            left: 0;
            bottom: 0;

            width: 250px;

            background: #082b52;

            color: white;

            z-index: 9999;

            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin;

            scrollbar-color:
                rgba(255,255,255,0.25)
                transparent;
        }

        .gesco-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .gesco-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .gesco-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.25);
            border-radius: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        .gesco-logo {

            height: 90px;

            display: flex;

            align-items: center;

            padding: 18px 20px;

            border-bottom:
                1px solid rgba(255,255,255,0.10);
        }

        .gesco-logo-circle {

            width: 48px;
            height: 48px;

            flex-shrink: 0;

            border-radius: 12px;

            background: #1687e8;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 23px;
            font-weight: 800;

            margin-right: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | MENU
        |--------------------------------------------------------------------------
        */

        .gesco-menu {

            padding: 14px 10px 25px;
        }

        .gesco-menu-title {

            color: rgba(255,255,255,0.45);

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            padding:
                14px 14px 7px;

            letter-spacing: 0.7px;
        }

        .gesco-menu-item {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            min-height: 43px;

            padding:
                10px 13px;

            margin-bottom: 3px;

            border-radius: 8px;

            color:
                rgba(255,255,255,0.80);

            text-decoration: none;

            font-size: 13.5px;

            font-weight: 500;

            transition:
                background 0.2s ease,
                color 0.2s ease;
        }

        .gesco-menu-item:hover {

            background:
                rgba(255,255,255,0.09);

            color: white;
        }

        .gesco-menu-item.active {

            background: #1594f4;

            color: white;

            box-shadow:
                0 4px 12px
                rgba(21,148,244,0.25);
        }

        .gesco-menu-icon {

            width: 21px;

            min-width: 21px;

            text-align: center;

            font-size: 17px;
        }

        /*
        |--------------------------------------------------------------------------
        | ZONE PRINCIPALE
        |--------------------------------------------------------------------------
        */

        .gesco-main {

            margin-left: 250px;

            min-height: 100vh;

            width: calc(100% - 250px);

            background: #f5f7fb;
        }

        /*
        |--------------------------------------------------------------------------
        | TOPBAR
        |--------------------------------------------------------------------------
        */

        .gesco-topbar {

            min-height: 70px;

            background: white;

            border-bottom:
                1px solid #e8edf3;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                0 28px;

            position: sticky;

            top: 0;

            z-index: 100;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTENU
        |--------------------------------------------------------------------------
        */

        .gesco-content {

            padding:
                25px 28px 40px;

            width: 100%;

            min-height:
                calc(100vh - 70px);
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 900px) {

            .gesco-sidebar {

                width: 75px;
            }

            .gesco-logo {

                justify-content: center;

                padding: 10px;
            }

            .gesco-logo-circle {

                margin-right: 0;
            }

            .gesco-logo-text,
            .gesco-menu-text,
            .gesco-menu-title {

                display: none;
            }

            .gesco-menu-item {

                justify-content: center;

                padding:
                    11px 5px;
            }

            .gesco-main {

                margin-left: 75px;

                width:
                    calc(100% - 75px);
            }

            .gesco-topbar {

                padding:
                    0 18px;
            }

            .gesco-content {

                padding:
                    20px;
            }
        }

    </style>

</head>


<body>

<div>

    {{-- =========================================================
         SIDEBAR PERMANENTE
         ========================================================= --}}

    <aside class="gesco-sidebar">

        {{-- LOGO --}}

        <div class="gesco-logo">

            <div class="gesco-logo-circle">
                G
            </div>

            <div class="gesco-logo-text">

                <div class="text-white text-xl font-bold">
                    GESCO
                </div>

                <div class="text-blue-200 text-xs">
                    Gestion Scolaire Complète
                </div>

            </div>

        </div>


        {{-- MENU --}}

        <nav class="gesco-menu">

            {{-- =================================================
                 PRINCIPAL
                 ================================================= --}}

            <div class="gesco-menu-title">
                Principal
            </div>


            {{-- TABLEAU DE BORD --}}

            <a
                href="{{ route('dashboard') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('dashboard')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">⌂</span>

                <span class="gesco-menu-text">
                    Tableau de bord
                </span>

            </a>


            {{-- ÉLÈVES --}}

            <a
                href="{{ route('eleves.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('eleves.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">♙</span>

                <span class="gesco-menu-text">
                    Élèves
                </span>

            </a>


            {{-- CLASSES --}}

            <a
                href="{{ route('classes.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('classes.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">▣</span>

                <span class="gesco-menu-text">
                    Classes
                </span>

            </a>


            {{-- INSCRIPTIONS --}}

            <a
                href="{{ route('inscriptions.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('inscriptions.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📋</span>

                <span class="gesco-menu-text">
                    Inscriptions
                </span>

            </a>


            {{-- PRÉSENCES --}}

            <a
                href="{{ route('presences.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('presences.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">✓</span>

                <span class="gesco-menu-text">
                    Présences
                </span>

            </a>


            {{-- MATIÈRES --}}

            <a
                href="{{ route('matieres.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('matieres.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📚</span>

                <span class="gesco-menu-text">
                    Matières
                </span>

            </a>


            {{-- ÉVALUATIONS --}}

            <a
                href="{{ route('evaluations.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('evaluations.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📝</span>

                <span class="gesco-menu-text">
                    Évaluations
                </span>

            </a>


            {{-- NOTES --}}

            <a
                href="{{ route('notes.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('notes.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">▧</span>

                <span class="gesco-menu-text">
                    Notes
                </span>

            </a>


            {{-- BULLETINS --}}

            <a
                href="{{ route('bulletins.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('bulletins.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📄</span>

                <span class="gesco-menu-text">
                    Bulletins
                </span>

            </a>


            {{-- =================================================
                 PERSONNEL
                 ================================================= --}}

            <div class="gesco-menu-title">
                Personnel
            </div>


            {{-- PERSONNEL --}}

            <a
                href="{{ route('personnel.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('personnel.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">♟</span>

                <span class="gesco-menu-text">
                    Personnel
                </span>

            </a>


            {{-- AFFECTATIONS ENSEIGNANTS --}}

            <a
                href="{{ route('affectations-enseignants.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('affectations-enseignants.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">👨‍🏫</span>

                <span class="gesco-menu-text">
                    Affectations enseignants
                </span>

            </a>


            {{-- =================================================
                 FINANCES
                 ================================================= --}}

            <div class="gesco-menu-title">
                Finance
            </div>


            {{-- CATÉGORIES DE FRAIS --}}

            <a
                href="{{ route('categories-frais.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('categories-frais.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">▤</span>

                <span class="gesco-menu-text">
                    Catégories de frais
                </span>

            </a>


            {{-- TARIFS SCOLAIRES --}}

            <a
                href="{{ route('tarifs-scolaires.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('tarifs-scolaires.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">💵</span>

                <span class="gesco-menu-text">
                    Tarifs scolaires
                </span>

            </a>


            {{-- PAIEMENTS --}}

            <a
                href="{{ route('paiements.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('paiements.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">₣</span>

                <span class="gesco-menu-text">
                    Paiements
                </span>

            </a>


            {{-- RECETTES --}}

            <a
                href="{{ route('recettes.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('recettes.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📈</span>

                <span class="gesco-menu-text">
                    Recettes
                </span>

            </a>


            {{-- DÉPENSES --}}

            <a
                href="{{ route('depenses.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('depenses.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📉</span>

                <span class="gesco-menu-text">
                    Dépenses
                </span>

            </a>


            {{-- CAISSE --}}

            @if(Route::has('caisse.index'))

                <a
                    href="{{ route('caisse.index') }}"
                    class="gesco-menu-item
                        {{ request()->routeIs('caisse.*')
                            ? 'active'
                            : '' }}"
                >

                    <span class="gesco-menu-icon">💰</span>

                    <span class="gesco-menu-text">
                        Caisse
                    </span>

                </a>

            @endif


            {{-- =================================================
                 CONFIGURATION SCOLAIRE
                 ================================================= --}}

            <div class="gesco-menu-title">
                Configuration
            </div>


            {{-- ANNÉES SCOLAIRES --}}

            <a
                href="{{ route('annees-scolaires.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('annees-scolaires.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📅</span>

                <span class="gesco-menu-text">
                    Années scolaires
                </span>

            </a>


            {{-- PÉRIODES SCOLAIRES --}}

            <a
                href="{{ route('periodes-scolaires.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('periodes-scolaires.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">🗓</span>

                <span class="gesco-menu-text">
                    Périodes scolaires
                </span>

            </a>


            {{-- INFRASTRUCTURES --}}

            <a
                href="{{ route('infrastructures.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('infrastructures.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">🏢</span>

                <span class="gesco-menu-text">
                    Infrastructures
                </span>

            </a>


            {{-- =================================================
                 SUIVI
                 ================================================= --}}

            <div class="gesco-menu-title">
                Suivi
            </div>


            {{-- JOURNAL ACTIVITÉS --}}

            <a
                href="{{ route('journaux-activites.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('journaux-activites.*')
                        || request()->routeIs('journal-activites.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📜</span>

                <span class="gesco-menu-text">
                    Journal activités
                </span>

            </a>


            {{-- RAPPORTS --}}

            <a
                href="{{ route('rapports.index') }}"
                class="gesco-menu-item
                    {{ request()->routeIs('rapports.*')
                        ? 'active'
                        : '' }}"
            >

                <span class="gesco-menu-icon">📊</span>

                <span class="gesco-menu-text">
                    Rapports
                </span>

            </a>


           {{-- =================================================
            ADMINISTRATION
            RÉSERVÉE AU SUPER ADMINISTRATEUR
            id_etablissement = NULL
            ================================================= --}}

            @if(is_null(Auth::user()->id_etablissement))

            <div class="gesco-menu-title">
                Administration
            </div>


            {{-- ÉTABLISSEMENTS --}}

            @if(Route::has('etablissements.index'))

                <a
                    href="{{ route('etablissements.index') }}"
                    class="gesco-menu-item
                        {{ request()->routeIs('etablissements.*')
                            ? 'active'
                            : '' }}"
                >

                    <span class="gesco-menu-icon">🏫</span>

                    <span class="gesco-menu-text">
                        Établissements
                    </span>

                </a>

            @endif


            {{-- UTILISATEURS --}}

            @if(Route::has('utilisateurs.index'))

                <a
                    href="{{ route('utilisateurs.index') }}"
                    class="gesco-menu-item
                        {{ request()->routeIs('utilisateurs.*')
                            ? 'active'
                            : '' }}"
                >

                    <span class="gesco-menu-icon">👤</span>

                    <span class="gesco-menu-text">
                        Utilisateurs
                    </span>

                </a>

            @endif


            {{-- RÔLES --}}

            @if(Route::has('roles.index'))

                <a
                    href="{{ route('roles.index') }}"
                    class="gesco-menu-item
                        {{ request()->routeIs('roles.*')
                            ? 'active'
                            : '' }}"
                >

                    <span class="gesco-menu-icon">🔐</span>

                    <span class="gesco-menu-text">
                        Rôles
                    </span>

                </a>

            @endif

            @endif


            {{-- PARAMÈTRES / PROFIL --}}

            @if(Route::has('profile.edit'))

                <a
                    href="{{ route('profile.edit') }}"
                    class="gesco-menu-item
                        {{ request()->routeIs('profile.*')
                            ? 'active'
                            : '' }}"
                >

                    <span class="gesco-menu-icon">⚙</span>

                    <span class="gesco-menu-text">
                        Paramètres
                    </span>

                </a>

            @endif

        </nav>


        {{-- =====================================================
             UTILISATEUR
             ===================================================== --}}

        @auth

            <div class="border-t border-blue-900 p-4">

                <div class="flex items-center gap-3 mb-3">

                    <div
                        class="w-9 h-9 rounded-full bg-blue-500
                               flex items-center justify-center
                               text-white font-bold flex-shrink-0"
                    >

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>

                    <div class="min-w-0 gesco-logo-text">

                        <p class="font-medium truncate text-white">

                            {{ auth()->user()->name }}

                        </p>

                        <p class="text-xs text-blue-200">

                            Utilisateur

                        </p>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="gesco-menu-item hover:bg-red-600 hover:text-white"
                    >

                        <span class="gesco-menu-icon">
                            🚪
                        </span>

                        <span class="gesco-menu-text">
                            Déconnexion
                        </span>

                    </button>

                </form>

            </div>

        @endauth

    </aside>



    {{-- =========================================================
         CONTENU PRINCIPAL
         ========================================================= --}}

    <div class="gesco-main">


        {{-- TOPBAR --}}

        <header class="gesco-topbar">

            <div>

                @isset($header)

                    {{ $header }}

                @else

                    <h1 class="text-xl font-bold text-gray-800">
                        GESCO
                    </h1>

                @endisset

            </div>


            <div class="flex items-center gap-4">

                @auth

                    <div
                        class="w-9 h-9 rounded-full
                               bg-blue-100
                               flex items-center justify-center
                               text-blue-700 font-bold"
                    >

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>

                    <div class="hidden md:block">

                        <p class="text-sm font-semibold text-gray-700">

                            {{ auth()->user()->name }}

                        </p>

                        <p class="text-xs text-gray-400">
                            Administrateur
                        </p>

                    </div>

                @endauth

            </div>

        </header>


        {{-- CONTENU DE LA PAGE --}}

        <main class="gesco-content">

            {{ $slot }}

        </main>

    </div>

</div>

</body>

</html>
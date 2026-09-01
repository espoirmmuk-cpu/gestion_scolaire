<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GESCO — Gestion Scolaire Complète</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fb;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #082b52;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;

            display: flex;
            flex-direction: column;

            overflow: hidden;
        }
        .sidebar-logo {
            height: 105px;
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.10);
        }

        .logo-circle {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #1687e8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 800;
            margin-right: 12px;
        }

        .sidebar-menu {
            padding: 18px 12px;

            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin;
            scrollbar-color: #1687e8 #082b52;
        }

        /* =========================================================
   BARRE DE DÉFILEMENT DE LA SIDEBAR
   ========================================================= */

        .sidebar-menu::-webkit-scrollbar {
            width: 7px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: #082b52;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #1687e8;
            border-radius: 10px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: #1594f4;
        }
        .menu-title {
            color: rgba(255,255,255,0.45);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 14px 8px;
            letter-spacing: 0.5px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 13px;
            width: 100%;
            padding: 11px 14px;
            margin-bottom: 4px;
            border-radius: 8px;
            color: rgba(255,255,255,0.78);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .menu-item.active {
            background: #1594f4;
            color: white;
            box-shadow: 0 4px 12px rgba(21,148,244,0.25);
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 17px;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            height: 72px;
            background: white;
            border-bottom: 1px solid #e8edf3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .content {
            padding: 28px 30px 40px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e9eef4;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .panel {
            background: white;
            border: 1px solid #e9eef4;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        }

        @media (max-width: 900px) {

            .sidebar {
                width: 75px;
            }

            .sidebar-logo {
                justify-content: center;
                padding: 10px;
            }

            .logo-circle {
                margin: 0;
            }

            .logo-text,
            .menu-text,
            .menu-title {
                display: none;
            }

            .menu-item {
                justify-content: center;
                padding: 13px 5px;
            }

            .main {
                margin-left: 75px;
            }

            .content {
                padding: 20px;
            }

        }

    </style>

</head>


<body>


{{-- ============================================================ --}}
{{-- SIDEBAR --}}
{{-- ============================================================ --}}

<aside class="sidebar">

    {{-- LOGO --}}

    <div class="sidebar-logo">

        <div class="logo-circle">
            G
        </div>

        <div class="logo-text">

            <div class="text-white text-xl font-bold leading-tight">
                GESCO
            </div>

            <div class="text-blue-200 text-xs">
                Gestion Scolaire Complète
            </div>

        </div>

    </div>


    {{-- MENU --}}

    <nav class="sidebar-menu">

{{-- ============================================================
     PRINCIPAL
     ============================================================ --}}

<div class="menu-title">
    Principal
</div>

{{-- Tableau de bord --}}
<a
    href="{{ route('dashboard') }}"
    class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
>
    <span class="menu-icon">⌂</span>
    <span class="menu-text">Tableau de bord</span>
</a>


{{-- Élèves --}}
<a
    href="{{ route('eleves.index') }}"
    class="menu-item {{ request()->routeIs('eleves.*') ? 'active' : '' }}"
>
    <span class="menu-icon">♙</span>
    <span class="menu-text">Élèves</span>
</a>


{{-- Classes --}}
<a
    href="{{ route('classes.index') }}"
    class="menu-item {{ request()->routeIs('classes.*') ? 'active' : '' }}"
>
    <span class="menu-icon">▣</span>
    <span class="menu-text">Classes</span>
</a>


{{-- Inscriptions --}}
<a
    href="{{ route('inscriptions.index') }}"
    class="menu-item {{ request()->routeIs('inscriptions.*') ? 'active' : '' }}"
>
    <span class="menu-icon">📝</span>
    <span class="menu-text">Inscriptions</span>
</a>


{{-- Présences --}}
<a
    href="{{ route('presences.index') }}"
    class="menu-item {{ request()->routeIs('presences.*') ? 'active' : '' }}"
>
    <span class="menu-icon">✓</span>
    <span class="menu-text">Présences</span>
</a>


{{-- Matières --}}
<a
    href="{{ route('matieres.index') }}"
    class="menu-item {{ request()->routeIs('matieres.*') ? 'active' : '' }}"
>
    <span class="menu-icon">▤</span>
    <span class="menu-text">Matières</span>
</a>


{{-- Évaluations --}}
<a
    href="{{ route('evaluations.index') }}"
    class="menu-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}"
>
    <span class="menu-icon">📝</span>
    <span class="menu-text">Évaluations</span>
</a>


{{-- Notes --}}
<a
    href="{{ route('notes.index') }}"
    class="menu-item {{ request()->routeIs('notes.*') ? 'active' : '' }}"
>
    <span class="menu-icon">▧</span>
    <span class="menu-text">Notes</span>
</a>


{{-- Bulletins --}}
<a
    href="{{ route('bulletins.index') }}"
    class="menu-item {{ request()->routeIs('bulletins.*') ? 'active' : '' }}"
>
    <span class="menu-icon">📄</span>
    <span class="menu-text">Bulletins</span>
</a>


{{-- Personnel --}}
<a
    href="{{ route('personnel.index') }}"
    class="menu-item {{ request()->routeIs('personnel.*') ? 'active' : '' }}"
>
    <span class="menu-icon">♟</span>
    <span class="menu-text">Personnel</span>
</a>


{{-- Affectations enseignants --}}
<a
    href="{{ route('affectations-enseignants.index') }}"
    class="menu-item {{ request()->routeIs('affectations-enseignants.*') ? 'active' : '' }}"
>
    <span class="menu-icon">👨‍🏫</span>
    <span class="menu-text">Affectations enseignants</span>
</a>


{{-- ============================================================
     FINANCES
     ============================================================ --}}

<div class="menu-title">
    Finance
</div>


{{-- Catégories de frais --}}
<a
    href="{{ route('categories-frais.index') }}"
    class="menu-item {{ request()->routeIs('categories-frais.*') ? 'active' : '' }}"
>
    <span class="menu-icon">🏷</span>
    <span class="menu-text">Catégories de frais</span>
</a>


{{-- Tarifs scolaires --}}
<a
    href="{{ route('tarifs-scolaires.index') }}"
    class="menu-item {{ request()->routeIs('tarifs-scolaires.*') ? 'active' : '' }}"
>
    <span class="menu-icon">💵</span>
    <span class="menu-text">Tarifs scolaires</span>
</a>


{{-- Paiements --}}
<a
    href="{{ route('paiements.index') }}"
    class="menu-item {{ request()->routeIs('paiements.*') ? 'active' : '' }}"
>
    <span class="menu-icon">₣</span>
    <span class="menu-text">Paiements</span>
</a>


{{-- Recettes --}}
<a
    href="{{ route('recettes.index') }}"
    class="menu-item {{ request()->routeIs('recettes.*') ? 'active' : '' }}"
>
    <span class="menu-icon">↗</span>
    <span class="menu-text">Recettes</span>
</a>


{{-- Dépenses --}}
<a
    href="{{ route('depenses.index') }}"
    class="menu-item {{ request()->routeIs('depenses.*') ? 'active' : '' }}"
>
    <span class="menu-icon">↘</span>
    <span class="menu-text">Dépenses</span>
</a>


{{-- ============================================================
     ADMINISTRATION SCOLAIRE
     ============================================================ --}}

<div class="menu-title">
    Administration scolaire
</div>


{{-- Années scolaires --}}
<a
    href="{{ route('annees-scolaires.index') }}"
    class="menu-item {{ request()->routeIs('annees-scolaires.*') ? 'active' : '' }}"
>
    <span class="menu-icon">📅</span>
    <span class="menu-text">Années scolaires</span>
</a>


{{-- Périodes scolaires --}}
<a
    href="{{ route('periodes-scolaires.index') }}"
    class="menu-item {{ request()->routeIs('periodes-scolaires.*') ? 'active' : '' }}"
>
    <span class="menu-icon">🗓</span>
    <span class="menu-text">Périodes scolaires</span>
</a>


{{-- Infrastructures --}}
<a
    href="{{ route('infrastructures.index') }}"
    class="menu-item {{ request()->routeIs('infrastructures.*') ? 'active' : '' }}"
>
    <span class="menu-icon">🏢</span>
    <span class="menu-text">Infrastructures</span>
</a>


{{-- Journal activités --}}
<a
    href="{{ route('journaux-activites.index') }}"
    class="menu-item {{ request()->routeIs('journaux-activites.*') || request()->routeIs('journal-activites.*') ? 'active' : '' }}"
>
    <span class="menu-icon">📋</span>
    <span class="menu-text">Journal activités</span>
</a>


{{-- ============================================================
     RAPPORTS
     ============================================================ --}}

<div class="menu-title">
    Analyse
</div>


{{-- Rapports --}}
<a
    href="{{ route('rapports.index') }}"
    class="menu-item {{ request()->routeIs('rapports.*') ? 'active' : '' }}"
>
    <span class="menu-icon">▥</span>
    <span class="menu-text">Rapports</span>
</a>


{{-- ============================================================
ADMINISTRATION
RÉSERVÉE AU SUPER ADMINISTRATEUR
============================================================ --}}

@if(Auth::user()->id_etablissement === null)

<div class="menu-title">
    Administration
</div>


{{-- Paramètres système --}}

@if(Route::has('profile.edit'))

    <a
        href="{{ route('profile.edit') }}"
        class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"
    >

        <span class="menu-icon">⚙</span>

        <span class="menu-text">
            Paramètres
        </span>

    </a>

@endif

@endif

</nav>


</aside>



{{-- ============================================================ --}}
{{-- CONTENU PRINCIPAL --}}
{{-- ============================================================ --}}

<main class="main">


    {{-- ======================================================== --}}
    {{-- TOPBAR --}}
    {{-- ======================================================== --}}

    <header class="topbar">

        <div>

            <h1 class="text-xl font-bold text-gray-800">
                Tableau de bord
            </h1>

            <p class="text-xs text-gray-400 mt-1">
                Vue générale de votre établissement
            </p>

        </div>


        <div class="flex items-center gap-5">


            {{-- ANNÉE SCOLAIRE --}}

            @if($anneeScolaire)

                <div class="hidden md:block text-right">

                    <p class="text-xs text-gray-400">
                        Année scolaire
                    </p>

                    <p class="text-sm font-semibold text-gray-700">

                        {{ $anneeScolaire->libelle
                            ?? $anneeScolaire->annee
                            ?? $anneeScolaire->nom
                            ?? 'Année active'
                        }}

                    </p>

                </div>

            @endif


            {{-- NOTIFICATION --}}

            <div class="text-gray-400 text-lg">
                ♧
            </div>

           {{-- ============================================================
            UTILISATEUR CONNECTÉ
            ============================================================ --}}

            <div class="relative">

            <details class="group">

                <summary
                    class="flex items-center gap-3 cursor-pointer list-none select-none"
                >

                    {{-- AVATAR --}}

                    <div
                        class="w-9 h-9 rounded-full bg-blue-100
                            flex items-center justify-center
                            text-blue-700 font-bold"
                    >

                        {{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}

                    </div>


                    {{-- NOM --}}

                    <div class="hidden md:block">

                        <p class="text-sm font-semibold text-gray-700">

                            {{ Auth::user()->nom ?? 'Utilisateur' }}

                        </p>

                        <p class="text-xs text-gray-400">

                            @if(Auth::user()->id_etablissement === null)

                                Super administrateur

                            @elseif(Auth::user()->aLeRole('Directeur'))

                                Directeur

                            @elseif(Auth::user()->aLeRole('Comptable'))

                                Comptable

                            @elseif(Auth::user()->aLeRole('Secretaire'))

                                Secrétaire

                            @elseif(Auth::user()->aLeRole('Enseignant'))

                                Enseignant

                            @else

                                Utilisateur

                            @endif

                        </p>

                    </div>


                    {{-- FLÈCHE --}}

                    <span
                        class="text-gray-400 text-xs
                            transition-transform
                            group-open:rotate-180"
                    >
                        ▼
                    </span>

                </summary>


                {{-- MENU UTILISATEUR --}}

                <div
                    class="absolute right-0 mt-3 w-64
                        bg-white rounded-xl
                        border border-gray-200
                        shadow-xl z-[100]"
                >

                    {{-- INFORMATIONS --}}

                    <div class="px-5 py-4 border-b border-gray-100">

                        <p class="text-sm font-semibold text-gray-800">

                            {{ Auth::user()->nom ?? 'Utilisateur' }}

                        </p>

                        <p class="text-xs text-gray-400 mt-1">

                            {{ Auth::user()->email }}

                        </p>

                    </div>


                    {{-- PROFIL --}}

                    @if(Route::has('profile.edit'))

                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3
                                px-5 py-3
                                text-sm text-gray-600
                                hover:bg-gray-50"
                        >

                            <span>⚙</span>

                            <span>Mon profil</span>

                        </a>

                    @endif


                    {{-- DÉCONNEXION --}}

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="w-full flex items-center gap-3
                                px-5 py-3
                                text-sm text-red-600
                                hover:bg-red-50
                                text-left"
                        >

                            <span>↪</span>

                            <span>Déconnexion</span>

                        </button>

                    </form>

                </div>

            </details>

            </div>


        </div>

    </header>



    {{-- ======================================================== --}}
    {{-- DASHBOARD --}}
    {{-- ======================================================== --}}

    <section class="content">


        {{-- BIENVENUE --}}

        <div class="mb-7">

            <h2 class="text-2xl font-bold text-gray-800">
                Bienvenue dans GESCO
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Gestion Scolaire Complète — aperçu de votre établissement
            </p>

        </div>



        {{-- ==================================================== --}}
        {{-- 4 STATISTIQUES --}}
        {{-- ==================================================== --}}

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">


            {{-- ÉLÈVES --}}

            <div class="stat-card">

                <div class="flex justify-between items-start">

                    <div>

                        <p class="text-sm text-gray-500">
                            Élèves
                        </p>

                        <p class="text-3xl font-bold text-gray-800 mt-2">

                            {{ number_format($nombreEleves, 0, ',', ' ') }}

                        </p>

                        <p class="text-xs text-gray-400 mt-2">
                            Élèves inscrits
                        </p>

                    </div>


                    <div class="stat-icon bg-blue-50 text-blue-600">
                        ♙
                    </div>

                </div>

            </div>



            {{-- ENSEIGNANTS --}}

            <div class="stat-card">

                <div class="flex justify-between items-start">

                    <div>

                        <p class="text-sm text-gray-500">
                            Enseignants
                        </p>

                        <p class="text-3xl font-bold text-gray-800 mt-2">

                            {{ number_format($nombreEnseignants, 0, ',', ' ') }}

                        </p>

                        <p class="text-xs text-gray-400 mt-2">
                            Enseignants actifs
                        </p>

                    </div>


                    <div class="stat-icon bg-green-50 text-green-600">
                        ♟
                    </div>

                </div>

            </div>



            {{-- CLASSES --}}

            <div class="stat-card">

                <div class="flex justify-between items-start">

                    <div>

                        <p class="text-sm text-gray-500">
                            Classes
                        </p>

                        <p class="text-3xl font-bold text-gray-800 mt-2">

                            {{ number_format($nombreClasses, 0, ',', ' ') }}

                        </p>

                        <p class="text-xs text-gray-400 mt-2">
                            Classes actives
                        </p>

                    </div>


                    <div class="stat-icon bg-purple-50 text-purple-600">
                        ▣
                    </div>

                </div>

            </div>



            {{-- FRÉQUENTATION --}}

            <div class="stat-card">

                <div class="flex justify-between items-start">

                    <div>

                        <p class="text-sm text-gray-500">
                            Taux de fréquentation
                        </p>

                        <p class="text-3xl font-bold text-gray-800 mt-2">

                            {{ number_format($tauxFrequentation, 1, ',', ' ') }}%

                        </p>

                        <p class="text-xs text-gray-400 mt-2">
                            Taux de présence
                        </p>

                    </div>


                    <div class="stat-icon bg-orange-50 text-orange-500">
                        ✓
                    </div>

                </div>


                <div class="mt-4 w-full bg-gray-100 h-1.5 rounded-full">

                    <div
                        class="bg-orange-400 h-1.5 rounded-full"
                        style="width: {{ min(100, max(0, $tauxFrequentation)) }}%"
                    ></div>

                </div>

            </div>

        </div>



        {{-- ==================================================== --}}
        {{-- COURBE + PAIEMENTS --}}
        {{-- ==================================================== --}}

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mt-5">


            {{-- ================================================= --}}
            {{-- ÉVOLUTION EFFECTIFS --}}
            {{-- ================================================= --}}

            <div class="panel xl:col-span-2 p-6">

                <div class="flex justify-between items-center mb-6">

                    <div>

                        <h3 class="font-bold text-gray-800 text-lg">
                            Évolution des effectifs
                        </h3>

                        <p class="text-sm text-gray-400 mt-1">
                            Évolution du nombre d'élèves au cours de l'année
                        </p>

                    </div>


                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        ↗
                    </div>

                </div>


                <div class="h-80">

                    <canvas id="effectifsChart"></canvas>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- PAIEMENTS --}}
            {{-- ================================================= --}}

            <div class="panel p-6">

                <div class="flex justify-between items-center">

                    <div>

                        <h3 class="font-bold text-gray-800 text-lg">
                            Paiements
                        </h3>

                        <p class="text-sm text-gray-400 mt-1">
                            Taux de paiement
                        </p>

                    </div>


                    <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                        ₣
                    </div>

                </div>


                {{-- CERCLE --}}

                <div class="flex justify-center py-8">

                    <div class="relative w-48 h-48">


                        <svg
                            class="w-full h-full transform -rotate-90"
                            viewBox="0 0 120 120"
                        >

                            <circle
                                cx="60"
                                cy="60"
                                r="48"
                                fill="none"
                                stroke="#eef2f7"
                                stroke-width="12"
                            />

                            <circle
                                cx="60"
                                cy="60"
                                r="48"
                                fill="none"
                                stroke="#16a34a"
                                stroke-width="12"
                                stroke-linecap="round"
                                stroke-dasharray="301.59"
                                stroke-dashoffset="{{ 301.59 - (301.59 * min(100, max(0, $tauxPaiement)) / 100) }}"
                            />

                        </svg>


                        <div class="absolute inset-0 flex flex-col items-center justify-center">

                            <span class="text-4xl font-bold text-gray-800">

                                {{ number_format($tauxPaiement, 1, ',', ' ') }}%

                            </span>

                            <span class="text-xs text-gray-400 mt-1">
                                Paiement
                            </span>

                        </div>

                    </div>

                </div>


                <div class="border-t border-gray-100 pt-4">


                    <div class="flex justify-between">

                        <span class="text-sm text-gray-500">
                            Élèves concernés
                        </span>

                        <span class="font-semibold text-gray-700">
                            {{ number_format($nombreEleves, 0, ',', ' ') }}
                        </span>

                    </div>


                    <div class="flex justify-between mt-3">

                        <span class="text-sm text-gray-500">
                            Taux de paiement
                        </span>

                        <span class="font-semibold text-green-600">

                            {{ number_format($tauxPaiement, 1, ',', ' ') }}%

                        </span>

                    </div>

                </div>

            </div>

        </div>



        {{-- ==================================================== --}}
        {{-- ACTIVITÉS + RÉSUMÉ --}}
        {{-- ==================================================== --}}

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mt-5">


            {{-- ACTIVITÉS --}}

            <div class="panel">

                <div class="p-6 border-b border-gray-100">

                    <h3 class="font-bold text-gray-800 text-lg">
                        Activités récentes
                    </h3>

                    <p class="text-sm text-gray-400 mt-1">
                        Dernières opérations effectuées
                    </p>

                </div>


                <div>

                    @forelse($activitesRecentes as $activite)

                        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-50">

                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                •
                            </div>

                            <div class="flex-1">

                                <p class="text-sm font-medium text-gray-700">

                                    {{ $activite->description
                                        ?? $activite->action
                                        ?? 'Activité effectuée'
                                    }}

                                </p>

                                <p class="text-xs text-gray-400 mt-1">

                                    @if($activite->date_heure)

                                        {{ \Carbon\Carbon::parse($activite->date_heure)->diffForHumans() }}

                                    @endif

                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="p-8 text-center">

                            <p class="text-sm text-gray-400">
                                Aucune activité récente.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>



            {{-- RÉSUMÉ --}}

            <div class="panel p-6">

                <h3 class="font-bold text-gray-800 text-lg">
                    Résumé de l'établissement
                </h3>

                <p class="text-sm text-gray-400 mt-1 mb-6">
                    Principaux indicateurs
                </p>


                {{-- FRÉQUENTATION --}}

                <div class="mb-6">

                    <div class="flex justify-between mb-2">

                        <span class="text-sm text-gray-600">
                            Fréquentation
                        </span>

                        <span class="font-semibold text-gray-700">

                            {{ number_format($tauxFrequentation, 1, ',', ' ') }}%

                        </span>

                    </div>


                    <div class="h-2 bg-gray-100 rounded-full">

                        <div
                            class="h-2 bg-orange-400 rounded-full"
                            style="width: {{ min(100, max(0, $tauxFrequentation)) }}%"
                        ></div>

                    </div>

                </div>


                {{-- PAIEMENTS --}}

                <div class="mb-6">

                    <div class="flex justify-between mb-2">

                        <span class="text-sm text-gray-600">
                            Paiements
                        </span>

                        <span class="font-semibold text-gray-700">

                            {{ number_format($tauxPaiement, 1, ',', ' ') }}%

                        </span>

                    </div>


                    <div class="h-2 bg-gray-100 rounded-full">

                        <div
                            class="h-2 bg-green-500 rounded-full"
                            style="width: {{ min(100, max(0, $tauxPaiement)) }}%"
                        ></div>

                    </div>

                </div>


                {{-- MINI STATISTIQUES --}}

                <div class="grid grid-cols-2 gap-4">


                    <div class="bg-blue-50 rounded-xl p-4">

                        <p class="text-xs text-gray-500">
                            Élèves
                        </p>

                        <p class="text-xl font-bold text-blue-700 mt-1">

                            {{ number_format($nombreEleves, 0, ',', ' ') }}

                        </p>

                    </div>


                    <div class="bg-purple-50 rounded-xl p-4">

                        <p class="text-xs text-gray-500">
                            Classes
                        </p>

                        <p class="text-xl font-bold text-purple-700 mt-1">

                            {{ number_format($nombreClasses, 0, ',', ' ') }}

                        </p>

                    </div>


                </div>

            </div>

        </div>


    </section>

</main>



{{-- ============================================================ --}}
{{-- COURBE EFFECTIFS --}}
{{-- ============================================================ --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const canvas =
            document.getElementById('effectifsChart');

        if (!canvas) {
            return;
        }


        const labels =
            @json($evolutionLabels);

        const effectifs =
            @json($evolutionEffectifs);


        new Chart(canvas, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [{

                    label: 'Élèves',

                    data: effectifs,

                    borderWidth: 3,

                    tension: 0.35,

                    fill: true,

                    pointRadius: 4,

                    pointHoverRadius: 6

                }]

            },


            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {

                    intersect: false,

                    mode: 'index'

                },


                plugins: {

                    legend: {

                        display: false

                    },


                    tooltip: {

                        callbacks: {

                            label: function(context) {

                                return ' ' +
                                    context.parsed.y +
                                    ' élèves';

                            }

                        }

                    }

                },


                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0

                        },

                        grid: {

                            color: '#eef2f7'

                        }

                    },


                    x: {

                        grid: {

                            display: false

                        }

                    }

                }

            }

        });

    });

</script>


</body>

</html>
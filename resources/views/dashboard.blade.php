<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GESCO — Gestion Scolaire Complète</title>

    {{-- Chart.js uniquement --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fb;
            color: #0f172a;
        }

        a {
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        /* =========================================================
           SIDEBAR
           ========================================================= */

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

        .gesco-logo {
            height: 105px;
            min-height: 105px;

            display: flex;
            align-items: center;

            padding: 20px;

            border-bottom: 1px solid rgba(255, 255, 255, 0.10);

            color: white;
        }

        .gesco-logo svg {
            width: 30px;
            height: 30px;
            min-width: 30px;

            flex-shrink: 0;

            color: #1594f4;

            margin-right: 12px;
        }

        .gesco-logo-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .gesco-logo-title {
            color: #ffffff;
            font-size: 20px;
            line-height: 1;
            font-weight: 700;
        }

        .gesco-logo-subtitle {
            color: #bfdbfe;
            font-size: 12px;
            line-height: 1.2;
        }

        .sidebar-menu {
            padding: 18px 12px;

            flex: 1;

            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin;
            scrollbar-color: #1687e8 #082b52;
        }

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
            color: rgba(255, 255, 255, 0.45);

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

            color: rgba(255, 255, 255, 0.78);

            font-size: 14px;
            font-weight: 500;

            transition:
                background 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .menu-item.active {
            background: #1594f4;
            color: #ffffff;

            box-shadow: 0 4px 12px rgba(21, 148, 244, 0.25);
        }

        .menu-icon {
            width: 20px;
            min-width: 20px;

            text-align: center;

            font-size: 17px;
            line-height: 1;

            flex-shrink: 0;
        }

        .menu-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* =========================================================
           ZONE PRINCIPALE
           ========================================================= */

        .main {
            margin-left: 250px;
            min-height: 100vh;
            background: #f5f7fb;
        }

        /* =========================================================
           TOPBAR
           ========================================================= */

        .topbar {
            height: 72px;

            background: #ffffff;

            border-bottom: 1px solid #e8edf3;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 30px;

            position: relative;
            z-index: 40;
        }

        .topbar-left {
            min-width: 0;
        }

        .page-title {
            margin: 0;

            font-size: 20px;
            line-height: 1.3;

            font-weight: 700;

            color: #1f2937;
        }

        .page-subtitle {
            margin: 4px 0 0;

            font-size: 12px;

            color: #9ca3af;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .school-year {
            text-align: right;
        }

        .school-year-label {
            margin: 0;

            font-size: 11px;

            color: #9ca3af;
        }

        .school-year-value {
            margin: 2px 0 0;

            font-size: 14px;
            font-weight: 600;

            color: #374151;
        }

        .notification-button {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            color: #9ca3af;

            font-size: 18px;
        }

        .notification-button:hover {
            background: #f3f4f6;
            color: #6b7280;
        }

        /* =========================================================
           UTILISATEUR
           ========================================================= */

        .user-menu-wrapper {
            position: relative;
        }

        .user-summary {
            display: flex;
            align-items: center;
            gap: 10px;

            cursor: pointer;

            list-style: none;

            user-select: none;
        }

        .user-summary::-webkit-details-marker {
            display: none;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            min-width: 38px;

            border-radius: 50%;

            background: #dbeafe;
            color: #1d4ed8;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;
            font-weight: 700;
        }

        .user-info {
            min-width: 0;
        }

        .user-name {
            margin: 0;

            font-size: 14px;

            font-weight: 600;

            color: #374151;

            white-space: nowrap;
        }

        .user-role {
            margin: 2px 0 0;

            font-size: 11px;

            color: #9ca3af;
        }

        .user-arrow {
            margin-left: 2px;

            color: #9ca3af;

            font-size: 10px;

            transition: transform 0.2s ease;
        }

        details[open] .user-arrow {
            transform: rotate(180deg);
        }

        .user-dropdown {
            position: absolute;

            top: calc(100% + 12px);
            right: 0;

            width: 260px;

            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 12px;

            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15);

            overflow: hidden;

            z-index: 100;
        }

        .dropdown-user-info {
            padding: 16px 18px;

            border-bottom: 1px solid #f1f5f9;
        }

        .dropdown-user-name {
            margin: 0;

            font-size: 14px;
            font-weight: 600;

            color: #1f2937;
        }

        .dropdown-user-email {
            margin: 4px 0 0;

            font-size: 12px;

            color: #9ca3af;

            word-break: break-word;
        }

        .dropdown-link,
        .dropdown-button {
            width: 100%;

            display: flex;
            align-items: center;

            gap: 12px;

            padding: 12px 18px;

            border: 0;

            background: #ffffff;

            color: #4b5563;

            font-size: 13px;

            text-align: left;

            cursor: pointer;
        }

        .dropdown-link:hover {
            background: #f8fafc;
        }

        .dropdown-button:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        /* =========================================================
           CONTENU
           ========================================================= */

        .content {
            padding: 28px 30px 40px;

            background: #f5f7fb;

            min-height: calc(100vh - 72px);
        }

        .welcome {
            margin-bottom: 26px;
        }

        .welcome-title {
            margin: 0;

            font-size: 26px;
            line-height: 1.25;

            font-weight: 700;

            color: #1f2937;
        }

        .welcome-text {
            margin: 6px 0 0;

            font-size: 14px;

            color: #6b7280;
        }

        /* =========================================================
           STATISTIQUES
           ========================================================= */

        .stats-grid {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 20px;

            margin-bottom: 20px;
        }

        .stat-card {
            background: #ffffff;

            border: 1px solid #e6ebf1;

            border-radius: 14px;

            padding: 20px;

            min-height: 145px;

            box-shadow:
                0 2px 8px rgba(15, 23, 42, 0.04);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);

            box-shadow:
                0 7px 18px rgba(15, 23, 42, 0.08);
        }

        .stat-card-top {
            display: flex;

            align-items: flex-start;
            justify-content: space-between;

            gap: 15px;
        }

        .stat-card-content {
            min-width: 0;
        }

        .stat-card-label {
            margin: 0;

            font-size: 13px;

            color: #6b7280;
        }

        .stat-card-value {
            margin: 7px 0 0;

            font-size: 30px;
            line-height: 1;

            font-weight: 700;

            color: #1f2937;
        }

        .stat-card-description {
            margin: 8px 0 0;

            font-size: 11px;

            color: #9ca3af;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            min-width: 48px;

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 21px;
        }

        .stat-icon-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .stat-icon-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        .stat-icon-purple {
            background: #faf5ff;
            color: #7c3aed;
        }

        .stat-icon-orange {
            background: #fff7ed;
            color: #f97316;
        }

        .progress-wrapper {
            margin-top: 15px;
        }

        .progress-track {
            width: 100%;

            height: 6px;

            background: #f1f5f9;

            border-radius: 999px;

            overflow: hidden;
        }

        .progress-bar {
            height: 100%;

            background: #fb923c;

            border-radius: 999px;

            transition: width 0.4s ease;
        }

        /* =========================================================
           PANNEAUX DASHBOARD
           ========================================================= */

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                minmax(0, 2fr)
                minmax(320px, 1fr);

            gap: 20px;

            margin-top: 20px;
        }

        .panel {
            background: #ffffff;

            border: 1px solid #e6ebf1;

            border-radius: 14px;

            box-shadow:
                0 2px 8px rgba(15, 23, 42, 0.04);

            overflow: hidden;
        }

        .panel-large {
            min-width: 0;
        }

        .panel-padding {
            padding: 24px;
        }

        .panel-header {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 20px;
        }

        .panel-header-text {
            min-width: 0;
        }

        .panel-title {
            margin: 0;

            font-size: 18px;
            line-height: 1.3;

            font-weight: 700;

            color: #1f2937;
        }

        .panel-subtitle {
            margin: 5px 0 0;

            font-size: 12px;

            color: #9ca3af;
        }

        .panel-header-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 17px;
        }

        .panel-icon-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .panel-icon-green {
            background: #f0fdf4;
            color: #16a34a;
        }

        /* =========================================================
           GRAPHIQUE
           ========================================================= */

        .chart-container {
            position: relative;

            width: 100%;

            height: 320px;
        }

        .chart-container canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* =========================================================
           PAIEMENTS
           ========================================================= */

        .payment-circle-wrapper {
            display: flex;

            justify-content: center;

            padding: 20px 0 24px;
        }

        .payment-circle {
            position: relative;

            width: 190px;
            height: 190px;
        }

        .payment-circle svg {
            display: block;

            width: 100%;
            height: 100%;

            transform: rotate(-90deg);
        }

        .payment-circle-center {
            position: absolute;

            inset: 0;

            display: flex;

            flex-direction: column;

            align-items: center;
            justify-content: center;

            text-align: center;
        }

        .payment-percentage {
            font-size: 34px;
            line-height: 1;

            font-weight: 700;

            color: #1f2937;
        }

        .payment-label {
            margin-top: 6px;

            font-size: 11px;

            color: #9ca3af;
        }

        .payment-details {
            border-top: 1px solid #f1f5f9;

            padding-top: 16px;
        }

        .payment-row {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 15px;
        }

        .payment-row + .payment-row {
            margin-top: 12px;
        }

        .payment-row-label {
            font-size: 13px;

            color: #6b7280;
        }

        .payment-row-value {
            font-size: 13px;

            font-weight: 600;

            color: #374151;
        }

        .payment-row-value.green {
            color: #16a34a;
        }

        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 75px;
            }

            .gesco-logo {
                justify-content: center;
                padding: 10px;
            }

            .gesco-logo svg {
                margin-right: 0;
            }

            .gesco-logo-text {
                display: none;
            }

            .menu-text,
            .menu-title {
                display: none;
            }

            .menu-item {
                justify-content: center;

                padding: 13px 5px;
            }

            .menu-icon {
                margin: 0;
            }

            .main {
                margin-left: 75px;
            }

            .topbar {
                padding: 0 20px;
            }

            .content {
                padding: 20px;
            }

            .user-info,
            .user-arrow,
            .school-year {
                display: none;
            }
        }

        @media (max-width: 650px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                height: auto;
                min-height: 72px;

                padding-top: 14px;
                padding-bottom: 14px;
            }

            .topbar-right {
                gap: 8px;
            }

            .notification-button {
                display: none;
            }

            .welcome-title {
                font-size: 22px;
            }

            .panel-padding {
                padding: 18px;
            }

            .chart-container {
                height: 260px;
            }
        }

        /* =========================================================
        ÉTABLISSEMENT + ANNÉE SCOLAIRE - TOPBAR
        ========================================================= */

        .topbar-school-info {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .topbar-school-block {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .topbar-info-label {
            font-size: 10px;
            color: #9ca3af;
            line-height: 1.2;
        }

        .topbar-info-value {
            margin-top: 2px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            white-space: nowrap;
        }

        @media (max-width: 900px) {
            .topbar-school-info {
                display: none;
            }
        }
    </style>
</head>

<body>

{{-- ============================================================
SIDEBAR
============================================================ --}}

<aside class="sidebar">

    {{-- LOGO --}}
    <div class="gesco-logo">

        <svg
            xmlns="http://www.w3.org/2000/svg"
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

        <div class="gesco-logo-text">
            <div class="gesco-logo-title">
                GESCO
            </div>

            <div class="gesco-logo-subtitle">
                Gestion Scolaire Complète
            </div>
        </div>

    </div>


    {{-- MENU --}}
    <nav class="sidebar-menu">

        <div class="menu-title">
            Principal
        </div>

        <a
            href="{{ route('dashboard') }}"
            class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            <span class="menu-icon">⌂</span>
            <span class="menu-text">Tableau de bord</span>
        </a>

        <a
            href="{{ route('eleves.index') }}"
            class="menu-item {{ request()->routeIs('eleves.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">♙</span>
            <span class="menu-text">Élèves</span>
        </a>

        <a
            href="{{ route('classes.index') }}"
            class="menu-item {{ request()->routeIs('classes.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">▣</span>
            <span class="menu-text">Classes</span>
        </a>

        <a
            href="{{ route('inscriptions.index') }}"
            class="menu-item {{ request()->routeIs('inscriptions.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">📝</span>
            <span class="menu-text">Inscriptions</span>
        </a>

        <a
            href="{{ route('presences.index') }}"
            class="menu-item {{ request()->routeIs('presences.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">✓</span>
            <span class="menu-text">Présences</span>
        </a>

        <a
            href="{{ route('matieres.index') }}"
            class="menu-item {{ request()->routeIs('matieres.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">▤</span>
            <span class="menu-text">Matières</span>
        </a>

        <a
            href="{{ route('evaluations.index') }}"
            class="menu-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">📝</span>
            <span class="menu-text">Évaluations</span>
        </a>

        <a
            href="{{ route('notes.index') }}"
            class="menu-item {{ request()->routeIs('notes.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">▧</span>
            <span class="menu-text">Notes</span>
        </a>

        <a
            href="{{ route('bulletins.index') }}"
            class="menu-item {{ request()->routeIs('bulletins.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">📄</span>
            <span class="menu-text">Bulletins</span>
        </a>

        <a
            href="{{ route('personnel.index') }}"
            class="menu-item {{ request()->routeIs('personnel.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">♟</span>
            <span class="menu-text">Personnel</span>
        </a>

        <a
            href="{{ route('affectations-enseignants.index') }}"
            class="menu-item {{ request()->routeIs('affectations-enseignants.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">👨‍🏫</span>
            <span class="menu-text">Affectations enseignants</span>
        </a>


        <div class="menu-title">
            Finance
        </div>

        <a
            href="{{ route('categories-frais.index') }}"
            class="menu-item {{ request()->routeIs('categories-frais.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">🏷</span>
            <span class="menu-text">Catégories de frais</span>
        </a>

        <a
            href="{{ route('tarifs-scolaires.index') }}"
            class="menu-item {{ request()->routeIs('tarifs-scolaires.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">💵</span>
            <span class="menu-text">Tarifs scolaires</span>
        </a>

        <a
            href="{{ route('paiements.index') }}"
            class="menu-item {{ request()->routeIs('paiements.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">₣</span>
            <span class="menu-text">Paiements</span>
        </a>

        <a
            href="{{ route('recettes.index') }}"
            class="menu-item {{ request()->routeIs('recettes.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">↗</span>
            <span class="menu-text">Recettes</span>
        </a>

        <a
            href="{{ route('depenses.index') }}"
            class="menu-item {{ request()->routeIs('depenses.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">↘</span>
            <span class="menu-text">Dépenses</span>
        </a>


        <div class="menu-title">
            Administration scolaire
        </div>

        <a
            href="{{ route('annees-scolaires.index') }}"
            class="menu-item {{ request()->routeIs('annees-scolaires.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">📅</span>
            <span class="menu-text">Années scolaires</span>
        </a>

        <a
            href="{{ route('periodes-scolaires.index') }}"
            class="menu-item {{ request()->routeIs('periodes-scolaires.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">🗓</span>
            <span class="menu-text">Périodes scolaires</span>
        </a>

        <a
            href="{{ route('infrastructures.index') }}"
            class="menu-item {{ request()->routeIs('infrastructures.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">🏢</span>
            <span class="menu-text">Infrastructures</span>
        </a>

        <a
            href="{{ route('journaux-activites.index') }}"
            class="menu-item {{
                request()->routeIs('journaux-activites.*')
                || request()->routeIs('journal-activites.*')
                    ? 'active'
                    : ''
            }}"
        >
            <span class="menu-icon">📋</span>
            <span class="menu-text">Journal activités</span>
        </a>


        <div class="menu-title">
            Analyse
        </div>

        <a
            href="{{ route('rapports.index') }}"
            class="menu-item {{ request()->routeIs('rapports.*') ? 'active' : '' }}"
        >
            <span class="menu-icon">▥</span>
            <span class="menu-text">Rapports</span>
        </a>


        @if(Auth::user()->id_etablissement === null)

            <div class="menu-title">
                Administration
            </div>

            @if(Route::has('profile.edit'))

                <a
                    href="{{ route('profile.edit') }}"
                    class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                >
                    <span class="menu-icon">⚙</span>
                    <span class="menu-text">Paramètres</span>
                </a>

            @endif

        @endif

    </nav>

</aside>


{{-- ============================================================
CONTENU PRINCIPAL
============================================================ --}}

<main class="main">


    {{-- ========================================================
    TOPBAR
    ======================================================== --}}

    <header class="topbar">

        <div class="topbar-left">

            <h1 class="page-title">
                Tableau de bord
            </h1>

            <p class="page-subtitle">
                Vue générale de votre établissement
            </p>

        </div>


        <div class="topbar-right">


            {{-- ÉTABLISSEMENT + ANNÉE SCOLAIRE --}}
            <div class="topbar-school-info">

                @if(Auth::user()->id_etablissement !== null)

                    @php
                        $etablissementTopbar = \App\Models\Etablissement::find(
                            Auth::user()->id_etablissement
                        );
                    @endphp

                    @if($etablissementTopbar)

                        <div class="topbar-school-block">
                            <span class="topbar-info-value">
                                {{ $etablissementTopbar->nom }}
                            </span>

                        </div>

                    @endif

                @endif


                @if($anneeScolaire)

                    <div class="topbar-school-block">

                        <span class="topbar-info-label">
                            Année scolaire
                        </span>

                        <span class="topbar-info-value">
                            {{
                                $anneeScolaire->libelle
                                ?? $anneeScolaire->annee
                                ?? $anneeScolaire->nom
                                ?? 'Année active'
                            }}
                        </span>

                    </div>

                @endif

            </div>

            {{-- NOTIFICATION --}}
            <div class="notification-button">
                ♧
            </div>


            {{-- UTILISATEUR --}}
            <div class="user-menu-wrapper">

                <details>

                    <summary class="user-summary">

                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->nom ?? 'U', 0, 1)) }}
                        </div>

                        <div class="user-info">

                            <p class="user-name">
                                {{ Auth::user()->nom ?? 'Utilisateur' }}
                            </p>

                            <p class="user-role">

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

                        <span class="user-arrow">
                            ▼
                        </span>

                    </summary>


                    <div class="user-dropdown">

                        <div class="dropdown-user-info">

                            <p class="dropdown-user-name">
                                {{ Auth::user()->nom ?? 'Utilisateur' }}
                            </p>

                            <p class="dropdown-user-email">
                                {{ Auth::user()->email }}
                            </p>

                        </div>


                        @if(Route::has('profile.edit'))

                            <a
                                href="{{ route('profile.edit') }}"
                                class="dropdown-link"
                            >
                                <span>⚙</span>
                                <span>Mon profil</span>
                            </a>

                        @endif


                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-button"
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


    {{-- ========================================================
    DASHBOARD
    ======================================================== --}}

    <section class="content">


        {{-- BIENVENUE --}}
        <div class="welcome">

            <h2 class="welcome-title">
                Bienvenue dans GESCO
            </h2>

            <p class="welcome-text">
                Gestion Scolaire Complète — aperçu de votre établissement
            </p>

        </div>


        {{-- ====================================================
        STATISTIQUES
        ==================================================== --}}

        <div class="stats-grid">


            {{-- ÉLÈVES --}}
            <div class="stat-card">

                <div class="stat-card-top">

                    <div class="stat-card-content">

                        <p class="stat-card-label">
                            Élèves
                        </p>

                        <p class="stat-card-value">
                            {{ number_format($nombreEleves, 0, ',', ' ') }}
                        </p>

                        <p class="stat-card-description">
                            Élèves inscrits
                        </p>

                    </div>

                    <div class="stat-icon stat-icon-blue">
                        ♙
                    </div>

                </div>

            </div>


            {{-- ENSEIGNANTS --}}
            <div class="stat-card">

                <div class="stat-card-top">

                    <div class="stat-card-content">

                        <p class="stat-card-label">
                            Enseignants
                        </p>

                        <p class="stat-card-value">
                            {{ number_format($nombreEnseignants, 0, ',', ' ') }}
                        </p>

                        <p class="stat-card-description">
                            Enseignants actifs
                        </p>

                    </div>

                    <div class="stat-icon stat-icon-green">
                        ♟
                    </div>

                </div>

            </div>


            {{-- CLASSES --}}
            <div class="stat-card">

                <div class="stat-card-top">

                    <div class="stat-card-content">

                        <p class="stat-card-label">
                            Classes
                        </p>

                        <p class="stat-card-value">
                            {{ number_format($nombreClasses, 0, ',', ' ') }}
                        </p>

                        <p class="stat-card-description">
                            Classes actives
                        </p>

                    </div>

                    <div class="stat-icon stat-icon-purple">
                        ▣
                    </div>

                </div>

            </div>


            {{-- FRÉQUENTATION --}}
            <div class="stat-card">

                <div class="stat-card-top">

                    <div class="stat-card-content">

                        <p class="stat-card-label">
                            Taux de fréquentation
                        </p>

                        <p class="stat-card-value">
                            {{ number_format($tauxFrequentation, 1, ',', ' ') }}%
                        </p>

                        <p class="stat-card-description">
                            Taux de présence
                        </p>

                    </div>

                    <div class="stat-icon stat-icon-orange">
                        ✓
                    </div>

                </div>


                <div class="progress-wrapper">

                    <div class="progress-track">

                        <div
                            class="progress-bar"
                            style="width: {{ min(100, max(0, $tauxFrequentation)) }}%"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ====================================================
        COURBE + PAIEMENTS
        ==================================================== --}}

        <div class="dashboard-grid">


            {{-- ÉVOLUTION EFFECTIFS --}}
            <div class="panel panel-large">

                <div class="panel-padding">

                    <div class="panel-header">

                        <div class="panel-header-text">

                            <h3 class="panel-title">
                                Évolution des effectifs
                            </h3>

                            <p class="panel-subtitle">
                                Évolution du nombre d'élèves au cours de l'année
                            </p>

                        </div>

                        <div class="panel-header-icon panel-icon-blue">
                            ↗
                        </div>

                    </div>


                    <div class="chart-container">

                        <canvas id="effectifsChart"></canvas>

                    </div>

                </div>

            </div>


            {{-- PAIEMENTS --}}
            <div class="panel">

                <div class="panel-padding">

                    <div class="panel-header">

                        <div class="panel-header-text">

                            <h3 class="panel-title">
                                Paiements
                            </h3>

                            <p class="panel-subtitle">
                                Taux de paiement
                            </p>

                        </div>

                        <div class="panel-header-icon panel-icon-green">
                            ₣
                        </div>

                    </div>


                    {{-- CERCLE --}}
                    <div class="payment-circle-wrapper">

                        <div class="payment-circle">

                            <svg viewBox="0 0 120 120">

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
                                    stroke-dashoffset="{{
                                        301.59
                                        - (
                                            301.59
                                            * min(100, max(0, $tauxPaiement))
                                            / 100
                                        )
                                    }}"
                                />

                            </svg>


                            <div class="payment-circle-center">

                                <span class="payment-percentage">
                                    {{ number_format($tauxPaiement, 1, ',', ' ') }}%
                                </span>

                                <span class="payment-label">
                                    Paiement
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- DÉTAILS --}}
                    <div class="payment-details">

                        <div class="payment-row">

                            <span class="payment-row-label">
                                Élèves concernés
                            </span>

                            <span class="payment-row-value">
                                {{ number_format($nombreEleves, 0, ',', ' ') }}
                            </span>

                        </div>


                        <div class="payment-row">

                            <span class="payment-row-label">
                                Taux de paiement
                            </span>

                            <span class="payment-row-value green">
                                {{ number_format($tauxPaiement, 1, ',', ' ') }}%
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>


{{-- ============================================================
GRAPHIQUE EFFECTIFS
============================================================ --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const canvas = document.getElementById('effectifsChart');

        if (!canvas) {
            return;
        }

        const labels = @json($evolutionLabels);
        const effectifs = @json($evolutionEffectifs);

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

                            label: function (context) {

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
{{-- ============================================================
     SIDEBAR PRINCIPALE GESCO
     ============================================================ --}}

<aside
    class="fixed inset-y-0 left-0 z-50 w-64
           bg-blue-950 text-white
           flex flex-col shadow-xl"
>

    {{-- ========================================================
         EN-TÊTE / LOGO
         ======================================================== --}}
    <div class="h-20 flex-shrink-0 px-5 flex items-center border-b border-blue-900">

        <a
            href="{{ route('dashboard') }}"
            class="flex items-center gap-3 w-full"
        >

            <div
                class="w-11 h-11 rounded-lg bg-white
                       flex items-center justify-center
                       shadow-sm flex-shrink-0"
            >
                <!-- Logo -->
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-indigo-600 shadow-lg">
                    <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-7"
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

            </div>

            <div class="min-w-0">

                <div class="text-xl font-bold tracking-wide">
                    GESCO
                </div>

                <div class="text-xs text-blue-200">
                    Gestion scolaire
                </div>

            </div>

        </a>

    </div>


    {{-- ========================================================
         MENU
         ======================================================== --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto">

        {{-- ====================================================
             TABLEAU DE BORD
             ==================================================== --}}
        <div class="mb-5">

            <a
                href="{{ route('dashboard') }}"
                class="
                    flex items-center gap-3
                    px-4 py-3 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('dashboard')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >

                <span class="w-6 text-center text-lg">
                    🏠
                </span>

                <span class="font-medium">
                    Tableau de bord
                </span>

            </a>

        </div>


        {{-- ====================================================
             GESTION SCOLAIRE
             ==================================================== --}}
        <div class="mb-5">

            <p class="px-4 mb-2 text-xs font-semibold
                      uppercase tracking-wider text-blue-300">
                Gestion scolaire
            </p>


            {{-- Classes --}}
            <a
                href="{{ route('classes.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('classes.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">🏫</span>
                <span>Classes</span>
            </a>


            {{-- Élèves --}}
            <a
                href="{{ route('eleves.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('eleves.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">👨‍🎓</span>
                <span>Élèves</span>
            </a>


            {{-- Inscriptions --}}
            <a
                href="{{ route('inscriptions.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('inscriptions.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📋</span>
                <span>Inscriptions</span>
            </a>


            {{-- Présences --}}
            <a
                href="{{ route('presences.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('presences.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📅</span>
                <span>Présences</span>
            </a>


            {{-- Matières --}}
            <a
                href="{{ route('matieres.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('matieres.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📚</span>
                <span>Matières</span>
            </a>


            {{-- Évaluations --}}
            <a
                href="{{ route('evaluations.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('evaluations.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📝</span>
                <span>Évaluations</span>
            </a>


            {{-- Notes --}}
            <a
                href="{{ route('notes.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('notes.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">🧮</span>
                <span>Notes</span>
            </a>


            {{-- Bulletins --}}
            <a
                href="{{ route('bulletins.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('bulletins.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📄</span>
                <span>Bulletins</span>
            </a>

        </div>


        {{-- ====================================================
             PERSONNEL
             ==================================================== --}}
        <div class="mb-5">

            <p class="px-4 mb-2 text-xs font-semibold
                      uppercase tracking-wider text-blue-300">
                Personnel
            </p>


            {{-- Personnel --}}
            <a
                href="{{ route('personnel.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('personnel.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">👨‍🏫</span>
                <span>Personnel</span>
            </a>


            {{-- Affectations enseignants --}}
            <a
                href="{{ route('affectations-enseignants.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('affectations-enseignants.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">🔗</span>

                <span class="leading-tight">
                    Affectations enseignants
                </span>
            </a>

        </div>


        {{-- ====================================================
             FINANCES
             ==================================================== --}}
        <div class="mb-5">

            <p class="px-4 mb-2 text-xs font-semibold
                      uppercase tracking-wider text-blue-300">
                Finances
            </p>


            {{-- Catégories frais --}}
            <a
                href="{{ route('categories-frais.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('categories-frais.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">🧾</span>

                <span class="leading-tight">
                    Catégories frais scolaires
                </span>
            </a>


            {{-- Tarifs scolaires --}}
            <a
                href="{{ route('tarifs-scolaires.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('tarifs-scolaires.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">💵</span>

                <span>
                    Frais scolaires / Tarifs
                </span>
            </a>


            {{-- Paiements --}}
            <a
                href="{{ route('paiements.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('paiements.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">💰</span>
                <span>Paiements</span>
            </a>


            {{-- Recettes --}}
            <a
                href="{{ route('recettes.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('recettes.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📈</span>
                <span>Recettes</span>
            </a>


            {{-- Dépenses --}}
            <a
                href="{{ route('depenses.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('depenses.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📉</span>
                <span>Dépenses</span>
            </a>

        </div>


        {{-- ====================================================
             ADMINISTRATION
             ==================================================== --}}
        <div class="mb-5">

            <p class="px-4 mb-2 text-xs font-semibold
                      uppercase tracking-wider text-blue-300">
                Administration
            </p>


            {{-- Années scolaires --}}
            <a
                href="{{ route('annees-scolaires.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('annees-scolaires.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📅</span>
                <span>Années scolaires</span>
            </a>


            {{-- Périodes scolaires --}}
            <a
                href="{{ route('periodes-scolaires.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('periodes-scolaires.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">🗓️</span>
                <span>Périodes scolaires</span>
            </a>


            {{-- Infrastructures --}}
            <a
                href="{{ route('infrastructures.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('infrastructures.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">🏢</span>
                <span>Infrastructures</span>
            </a>


            {{-- Journal activités --}}
            <a
                href="{{ route('journal-activites.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('journal-activites.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📋</span>
                <span>Journal activités</span>
            </a>


            {{-- Rapports --}}
            <a
                href="{{ route('rapports.index') }}"
                class="
                    flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    transition duration-150
                    {{ request()->routeIs('rapports.*')
                        ? 'bg-blue-500 text-white shadow-sm'
                        : 'bg-transparent text-white hover:bg-blue-900' }}
                "
            >
                <span class="w-6 text-center">📊</span>
                <span>Rapports</span>
            </a>

        </div>

    </nav>


    {{-- ========================================================
         UTILISATEUR / DÉCONNEXION
         ======================================================== --}}
    @auth

        <div class="flex-shrink-0 border-t border-blue-900 p-4">

            <div class="flex items-center gap-3 mb-3">

                <div
                    class="w-10 h-10 rounded-full
                           bg-blue-500
                           flex items-center justify-center
                           flex-shrink-0"
                >
                    <span class="font-bold text-white">

                        {{ strtoupper(
                            substr(
                                Auth::user()?->nom ?? 'U',
                                0,
                                1
                            )
                        ) }}

                    </span>
                </div>


                <div class="min-w-0">

                    <p class="font-medium text-white truncate">

                        {{ Auth::user()?->nom ?? 'Utilisateur' }}

                    </p>

                    <p class="text-xs text-blue-300 truncate">

                        {{ Auth::user()?->email ?? '' }}

                    </p>

                </div>

            </div>


            {{-- Profil --}}
            <a
                href="{{ route('profile.edit') }}"
                class="
                    w-full flex items-center gap-3
                    px-4 py-2.5 rounded-lg
                    text-white
                    hover:bg-blue-900
                    transition duration-150
                    mb-1
                "
            >
                <span class="w-6 text-center">⚙️</span>
                <span>Paramètres</span>
            </a>


            {{-- Déconnexion --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="
                        w-full flex items-center gap-3
                        px-4 py-2.5 rounded-lg
                        text-white
                        hover:bg-red-600
                        transition duration-150
                    "
                >

                    <span class="w-6 text-center">
                        🚪
                    </span>

                    <span>
                        Déconnexion
                    </span>

                </button>

            </form>

        </div>

    @endauth

</aside>
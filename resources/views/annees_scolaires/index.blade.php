<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Années scolaires
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Gestion des années scolaires de l'établissement
                </p>

            </div>


            {{-- Nouvelle année scolaire --}}
            <a
                href="{{ route('annees-scolaires.create') }}"
                class="inline-flex items-center justify-center px-4 py-2
                       bg-gray-800 border border-transparent rounded-md
                       font-semibold text-xs text-white uppercase tracking-widest
                       hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900
                       focus:outline-none focus:ring-2 focus:ring-gray-500
                       focus:ring-offset-2 transition ease-in-out duration-150"
            >
                + Nouvelle année scolaire
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- =========================================================
                 MESSAGE DE SUCCÈS
                 ========================================================= --}}
            @if(session('success'))

                <div
                    class="mb-6 bg-green-50 border border-green-200
                           text-green-800 px-4 py-3 rounded-lg"
                >

                    <div class="flex items-center gap-2">

                        <span class="text-lg">
                            ✓
                        </span>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                </div>

            @endif


            {{-- =========================================================
                 MESSAGE D'ERREUR
                 ========================================================= --}}
            @if(session('error'))

                <div
                    class="mb-6 bg-red-50 border border-red-200
                           text-red-800 px-4 py-3 rounded-lg"
                >

                    <div class="flex items-center gap-2">

                        <span class="text-lg">
                            ⚠
                        </span>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>

                </div>

            @endif


            {{-- =========================================================
                 ERREURS DE VALIDATION
                 ========================================================= --}}
            @if($errors->any())

                <div
                    class="mb-6 bg-red-50 border border-red-200
                           text-red-800 px-4 py-3 rounded-lg"
                >

                    <div class="font-semibold mb-2">
                        Impossible d'effectuer cette opération :
                    </div>


                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =========================================================
                 LÉGENDE
                 ========================================================= --}}
            <div class="mb-5 bg-white shadow-sm rounded-lg border border-gray-100">

                <div class="px-5 py-4">

                    <div class="flex flex-wrap items-center gap-5 text-sm">

                        <div class="flex items-center gap-2">

                            <span
                                class="w-3 h-3 rounded-full bg-green-500"
                            ></span>

                            <span class="text-gray-600">
                                Année active
                            </span>

                        </div>


                        <div class="flex items-center gap-2">

                            <span
                                class="w-3 h-3 rounded-full bg-gray-400"
                            ></span>

                            <span class="text-gray-600">
                                Année inactive
                            </span>

                        </div>


                        <div class="flex items-center gap-2">

                            <span
                                class="w-3 h-3 rounded-full bg-red-500"
                            ></span>

                            <span class="text-gray-600">
                                Année clôturée
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 TABLEAU
                 ========================================================= --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            {{-- =================================================
                                 EN-TÊTE
                                 ================================================= --}}
                            <thead class="bg-gray-50">

                                <tr>

                                    <th
                                        class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider"
                                    >
                                        #
                                    </th>


                                    <th
                                        class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider"
                                    >
                                        Année scolaire
                                    </th>


                                    <th
                                        class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider"
                                    >
                                        Date de début
                                    </th>


                                    <th
                                        class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider"
                                    >
                                        Date de fin
                                    </th>


                                    <th
                                        class="px-6 py-3 text-left text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider"
                                    >
                                        État
                                    </th>


                                    <th
                                        class="px-6 py-3 text-right text-xs
                                               font-medium text-gray-500
                                               uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            {{-- =================================================
                                 CORPS DU TABLEAU
                                 ================================================= --}}
                            <tbody class="bg-white divide-y divide-gray-200">


                                @forelse($anneesScolaires as $annee)


                                    <tr class="hover:bg-gray-50">


                                        {{-- =====================================
                                             NUMÉRO
                                             ===================================== --}}
                                        <td
                                            class="px-6 py-4 whitespace-nowrap
                                                   text-sm text-gray-500"
                                        >
                                            {{ $loop->iteration }}
                                        </td>


                                        {{-- =====================================
                                             ANNÉE
                                             ===================================== --}}
                                        <td
                                            class="px-6 py-4 whitespace-nowrap"
                                        >

                                            <div
                                                class="text-sm font-semibold
                                                       text-gray-900"
                                            >
                                                {{ $annee->libelle }}
                                            </div>


                                            @if((int) ($annee->est_cloturee ?? 0) === 1)

                                                <div
                                                    class="text-xs text-red-600
                                                           mt-1"
                                                >
                                                    Année historique
                                                </div>

                                            @endif

                                        </td>


                                        {{-- =====================================
                                             DATE DE DÉBUT
                                             ===================================== --}}
                                        <td
                                            class="px-6 py-4 whitespace-nowrap
                                                   text-sm text-gray-600"
                                        >

                                            {{ \Carbon\Carbon::parse($annee->date_debut)->format('d/m/Y') }}

                                        </td>


                                        {{-- =====================================
                                             DATE DE FIN
                                             ===================================== --}}
                                        <td
                                            class="px-6 py-4 whitespace-nowrap
                                                   text-sm text-gray-600"
                                        >

                                            {{ \Carbon\Carbon::parse($annee->date_fin)->format('d/m/Y') }}

                                        </td>


                                        {{-- =====================================
                                             ÉTAT
                                             ===================================== --}}
                                        <td
                                            class="px-6 py-4 whitespace-nowrap"
                                        >


                                            {{-- ANNÉE CLÔTURÉE --}}
                                            @if((int) ($annee->est_cloturee ?? 0) === 1)


                                                <div class="flex flex-col gap-1">

                                                    <span
                                                        class="inline-flex items-center
                                                               w-fit px-2.5 py-0.5
                                                               rounded-full
                                                               text-xs font-medium
                                                               bg-red-100 text-red-800"
                                                    >
                                                        🔒 Clôturée
                                                    </span>


                                                    @if($annee->date_cloture)

                                                        <span
                                                            class="text-xs text-gray-500"
                                                        >
                                                            Le
                                                            {{ \Carbon\Carbon::parse($annee->date_cloture)->format('d/m/Y à H:i') }}
                                                        </span>

                                                    @endif

                                                </div>


                                            {{-- ANNÉE ACTIVE --}}
                                            @elseif((int) $annee->est_active === 1)


                                                <span
                                                    class="inline-flex items-center
                                                           px-2.5 py-0.5
                                                           rounded-full
                                                           text-xs font-medium
                                                           bg-green-100
                                                           text-green-800"
                                                >
                                                    ● Active
                                                </span>


                                            {{-- ANNÉE INACTIVE --}}
                                            @else


                                                <span
                                                    class="inline-flex items-center
                                                           px-2.5 py-0.5
                                                           rounded-full
                                                           text-xs font-medium
                                                           bg-gray-100
                                                           text-gray-700"
                                                >
                                                    ● Inactive
                                                </span>


                                            @endif

                                        </td>


                                        {{-- =====================================
                                             ACTIONS
                                             ===================================== --}}
                                        <td
                                            class="px-6 py-4 whitespace-nowrap
                                                   text-right text-sm font-medium"
                                        >

                                            <div
                                                class="flex justify-end
                                                       items-center gap-2
                                                       flex-wrap"
                                            >


                                                {{-- =================================
                                                     MODIFIER
                                                     ================================= --}}
                                                @if(
                                                    (int) ($annee->est_cloturee ?? 0)
                                                    !== 1
                                                )


                                                    <a
                                                        href="{{ route('annees-scolaires.edit', $annee->id_annee_scolaire) }}"
                                                        class="px-3 py-1.5
                                                               rounded-md
                                                               bg-gray-600
                                                               text-white
                                                               hover:bg-gray-700
                                                               transition"
                                                    >
                                                        Modifier
                                                    </a>


                                                @else


                                                    <span
                                                        class="px-3 py-1.5
                                                               rounded-md
                                                               bg-gray-100
                                                               text-gray-400
                                                               cursor-not-allowed"
                                                        title="Cette année scolaire est clôturée."
                                                    >
                                                        Modifier
                                                    </span>


                                                @endif


                                                {{-- =================================
                                                     CLÔTURER
                                                     ================================= --}}
                                                @if(
                                                    (int) ($annee->est_cloturee ?? 0)
                                                    !== 1
                                                    &&
                                                    (int) $annee->est_active === 1
                                                )


                                                    <form
                                                        action="{{ route('annees-scolaires.cloturer', $annee->id_annee_scolaire) }}"
                                                        method="POST"
                                                        onsubmit="return confirm(
                                                            'ATTENTION : cette opération est définitive.\\n\\n' +
                                                            'L’année scolaire {{ $annee->libelle }} sera clôturée et ne pourra plus être modifiée.\\n\\n' +
                                                            'Voulez-vous continuer ?'
                                                        );"
                                                    >

                                                        @csrf


                                                        <button
                                                            type="submit"
                                                            class="px-3 py-1.5
                                                                   rounded-md
                                                                   bg-orange-600
                                                                   text-white
                                                                   hover:bg-orange-700
                                                                   transition"
                                                        >
                                                            🔒 Clôturer
                                                        </button>

                                                    </form>


                                                @endif


                                                {{-- =================================
                                                     SUPPRIMER
                                                     ================================= --}}
                                                @if(
                                                    (int) $annee->est_active !== 1
                                                    &&
                                                    (int) ($annee->est_cloturee ?? 0)
                                                    !== 1
                                                )


                                                    <form
                                                        action="{{ route('annees-scolaires.destroy', $annee->id_annee_scolaire) }}"
                                                        method="POST"
                                                        onsubmit="return confirm(
                                                            'Voulez-vous vraiment supprimer cette année scolaire ?'
                                                        );"
                                                    >

                                                        @csrf

                                                        @method('DELETE')


                                                        <button
                                                            type="submit"
                                                            class="px-3 py-1.5
                                                                   rounded-md
                                                                   bg-red-600
                                                                   text-white
                                                                   hover:bg-red-700
                                                                   transition"
                                                        >
                                                            Supprimer
                                                        </button>

                                                    </form>


                                                @else


                                                    <span
                                                        class="px-3 py-1.5
                                                               rounded-md
                                                               bg-gray-100
                                                               text-gray-400
                                                               cursor-not-allowed"
                                                        title="Une année active ou clôturée ne peut pas être supprimée."
                                                    >
                                                        Supprimer
                                                    </span>


                                                @endif


                                            </div>

                                        </td>


                                    </tr>


                                @empty


                                    {{-- =========================================
                                         AUCUNE ANNÉE
                                         ========================================= --}}
                                    <tr>

                                        <td
                                            colspan="6"
                                            class="px-6 py-12 text-center"
                                        >

                                            <div class="text-gray-500">


                                                <div
                                                    class="text-lg font-semibold
                                                           mb-2"
                                                >
                                                    Aucune année scolaire
                                                </div>


                                                <p
                                                    class="text-sm mb-4"
                                                >
                                                    Aucune année scolaire
                                                    n'a encore été enregistrée.
                                                </p>


                                                <a
                                                    href="{{ route('annees-scolaires.create') }}"
                                                    class="inline-flex
                                                           items-center
                                                           px-4 py-2
                                                           bg-gray-800
                                                           text-white
                                                           rounded-md
                                                           hover:bg-gray-700"
                                                >
                                                    Ajouter une année scolaire
                                                </a>


                                            </div>

                                        </td>

                                    </tr>


                                @endforelse


                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
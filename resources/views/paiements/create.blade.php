<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800">
                Nouveau paiement
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Enregistrer un nouveau paiement scolaire
            </p>
        </div>

        <a href="{{ route('paiements.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
            Retour
        </a>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white shadow-sm rounded-xl overflow-hidden">

            <form method="POST"
                  action="{{ route('paiements.store') }}">

                @csrf


                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-6">
                        Informations du paiement
                    </h3>


                    {{-- Messages d'erreur --}}

                    @if ($errors->any())

                        <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">

                            <ul class="list-disc list-inside">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    @if (session('error'))

                        <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">

                            {{ session('error') }}

                        </div>

                    @endif


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- Élève --}}

                        <div class="md:col-span-2">

                            <label for="id_eleve"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Élève *

                            </label>


                            <select
                                id="id_eleve"
                                name="id_eleve"
                                required
                                class="w-full rounded-lg border-gray-300">

                                <option value="">
                                    -- Sélectionner un élève --
                                </option>


                                @foreach ($eleves as $eleve)

                                    <option
                                        value="{{ $eleve->id_eleve }}"
                                        {{ old('id_eleve') == $eleve->id_eleve ? 'selected' : '' }}>

                                        {{ $eleve->matricule }}
                                        -
                                        {{ $eleve->nom }}
                                        {{ $eleve->postnom }}
                                        {{ $eleve->prenom }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Zone des frais --}}

                        <div class="md:col-span-2">

                            <div class="flex items-center justify-between mb-3">

                                <div>

                                    <h4 class="font-semibold text-gray-800">
                                        Frais scolaires
                                    </h4>

                                    <p class="text-sm text-gray-500">
                                        Sélectionnez les frais concernés par ce paiement.
                                    </p>

                                </div>

                            </div>


                            <div id="chargement-frais"
                                 class="hidden bg-blue-50 border border-blue-200 text-blue-700 rounded-lg p-4">

                                Chargement des frais de l'élève...

                            </div>


                            <div id="message-frais"
                                 class="bg-gray-50 border border-gray-200 rounded-lg p-5 text-center text-gray-500">

                                Sélectionnez un élève pour afficher ses frais.

                            </div>


                            <div id="liste-frais"
                                 class="hidden space-y-3">
                            </div>

                        </div>
                    {{-- Frais scolaires de l'élève --}}

<div
    id="frais-container"
    class="md:col-span-2 hidden"
>

```
<h3 class="text-lg font-semibold text-gray-800 mb-4">
    Frais scolaires à payer
</h3>

<div class="overflow-x-auto border border-gray-200 rounded-lg">

    <table class="min-w-full divide-y divide-gray-200">

        <thead class="bg-gray-50">

            <tr>

                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Payer
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Frais
                </th>

                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                    Montant
                </th>

                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                    Déjà payé
                </th>

                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                    Solde
                </th>

                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                    À payer
                </th>

            </tr>

        </thead>

        <tbody
            id="frais-list"
            class="bg-white divide-y divide-gray-200"
        >
        </tbody>

    </table>

</div>

<p
    id="frais-message"
    class="mt-3 text-sm text-gray-500"
></p>
```

</div>


                        {{-- Numéro reçu --}}

                        <div>

                            <label for="numero_recu"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Numéro du reçu *

                            </label>


                            <input
                                type="text"
                                id="numero_recu"
                                name="numero_recu"
                                value="{{ old('numero_recu') }}"
                                placeholder="Exemple : REC-0001"
                                required
                                class="w-full rounded-lg border-gray-300">

                        </div>


                        {{-- Date --}}

                        <div>

                            <label for="date_paiement"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Date du paiement *

                            </label>


                            <input
                                type="datetime-local"
                                id="date_paiement"
                                name="date_paiement"
                                value="{{ old('date_paiement', now()->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full rounded-lg border-gray-300">

                        </div>


                        {{-- Montant total --}}

                        <div>

                            <label for="montant_total"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Montant total *

                            </label>


                            <input
                                type="number"
                                id="montant_total"
                                name="montant_total"
                                value="{{ old('montant_total') }}"
                                min="0.01"
                                step="0.01"
                                required
                                class="w-full rounded-lg border-gray-300 bg-gray-50"
                                readonly>

                            <p class="text-xs text-gray-400 mt-1">
                                Calculé automatiquement à partir des frais sélectionnés.
                            </p>

                        </div>


                        {{-- Devise --}}

                        <div>

                            <label for="devise"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Devise *

                            </label>


                            <select
                                id="devise"
                                name="devise"
                                required
                                class="w-full rounded-lg border-gray-300">

                                <option value="USD"
                                    {{ old('devise', 'USD') == 'USD' ? 'selected' : '' }}>

                                    USD - Dollar américain

                                </option>

                                <option value="CDF"
                                    {{ old('devise') == 'CDF' ? 'selected' : '' }}>

                                    CDF - Franc congolais

                                </option>

                            </select>

                        </div>


                        {{-- Mode de paiement --}}

                        <div>

                            <label for="mode_paiement"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Mode de paiement *

                            </label>


                            <select
                                id="mode_paiement"
                                name="mode_paiement"
                                required
                                class="w-full rounded-lg border-gray-300">

                                <option value="ESPECES">
                                    Espèces
                                </option>

                                <option value="BANQUE">
                                    Banque
                                </option>

                                <option value="MOBILE_MONEY">
                                    Mobile Money
                                </option>

                                <option value="CHEQUE">
                                    Chèque
                                </option>

                                <option value="AUTRE">
                                    Autre
                                </option>

                            </select>

                        </div>


                        {{-- Référence --}}

                        <div>

                            <label for="reference"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Référence

                            </label>


                            <input
                                type="text"
                                id="reference"
                                name="reference"
                                value="{{ old('reference') }}"
                                placeholder="Exemple : TXN123456"
                                class="w-full rounded-lg border-gray-300">

                        </div>


                        {{-- Observation --}}

                        <div class="md:col-span-2">

                            <label for="observation"
                                   class="block text-sm font-medium text-gray-700 mb-1">

                                Observation

                            </label>


                            <textarea
                                id="observation"
                                name="observation"
                                rows="4"
                                class="w-full rounded-lg border-gray-300"
                                placeholder="Observation éventuelle...">{{ old('observation') }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- Boutons --}}

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">

                    <a
                        href="{{ route('paiements.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">

                        Annuler

                    </a>


                    <button
                        type="submit"
                        id="btn-enregistrer"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        
                        Enregistrer le paiement

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- JavaScript --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const selectEleve = document.getElementById('id_eleve');

        const listeFrais = document.getElementById('liste-frais');

        const messageFrais = document.getElementById('message-frais');

        const chargementFrais = document.getElementById('chargement-frais');

        const montantTotal = document.getElementById('montant_total');


        /*
        |--------------------------------------------------------------------------
        | Calcul du montant total
        |--------------------------------------------------------------------------
        */

        function calculerTotal() {

            let total = 0;


            document.querySelectorAll('.frais-checkbox:checked')
                .forEach(function (checkbox) {

                    const input = checkbox
                        .closest('.frais-item')
                        .querySelector('.montant-frais');

                    if (input) {

                        let montant = parseFloat(input.value) || 0;

                        total += montant;

                    }

                });


            montantTotal.value = total.toFixed(2);

        }


        /*
        |--------------------------------------------------------------------------
        | Charger les frais de l'élève
        |--------------------------------------------------------------------------
        */

        selectEleve.addEventListener('change', function () {

            const idEleve = this.value;


            listeFrais.innerHTML = '';

            listeFrais.classList.add('hidden');

            messageFrais.classList.remove('hidden');

            montantTotal.value = '';


            if (!idEleve) {

                messageFrais.innerHTML =
                    'Sélectionnez un élève pour afficher ses frais.';

                return;

            }


            messageFrais.classList.add('hidden');

            chargementFrais.classList.remove('hidden');


            fetch(
                "{{ url('/paiements/eleve') }}/" +
                idEleve +
                "/frais"
            )

            .then(function (response) {

                if (!response.ok) {

                    throw new Error(
                        'Impossible de récupérer les frais.'
                    );

                }

                return response.json();

            })

            .then(function (frais) {

                chargementFrais.classList.add('hidden');


                if (frais.length === 0) {

                    messageFrais.innerHTML =
                        'Aucun frais impayé pour cet élève.';

                    messageFrais.classList.remove('hidden');

                    return;

                }


                listeFrais.classList.remove('hidden');


                frais.forEach(function (fraisEleve) {


                    const categorie =
                        fraisEleve.tarif &&
                        fraisEleve.tarif.categorie_frais
                            ? fraisEleve.tarif.categorie_frais.libelle
                            : 'Frais scolaire';


                    const classe =
                        fraisEleve.inscription &&
                        fraisEleve.inscription.classe
                            ? fraisEleve.inscription.classe.libelle
                            : '';


                    const annee =
                        fraisEleve.inscription &&
                        fraisEleve.inscription.annee_scolaire
                            ? fraisEleve.inscription.annee_scolaire.libelle
                            : '';


                    const devise =
                        fraisEleve.tarif &&
                        fraisEleve.tarif.devise
                            ? fraisEleve.tarif.devise
                            : 'USD';


                    const solde =
                        parseFloat(fraisEleve.solde) || 0;


                    const item = document.createElement('div');

                    item.className =
                        'frais-item border border-gray-200 rounded-lg p-4 hover:bg-gray-50';


                    item.innerHTML = `

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div class="flex items-start gap-3">

                                <input
                                    type="checkbox"
                                    class="frais-checkbox mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    data-id="${fraisEleve.id_frais_eleve}"
                                >

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        ${categorie}
                                    </p>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Classe : ${classe}
                                        ${annee ? ' | Année : ' + annee : ''}
                                    </p>

                                    <p class="text-sm text-gray-600 mt-1">
                                        Solde :
                                        <strong>
                                            ${solde.toFixed(2)} ${devise}
                                        </strong>
                                    </p>

                                </div>

                            </div>


                            <div class="w-full md:w-40">

                                <label class="block text-xs text-gray-500 mb-1">
                                    Montant à payer
                                </label>

                                <input
                                    type="number"
                                    name="frais[${fraisEleve.id_frais_eleve}]"
                                    class="montant-frais w-full rounded-lg border-gray-300"
                                    value="${solde.toFixed(2)}"
                                    min="0.01"
                                    max="${solde.toFixed(2)}"
                                    step="0.01"
                                    disabled
                                >

                            </div>

                        </div>

                    `;


                    listeFrais.appendChild(item);


                    const checkbox =
                        item.querySelector('.frais-checkbox');

                    const montantInput =
                        item.querySelector('.montant-frais');


                    checkbox.addEventListener('change', function () {

                        montantInput.disabled = !this.checked;

                        if (!this.checked) {

                            montantInput.value =
                                solde.toFixed(2);

                        }

                        calculerTotal();

                    });


                    montantInput.addEventListener('input', function () {

                        let valeur =
                            parseFloat(this.value) || 0;


                        if (valeur > solde) {

                            this.value =
                                solde.toFixed(2);

                        }


                        if (valeur < 0) {

                            this.value = '0';

                        }


                        calculerTotal();

                    });

                });


            })

            .catch(function (error) {

                chargementFrais.classList.add('hidden');

                messageFrais.innerHTML =
                    'Erreur lors du chargement des frais : ' +
                    error.message;

                messageFrais.classList.remove('hidden');

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Sélection automatique si un ancien élève existe
        |--------------------------------------------------------------------------
        */

        if (selectEleve.value) {

            selectEleve.dispatchEvent(
                new Event('change')
            );

        }

    });

</script>

</x-app-layout>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const selectEleve = document.getElementById('id_eleve');

    const fraisContainer = document.getElementById('frais-container');

    const fraisList = document.getElementById('frais-list');

    const fraisMessage = document.getElementById('frais-message');

    const montantTotal = document.getElementById('montant_total');


    /*
    |--------------------------------------------------------------------------
    | Calculer le montant total
    |--------------------------------------------------------------------------
    */

    function calculerMontantTotal() {

        let total = 0;


        document
            .querySelectorAll('.frais-checkbox:checked')
            .forEach(function (checkbox) {

                const idFrais =
                    checkbox.dataset.id;

                const montantInput =
                    document.querySelector(
                        `.montant-frais[data-id="${idFrais}"]`
                    );


                if (montantInput && !montantInput.disabled) {

                    const montant =
                        parseFloat(montantInput.value) || 0;

                    total += montant;

                }

            });


        montantTotal.value =
            total.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | Charger les frais de l'élève
    |--------------------------------------------------------------------------
    */

    selectEleve.addEventListener('change', function () {

        const idEleve = this.value;


        fraisList.innerHTML = '';

        fraisMessage.textContent = '';

        fraisContainer.classList.add('hidden');

        montantTotal.value = '';


        if (!idEleve) {

            return;

        }


        fraisContainer.classList.remove('hidden');

        fraisMessage.textContent =
            'Chargement des frais...';


        fetch(
            "{{ url('/paiements/eleve') }}/" +
            idEleve +
            "/frais"
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Erreur HTTP : ' +
                    response.status
                );

            }

            return response.json();

        })

        .then(function (frais) {

            fraisList.innerHTML = '';

            fraisMessage.textContent = '';


            /*
            |--------------------------------------------------------------------------
            | Aucun frais
            |--------------------------------------------------------------------------
            */

            if (frais.length === 0) {

                fraisMessage.textContent =
                    'Aucun frais à payer pour cet élève.';

                montantTotal.value = '0.00';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Afficher les frais
            |--------------------------------------------------------------------------
            */

            frais.forEach(function (fraisEleve) {

                const categorie =
                    fraisEleve.tarif &&
                    fraisEleve.tarif.categorie_frais
                        ? fraisEleve.tarif.categorie_frais.libelle
                        : 'Frais scolaire';


                const devise =
                    fraisEleve.tarif &&
                    fraisEleve.tarif.devise
                        ? fraisEleve.tarif.devise
                        : 'USD';


                const montant =
                    parseFloat(
                        fraisEleve.montant_a_payer
                    ) || 0;


                const montantPaye =
                    parseFloat(
                        fraisEleve.montant_paye
                    ) || 0;


                const solde =
                    parseFloat(
                        fraisEleve.solde
                    ) || 0;


                const idFrais =
                    fraisEleve.id_frais_eleve;


                /*
                |--------------------------------------------------------------------------
                | Création de la ligne
                |--------------------------------------------------------------------------
                */

                const row =
                    document.createElement('tr');


                row.innerHTML = `

                    <td class="px-4 py-3">

                        <input
                            type="checkbox"
                            class="frais-checkbox rounded border-gray-300"
                            data-id="${idFrais}"
                            checked
                        >

                    </td>


                    <td class="px-4 py-3 text-sm text-gray-800">

                        ${categorie}

                    </td>


                    <td class="px-4 py-3 text-sm text-right">

                        ${montant.toFixed(2)}
                        ${devise}

                    </td>


                    <td class="px-4 py-3 text-sm text-right">

                        ${montantPaye.toFixed(2)}
                        ${devise}

                    </td>


                    <td class="px-4 py-3 text-sm font-semibold text-right">

                        ${solde.toFixed(2)}
                        ${devise}

                    </td>


                    <td class="px-4 py-3">

                        <input
                            type="number"
                            name="frais[${idFrais}]"
                            class="montant-frais w-32 rounded-lg border-gray-300 text-right"
                            data-id="${idFrais}"
                            value="${solde.toFixed(2)}"
                            min="0.01"
                            max="${solde}"
                            step="0.01"
                        >

                    </td>

                `;


                fraisList.appendChild(row);


                /*
                |--------------------------------------------------------------------------
                | Éléments de la ligne
                |--------------------------------------------------------------------------
                */

                const checkbox =
                    row.querySelector(
                        '.frais-checkbox'
                    );


                const montantInput =
                    row.querySelector(
                        '.montant-frais'
                    );


                /*
                |--------------------------------------------------------------------------
                | Cocher / décocher
                |--------------------------------------------------------------------------
                */

                checkbox.addEventListener(
                    'change',
                    function () {

                        montantInput.disabled =
                            !this.checked;


                        if (!this.checked) {

                            montantInput.value =
                                '0.00';

                        } else {

                            if (
                                parseFloat(
                                    montantInput.value
                                ) <= 0
                            ) {

                                montantInput.value =
                                    solde.toFixed(2);

                            }

                        }


                        calculerMontantTotal();

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Modification du montant
                |--------------------------------------------------------------------------
                */

                montantInput.addEventListener(
                    'input',
                    function () {

                        let valeur =
                            parseFloat(
                                this.value
                            ) || 0;


                        /*
                        | Ne jamais dépasser le solde
                        */

                        if (valeur > solde) {

                            valeur = solde;

                            this.value =
                                solde.toFixed(2);

                        }


                        if (valeur < 0) {

                            valeur = 0;

                            this.value =
                                '0.00';

                        }


                        calculerMontantTotal();

                    }
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Calcul initial
            |--------------------------------------------------------------------------
            */

            calculerMontantTotal();

        })

        .catch(function (error) {

            console.error(error);


            fraisList.innerHTML = '';


            fraisMessage.textContent =
                'Impossible de charger les frais de cet élève.';


            montantTotal.value =
                '0.00';

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Initialisation si un élève est déjà sélectionné
    |--------------------------------------------------------------------------
    */

    if (selectEleve.value) {

        selectEleve.dispatchEvent(
            new Event('change')
        );

    }

});

</script>

<x-app-layout>

<x-slot name="header">

    <div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Modifier les permissions
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Gestion des permissions du rôle :
            <span class="font-semibold text-gray-700">
                {{ $role->nom }}
            </span>
        </p>
    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


        {{-- Messages --}}

        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200
                        text-green-700 px-4 py-3 rounded-lg">

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="mb-6 bg-red-50 border border-red-200
                        text-red-700 px-4 py-3 rounded-lg">

                {{ session('error') }}

            </div>

        @endif


        {{-- Formulaire --}}

        <form method="POST"
              action="{{ route('roles.update', $role) }}">

            @csrf

            @method('PUT')


            {{-- Informations du rôle --}}

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">

                <h3 class="text-lg font-bold text-gray-800">
                    Informations du rôle
                </h3>

                <div class="mt-5">

                    <label class="block text-sm font-medium text-gray-700">
                        Nom du rôle
                    </label>

                    <input type="text"
                           name="nom"
                           value="{{ old('nom', $role->nom) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300
                                  focus:border-indigo-500 focus:ring-indigo-500">

                </div>


                <div class="mt-5">

                    <label class="block text-sm font-medium text-gray-700">
                        Description
                    </label>

                    <textarea name="description"
                              rows="3"
                              class="mt-1 block w-full rounded-lg border-gray-300
                                     focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $role->description) }}</textarea>

                </div>

            </div>


            {{-- Grille des permissions --}}

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <div class="p-6 border-b border-gray-200">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-bold text-gray-800">
                                Permissions
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Sélectionnez les actions autorisées pour ce rôle.
                            </p>

                        </div>

                        <div>

                            <label class="inline-flex items-center">

                                <input type="checkbox"
                                       id="selectAll"
                                       class="rounded border-gray-300
                                              text-indigo-600
                                              focus:ring-indigo-500">

                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Tout sélectionner
                                </span>

                            </label>

                        </div>

                    </div>

                </div>


                @php

                    $permissionsRole = $role->permissions
                        ->pluck('id_permission')
                        ->toArray();

                @endphp


                <div class="p-6 space-y-8">


                    @foreach($permissions as $module => $permissionsModule)

                        <div class="border border-gray-200 rounded-xl overflow-hidden">


                            {{-- En-tête du module --}}

                            <div class="bg-gray-50 px-5 py-4
                                        border-b border-gray-200">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <h4 class="font-bold text-gray-800 text-lg">
                                            {{ ucfirst(str_replace('_', ' ', $module)) }}
                                        </h4>

                                        <p class="text-xs text-gray-500 mt-1">
                                            Permissions du module
                                        </p>

                                    </div>


                                    {{-- Sélection du module --}}

                                    <label class="inline-flex items-center">

                                        <input type="checkbox"
                                               class="module-select rounded
                                                      border-gray-300
                                                      text-indigo-600
                                                      focus:ring-indigo-500"
                                               data-module="{{ $loop->index }}">

                                        <span class="ml-2 text-xs font-medium text-gray-600">
                                            Tout le module
                                        </span>

                                    </label>

                                </div>

                            </div>


                            {{-- Actions --}}

                            <div class="overflow-x-auto">

                                <table class="min-w-full">

                                    <thead class="bg-white">

                                        <tr>

                                            <th class="px-5 py-3 text-left text-xs
                                                       font-semibold text-gray-500
                                                       uppercase">
                                                Fonctionnalité
                                            </th>

                                            <th class="px-5 py-3 text-left text-xs
                                                       font-semibold text-gray-500
                                                       uppercase">
                                                Description
                                            </th>

                                            <th class="px-5 py-3 text-center text-xs
                                                       font-semibold text-gray-500
                                                       uppercase">
                                                Autoriser
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody class="divide-y divide-gray-100">

                                        @foreach($permissionsModule as $permission)

                                            <tr class="hover:bg-gray-50">


                                                <td class="px-5 py-4">

                                                    <span class="font-medium text-gray-800">

                                                        {{ ucfirst(str_replace('_', ' ', $permission->action)) }}

                                                    </span>

                                                </td>


                                                <td class="px-5 py-4 text-sm text-gray-500">

                                                    {{ $permission->description }}

                                                </td>


                                                <td class="px-5 py-4 text-center">

                                                    <input type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->id_permission }}"
                                                           class="permission-checkbox rounded
                                                                  border-gray-300
                                                                  text-indigo-600
                                                                  focus:ring-indigo-500"
                                                           data-module="{{ $loop->parent->index }}"
                                                           @checked(in_array(
                                                               $permission->id_permission,
                                                               $permissionsRole
                                                           ))>

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    @endforeach


                </div>


                {{-- Boutons --}}

                <div class="px-6 py-5 bg-gray-50 border-t border-gray-200
                            flex items-center justify-between">


                    <a href="{{ route('roles.index') }}"
                       class="px-5 py-2.5 bg-gray-200 text-gray-700
                              rounded-lg font-semibold text-sm
                              hover:bg-gray-300 transition">

                        Annuler

                    </a>


                    <button type="submit"
                            class="px-5 py-2.5 bg-gray-800 text-white
                                   rounded-lg font-semibold text-sm
                                   hover:bg-gray-700 transition">

                        Enregistrer les permissions

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- Sélection automatique --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const selectAll = document.getElementById('selectAll');

        const permissionCheckboxes =
            document.querySelectorAll('.permission-checkbox');

        const moduleCheckboxes =
            document.querySelectorAll('.module-select');


        /*
        |--------------------------------------------------------------------------
        | Tout sélectionner
        |--------------------------------------------------------------------------
        */

        selectAll.addEventListener('change', function () {

            permissionCheckboxes.forEach(function (checkbox) {

                checkbox.checked = selectAll.checked;

            });

            moduleCheckboxes.forEach(function (checkbox) {

                checkbox.checked = selectAll.checked;

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Sélection d'un module
        |--------------------------------------------------------------------------
        */

        moduleCheckboxes.forEach(function (moduleCheckbox) {

            moduleCheckbox.addEventListener('change', function () {

                const module =
                    this.dataset.module;

                document
                    .querySelectorAll(
                        '.permission-checkbox[data-module="' + module + '"]'
                    )
                    .forEach(function (checkbox) {

                        checkbox.checked =
                            moduleCheckbox.checked;

                    });

                updateSelectAll();

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Mise à jour de "Tout sélectionner"
        |--------------------------------------------------------------------------
        */

        permissionCheckboxes.forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                updateSelectAll();

                updateModuleCheckbox(this.dataset.module);

            });

        });


        function updateSelectAll() {

            const total =
                permissionCheckboxes.length;

            const checked =
                document.querySelectorAll(
                    '.permission-checkbox:checked'
                ).length;

            selectAll.checked =
                total > 0 && total === checked;

        }


        function updateModuleCheckbox(module) {

            const modulePermissions =
                document.querySelectorAll(
                    '.permission-checkbox[data-module="' +
                    module +
                    '"]'
                );

            const moduleChecked =
                document.querySelectorAll(
                    '.permission-checkbox[data-module="' +
                    module +
                    '"]:checked'
                ).length;

            const moduleCheckbox =
                document.querySelector(
                    '.module-select[data-module="' +
                    module +
                    '"]'
                );

            if (moduleCheckbox) {

                moduleCheckbox.checked =
                    modulePermissions.length > 0 &&
                    modulePermissions.length === moduleChecked;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | État initial
        |--------------------------------------------------------------------------
        */

        moduleCheckboxes.forEach(function (checkbox) {

            updateModuleCheckbox(
                checkbox.dataset.module
            );

        });

        updateSelectAll();

    });

</script>

</x-app-layout>

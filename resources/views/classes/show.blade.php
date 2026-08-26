<x-app-layout>

```
<x-slot name="header">

    <div class="flex justify-between items-center">

        <div>
            <h2 class="font-semibold text-2xl text-gray-800">
                Détails de la classe
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Informations détaillées
            </p>
        </div>

        <div class="flex gap-2">

            <a href="{{ route('classes.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Retour
            </a>

            <a href="{{ route('classes.edit', $classe) }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Modifier
            </a>

        </div>

    </div>

</x-slot>


<div class="py-8 bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        {{-- Informations générales --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">

            <div class="p-6">

                <div class="flex justify-between items-start mb-6">

                    <div>

                        <h3 class="text-2xl font-bold text-gray-800">
                            {{ $classe->libelle }}
                        </h3>

                        <p class="text-gray-500 mt-1">
                            {{ $classe->niveau->libelle ?? '-' }}
                        </p>

                    </div>


                    @if($classe->statut === 'ACTIVE')

                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700">
                            ACTIVE
                        </span>

                    @else

                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700">
                            INACTIVE
                        </span>

                    @endif

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- Année scolaire --}}
                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Année scolaire
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $classe->anneeScolaire->libelle ?? '-' }}
                        </p>

                    </div>


                    {{-- Niveau --}}
                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Niveau
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $classe->niveau->libelle ?? '-' }}
                        </p>

                    </div>


                    {{-- Option --}}
                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Option
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $classe->option_classe ?? '-' }}
                        </p>

                    </div>


                    {{-- Capacité --}}
                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            Capacité
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $classe->capacite }} élèves
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="bg-white shadow-sm rounded-lg mt-6">

            <div class="p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Actions
                </h3>

                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('classes.edit', $classe) }}"
                       class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Modifier la classe
                    </a>

                    <a href="{{ route('classes.index') }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Retour à la liste
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>
```

</x-app-layout>

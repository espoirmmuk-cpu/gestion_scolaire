<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-8">

        <div class="w-full max-w-md">

            <!-- En-tête -->
            <div class="text-center mb-4">

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

                <h1 class="text-3xl font-bold text-gray-800">
                    GESCO
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Système de gestion scolaire
                </p>

            </div>


            <!-- Carte de connexion -->
            <div class="rounded-2xl bg-white p-8 shadow-xl">

                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Connexion
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        
                    </p>
                </div>


                <!-- Message de session -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')"
                />


                <!-- Formulaire -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf


                    <!-- Email -->
                    <div>
                        <x-input-label
                            for="email"
                            :value="__('Adresse e-mail')"
                            class="text-gray-700"
                        />

                        <x-text-input
                            id="email"
                            class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@gestion-scolaire.local"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />
                    </div>


                    <!-- Mot de passe -->
                    <div class="mt-5">

                        <x-input-label
                            for="password"
                            :value="__('Mot de passe')"
                            class="text-gray-700"
                        />

                        <x-text-input
                            id="password"
                            class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2"
                        />

                    </div>


                    <!-- Se souvenir -->
                    <div class="mt-5">

                        <label
                            for="remember_me"
                            class="inline-flex items-center cursor-pointer"
                        >

                            <input
                                id="remember_me"
                                type="checkbox"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                name="remember"
                            >

                            <span class="ms-2 text-sm text-gray-600">
                                {{ __('Se souvenir de moi') }}
                            </span>

                        </label>

                    </div>


                    <!-- Actions -->
                    <div class="mt-6">

                        <x-primary-button
                            class="w-full justify-center py-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800"
                        >
                            {{ __('Se connecter') }}
                        </x-primary-button>

                    </div>


                    <!-- Mot de passe oublié -->
                    @if (Route::has('password.request'))

                        <div class="mt-5 text-center">

                            <a
                                href="{{ route('password.request') }}"
                                class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline"
                            >
                                {{ __('Mot de passe oublié ?') }}
                            </a>

                        </div>

                    @endif

                </form>

            </div>


            <!-- Pied de page -->
            <div class="mt-6 text-center">

                <p class="text-xs text-gray-500">
                    © {{ date('Y') }} Gestion Scolaire
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Accès sécurisé
                </p>

            </div>

        </div>

    </div>

</x-guest-layout>
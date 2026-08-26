<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nous contacter - Gestion Scolaire</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="bg-gray-100 min-h-screen">


    <!-- =========================================================
         EN-TÊTE
    ========================================================== -->

    <header class="bg-white shadow-sm">

        <div class="max-w-7xl mx-auto px-6 py-4">

            <div class="flex items-center justify-between">



                <!-- RETOUR -->

                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-4 py-2
                          bg-gray-100 text-gray-700 rounded-lg
                          hover:bg-gray-200
                          transition duration-200">

                    <span class="mr-2">
                        ←
                    </span>

                    Retour

                </a>

            </div>

        </div>

    </header>



    <!-- =========================================================
         CONTENU PRINCIPAL
    ========================================================== -->

    <main class="py-12">

        <div class="max-w-6xl mx-auto px-6">


            <!-- =====================================================
                 TITRE
            ====================================================== -->

            <div class="text-center mb-10">

                <h2 class="text-3xl font-bold text-gray-800">
                    Nous contacter
                </h2>

                <p class="text-gray-500 mt-2">

                    Une question, une assistance ou besoin d'informations ?
                    Nous sommes à votre disposition.

                </p>

            </div>



           <!-- =====================================================
                NOS COORDONNÉES
            ====================================================== -->

            <div class="bg-white rounded-xl shadow-sm px-6 py-4 mb-8">

                <div class="flex flex-col md:flex-row md:items-center gap-5">

                    <!-- TITRE -->

                    <div class="flex-shrink-0 md:pr-5 md:border-r border-gray-200">

                        <h3 class="text-lg font-bold text-gray-800">
                            Nos coordonnées
                        </h3>

                    </div>


                    <!-- TÉLÉPHONE -->

                    <a href="tel:+243820607096"
                    class="flex items-center text-sm text-gray-700
                            hover:text-blue-600 transition duration-200">

                        <span class="text-lg mr-2">
                            📞
                        </span>

                        <span>
                            <strong>Téléphone</strong>
                            <span class="ml-1">+243 820 607 096</span>
                        </span>

                    </a>


                    <!-- EMAIL -->

                    <a href="mailto:espoirmmuk@gmail.com"
                    class="flex items-center text-sm text-gray-700
                            hover:text-green-600 transition duration-200">

                        <span class="text-lg mr-2">
                            ✉️
                        </span>

                        <span>
                            <strong>E-mail</strong>
                            <span class="ml-1">espoirmmuk@gmail.com</span>
                        </span>

                    </a>


                    <!-- ADRESSE -->

                    <div class="flex items-center text-sm text-gray-700">

                        <span class="text-lg mr-2">
                            📍
                        </span>

                        <span>
                            <strong>Adresse</strong>
                            <span class="ml-1">Kinshasa, RDC</span>
                        </span>

                    </div>

                </div>

            </div>



            <!-- =====================================================
                 BLOC MESSAGE
            ====================================================== -->

            <div class="bg-white rounded-xl shadow-sm p-8">


                <!-- =================================================
                     TITRE
                ================================================== -->

                <div class="mb-6">

                    <h3 class="text-xl font-bold text-gray-800">
                        Envoyer un message
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Remplissez le formulaire ci-dessous et envoyez-nous
                        votre demande.
                    </p>

                </div>



                <!-- =================================================
                     MESSAGE SUCCÈS
                ================================================== -->

                @if(session('success'))

                    <div class="mb-6 bg-green-100 border
                                border-green-300 text-green-700
                                px-4 py-3 rounded-lg">

                        {{ session('success') }}

                    </div>

                @endif



                <!-- =================================================
                     MESSAGE ERREUR
                ================================================== -->

                @if(session('error'))

                    <div class="mb-6 bg-red-100 border
                                border-red-300 text-red-700
                                px-4 py-3 rounded-lg">

                        {{ session('error') }}

                    </div>

                @endif



                <!-- =================================================
                     ERREURS VALIDATION
                ================================================== -->

                @if($errors->any())

                    <div class="mb-6 bg-red-100 border
                                border-red-300 text-red-700
                                px-4 py-3 rounded-lg">

                        <ul class="list-disc ml-5">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif



                <!-- =================================================
                     FORMULAIRE
                ================================================== -->

                <form method="POST"
                      action="{{ route('contact.send') }}">

                    @csrf


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <!-- =================================================
                             NOM
                        ================================================== -->

                        <div>

                            <label for="nom"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-2">

                                Nom complet

                            </label>

                            <input type="text"
                                   id="nom"
                                   name="nom"
                                   value="{{ old('nom') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300
                                          focus:border-blue-500
                                          focus:ring-blue-500"
                                   placeholder="Votre nom complet">

                        </div>



                        <!-- =================================================
                             EMAIL
                        ================================================== -->

                        <div>

                            <label for="email"
                                   class="block text-sm font-medium
                                          text-gray-700 mb-2">

                                Adresse e-mail

                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full rounded-lg border-gray-300
                                          focus:border-blue-500
                                          focus:ring-blue-500"
                                   placeholder="exemple@email.com">

                        </div>


                    </div>



                    <!-- =================================================
                         SUJET
                    ================================================== -->

                    <div class="mt-6">

                        <label for="sujet"
                               class="block text-sm font-medium
                                      text-gray-700 mb-2">

                            Sujet

                        </label>

                        <input type="text"
                               id="sujet"
                               name="sujet"
                               value="{{ old('sujet') }}"
                               required
                               class="w-full rounded-lg border-gray-300
                                      focus:border-blue-500
                                      focus:ring-blue-500"
                               placeholder="Objet de votre message">

                    </div>



                    <!-- =================================================
                         MESSAGE
                    ================================================== -->

                    <div class="mt-6">

                        <label for="message"
                               class="block text-sm font-medium
                                      text-gray-700 mb-2">

                            Message

                        </label>

                        <textarea id="message"
                                  name="message"
                                  rows="7"
                                  required
                                  class="w-full rounded-lg
                                         border-gray-300
                                         focus:border-blue-500
                                         focus:ring-blue-500"
                                  placeholder="Écrivez votre message ici...">{{ old('message') }}</textarea>

                    </div>



                    <!-- =================================================
                         BOUTON
                    ================================================== -->

                    <div class="flex justify-end mt-6">

                        <button type="submit"
                                class="inline-flex items-center
                                       px-6 py-3
                                       bg-blue-600
                                       text-white
                                       font-semibold
                                       rounded-lg
                                       shadow-sm
                                       hover:bg-blue-700
                                       hover:scale-105
                                       transition duration-200">

                            <span class="mr-2">
                                📩
                            </span>

                            Envoyer le message

                        </button>

                    </div>


                </form>

            </div>


        </div>

    </main>



    <!-- =========================================================
         PIED DE PAGE
    ========================================================== -->

    <footer class="bg-white border-t mt-10">

        <div class="max-w-7xl mx-auto px-6 py-6">

            <div class="text-center text-sm text-gray-500">

                © {{ date('Y') }} Gestion Scolaire.
                Tous droits réservés.

            </div>

        </div>

    </footer>


</body>

</html>
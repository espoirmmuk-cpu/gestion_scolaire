@extends('layouts.app')

@section('content')

    <div class="p-6 max-w-5xl mx-auto">

        {{-- RETOUR --}}
        <div class="mb-6">

            <a
                href="{{ route('contacts.index') }}"
                class="inline-flex items-center px-4 py-2
                       bg-gray-100 text-gray-700 rounded-lg
                       hover:bg-gray-200"
            >
                ← Retour aux messages
            </a>

        </div>


        {{-- MESSAGE --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">


            {{-- EN-TÊTE --}}
            <div class="px-6 py-5 border-b bg-gray-50">

                <div class="flex flex-col md:flex-row
                            md:items-center md:justify-between gap-4">

                    <div>

                        <h1 class="text-2xl font-bold text-gray-800">
                            {{ $contact->sujet }}
                        </h1>

                        <p class="text-sm text-gray-500 mt-1">
                            Reçu le
                            {{ $contact->created_at?->format('d/m/Y à H:i') }}
                        </p>

                    </div>


                    @if($contact->lu)

                        <span class="inline-flex px-3 py-1
                                     bg-green-100 text-green-700
                                     rounded-full text-sm font-semibold">
                            🟢 Lu
                        </span>

                    @else

                        <span class="inline-flex px-3 py-1
                                     bg-blue-100 text-blue-700
                                     rounded-full text-sm font-semibold">
                            🔵 Non lu
                        </span>

                    @endif

                </div>

            </div>


            {{-- EXPÉDITEUR --}}
            <div class="px-6 py-5 border-b">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>

                        <p class="text-xs font-semibold text-gray-500 uppercase">
                            Expéditeur
                        </p>

                        <p class="text-gray-800 font-semibold mt-1">
                            {{ $contact->nom }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs font-semibold text-gray-500 uppercase">
                            Adresse e-mail
                        </p>

                        <p class="text-gray-800 mt-1">
                            {{ $contact->email }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- MESSAGE --}}
            <div class="px-6 py-8">

                <p class="text-xs font-semibold text-gray-500 uppercase mb-3">
                    Message
                </p>

                <div class="bg-gray-50 rounded-lg p-6
                            text-gray-700 whitespace-pre-line
                            leading-relaxed">

                    {{ $contact->message }}

                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="px-6 py-5 border-t flex flex-wrap gap-3">

                {{-- RÉPONDRE --}}
                <a
                    href="mailto:{{ $contact->email }}?subject={{ rawurlencode('Re: ' . $contact->sujet) }}"
                    class="inline-flex items-center px-5 py-2.5
                           bg-blue-600 text-white rounded-lg
                           hover:bg-blue-700"
                >
                    ✉️ Répondre par email
                </a>


                {{-- SUPPRIMER --}}
                <form
                    method="POST"
                    action="{{ route('contacts.destroy', $contact) }}"
                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce message ?');"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-2.5
                               bg-red-600 text-white rounded-lg
                               hover:bg-red-700"
                    >
                        🗑️ Supprimer
                    </button>

                </form>


                {{-- RETOUR --}}
                <a
                    href="{{ route('contacts.index') }}"
                    class="inline-flex items-center px-5 py-2.5
                           bg-gray-100 text-gray-700 rounded-lg
                           hover:bg-gray-200"
                >
                    Fermer
                </a>

            </div>

        </div>

    </div>

@endsection

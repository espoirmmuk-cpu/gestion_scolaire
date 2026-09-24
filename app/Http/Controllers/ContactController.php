<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Afficher la page de contact.
     */
    public function index()
    {
        return view('contact');
    }


    /**
     * Traiter l'envoi du formulaire de contact.
     */
    public function send(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
            ],

            'sujet' => [
                'required',
                'string',
                'max:200',
            ],

            'message' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ENREGISTREMENT DU MESSAGE DANS GESCO
        |--------------------------------------------------------------------------
        */

        $contact = Contact::create([
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'sujet' => $validated['sujet'],
            'message' => $validated['message'],
            'lu' => false,
            'date_lu' => null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | DESTINATAIRE EMAIL
        |--------------------------------------------------------------------------
        */

        $destinataire = env(
            'CONTACT_EMAIL',
            config('mail.from.address')
        );


        /*
        |--------------------------------------------------------------------------
        | ENVOI DE LA COPIE PAR EMAIL
        |--------------------------------------------------------------------------
        */

        try {

            Mail::raw(
                "Nouveau message reçu depuis le formulaire de contact GESCO.\n\n"
                . "Nom : " . $validated['nom'] . "\n"
                . "Email : " . $validated['email'] . "\n"
                . "Sujet : " . $validated['sujet'] . "\n\n"
                . "Message :\n"
                . $validated['message'],
                function ($mail) use (
                    $validated,
                    $destinataire
                ) {

                    $mail->to($destinataire)

                        ->replyTo(
                            $validated['email'],
                            $validated['nom']
                        )

                        ->subject(
                            '[GESCO] '
                            . $validated['sujet']
                        );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | MESSAGE DE SUCCÈS
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('contact')
                ->with(
                    'success',
                    'Votre message a bien été envoyé. Merci de nous avoir contactés.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | LE MESSAGE EST DÉJÀ ENREGISTRÉ
            |--------------------------------------------------------------------------
            |
            | Même si l'email n'a pas pu être envoyé,
            | le message reste disponible dans GESCO.
            |
            */

            return redirect()
                ->route('contact')
                ->with(
                    'success',
                    'Votre message a bien été enregistré. Nous vous répondrons dans les meilleurs délais.'
                );
        }
    }
}
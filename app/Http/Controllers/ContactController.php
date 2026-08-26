<?php

namespace App\Http\Controllers;

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
        | DESTINATAIRE
        |--------------------------------------------------------------------------
        |
        | Pour l'instant, l'adresse est récupérée depuis .env.
        |
        */

        $destinataire = env(
            'CONTACT_EMAIL',
            config('mail.from.address')
        );


        /*
        |--------------------------------------------------------------------------
        | ENVOI DU MESSAGE
        |--------------------------------------------------------------------------
        */

        try {

            Mail::raw(
                $validated['message'],
                function ($mail) use ($validated, $destinataire) {

                    $mail->to($destinataire)

                         ->replyTo(
                             $validated['email'],
                             $validated['nom']
                         )

                         ->subject(
                             '[Gestion Scolaire] ' .
                             $validated['sujet']
                         );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | RETOUR À LA PAGE DE CONTACT
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
            | EN CAS D'ERREUR D'ENVOI
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('contact')
                ->withInput()
                ->with(
                    'error',
                    'Impossible d’envoyer votre message pour le moment. Veuillez réessayer plus tard.'
                );
        }
    }
}
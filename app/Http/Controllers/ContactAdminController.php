<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactAdminController extends Controller
{
    /**
     * Liste des messages.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('sujet', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Filtre état
        |--------------------------------------------------------------------------
        */

        if ($request->filled('etat')) {

            if ($request->etat === 'non_lu') {

                $query->where('lu', false);

            } elseif ($request->etat === 'lu') {

                $query->where('lu', true);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $contacts = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Nombre de messages non lus
        |--------------------------------------------------------------------------
        */

        $nombreNonLus = Contact::where('lu', false)->count();


        return view(
            'contacts.index',
            compact(
                'contacts',
                'nombreNonLus'
            )
        );
    }


    /**
     * Afficher un message.
     */
    public function show(Contact $contact)
    {
        /*
        |--------------------------------------------------------------------------
        | Marquer automatiquement comme lu
        |--------------------------------------------------------------------------
        */

        if (!$contact->lu) {

            $contact->update([
                'lu' => true,
                'date_lu' => now(),
            ]);
        }


        return view(
            'contacts.show',
            compact('contact')
        );
    }


    /**
     * Supprimer un message.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('contacts.index')
            ->with(
                'success',
                'Message supprimé avec succès.'
            );
    }
}
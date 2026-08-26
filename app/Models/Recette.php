<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Paiement;

class Recette extends Model
{
    protected $table = 'recettes';

    protected $primaryKey = 'id_recette';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_paiement',
        'id_etablissement',
        'id_annee_scolaire',
        'date_recette',
        'source',
        'montant',
        'devise',
        'description',
        'id_utilisateur',
    ];

    protected $casts = [
        'date_recette' => 'date',
        'montant' => 'decimal:2',
    ];

    /**
     * Établissement
     */
    public function etablissement()
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }

    /**
     * Année scolaire
     */
    public function anneeScolaire()
    {
        return $this->belongsTo(
            AnneeScolaire::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(
            User::class,
            'id_utilisateur',
            'id_utilisateur'
        );
    }

    /**
 * Paiement à l'origine de la recette
 */
public function paiement()
{
    return $this->belongsTo(
        Paiement::class,
        'id_paiement',
        'id_paiement'
    );
}
}
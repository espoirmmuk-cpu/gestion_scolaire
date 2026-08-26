<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    protected $table = 'depenses';

    protected $primaryKey = 'id_depense';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'id_annee_scolaire',
        'date_depense',
        'categorie',
        'montant',
        'devise',
        'description',
        'id_utilisateur',
    ];

    protected $casts = [
        'date_depense' => 'datetime',
        'montant' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Établissement
    |--------------------------------------------------------------------------
    */

    public function etablissement()
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Année scolaire
    |--------------------------------------------------------------------------
    */

    public function anneeScolaire()
    {
        return $this->belongsTo(
            AnneeScolaire::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Utilisateur
    |--------------------------------------------------------------------------
    */

    public function utilisateur()
    {
        return $this->belongsTo(
            User::class,
            'id_utilisateur',
            'id_utilisateur'
        );
    }
}

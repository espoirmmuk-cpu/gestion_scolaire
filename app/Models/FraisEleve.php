<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraisEleve extends Model
{
    protected $table = 'frais_eleves';

    protected $primaryKey = 'id_frais_eleve';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_eleve',
        'id_inscription',
        'id_tarif',
        'montant',
        'remise',
        'montant_a_payer',
        'montant_paye',
        'solde',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'remise' => 'decimal:2',
        'montant_a_payer' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'solde' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function eleve()
    {
        return $this->belongsTo(
            Eleve::class,
            'id_eleve',
            'id_eleve'
        );
    }


    public function inscription()
    {
        return $this->belongsTo(
            Inscription::class,
            'id_inscription',
            'id_inscription'
        );
    }


    public function tarif()
    {
        return $this->belongsTo(
            TarifScolaire::class,
            'id_tarif',
            'id_tarif'
        );
    }


    public function detailsPaiements()
    {
        return $this->hasMany(
            DetailPaiement::class,
            'id_frais_eleve',
            'id_frais_eleve'
        );
    }
    public function classe()
{
    return $this->belongsTo(
        Classe::class,
        'id_classe',
        'id_classe'
    );
}

public function anneeScolaire()
{
    return $this->belongsTo(
        AnneeScolaire::class,
        'id_annee_scolaire',
        'id_annee_scolaire'
    );
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifScolaire extends Model
{
    protected $table = 'tarifs_scolaires';

    protected $primaryKey = 'id_tarif';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_annee_scolaire',
        'id_classe',
        'id_categorie_frais',
        'montant',
        'devise',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
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


    public function classe()
    {
        return $this->belongsTo(
            Classe::class,
            'id_classe',
            'id_classe'
        );
    }


    public function categorieFrais()
    {
        return $this->belongsTo(
            CategorieFrais::class,
            'id_categorie_frais',
            'id_categorie_frais'
        );
    }


    public function fraisEleves()
    {
        return $this->hasMany(
            FraisEleve::class,
            'id_tarif',
            'id_tarif'
        );
    }
}

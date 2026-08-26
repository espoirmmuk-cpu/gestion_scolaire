<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Etablissement;

class CategorieFrais extends Model
{
    protected $table = 'categories_frais';

    protected $primaryKey = 'id_categorie_frais';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'libelle',
        'description',
        'statut',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function tarifs()
    {
        return $this->hasMany(
            TarifScolaire::class,
            'id_categorie_frais',
            'id_categorie_frais'
        );
    }

    public function etablissement()
{
    return $this->belongsTo(
        Etablissement::class,
        'id_etablissement',
        'id_etablissement'
    );
}
}

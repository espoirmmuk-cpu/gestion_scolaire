<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infrastructure extends Model
{
    protected $table = 'infrastructures';

    protected $primaryKey = 'id_infrastructure';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'designation',
        'type',
        'quantite',
        'etat',
        'localisation',
        'observation',
    ];

    protected $casts = [
        'quantite' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | ÉTABLISSEMENT
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
}
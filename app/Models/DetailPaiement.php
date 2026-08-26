<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPaiement extends Model
{
    protected $table = 'details_paiements';

    protected $primaryKey = 'id_detail_paiement';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_paiement',
        'id_frais_eleve',
        'montant',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function paiement()
    {
        return $this->belongsTo(
            Paiement::class,
            'id_paiement',
            'id_paiement'
        );
    }


    public function fraisEleve()
    {
        return $this->belongsTo(
            FraisEleve::class,
            'id_frais_eleve',
            'id_frais_eleve'
        );
    }
}

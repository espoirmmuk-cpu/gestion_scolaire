<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Eleve;
use App\Models\User;
use App\Models\DetailPaiement;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $primaryKey = 'id_paiement';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_eleve',
        'numero_recu',
        'date_paiement',
        'montant_total',
        'devise',
        'mode_paiement',
        'reference',
        'id_utilisateur',
        'observation',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant_total' => 'decimal:2',
    ];

    /**
     * Élève concerné par le paiement.
     */
    public function eleve()
    {
        return $this->belongsTo(
            Eleve::class,
            'id_eleve',
            'id_eleve'
        );
    }

    /**
     * Utilisateur ayant enregistré le paiement.
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
     * Détails du paiement.
     */
    public function details()
    {
        return $this->hasMany(
            DetailPaiement::class,
            'id_paiement',
            'id_paiement'
        );
    }
}
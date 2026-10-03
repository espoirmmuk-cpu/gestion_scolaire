<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    use Syncable;

    protected $table = 'bulletins';

    protected $primaryKey = 'id_bulletin';

    public $timestamps = false;

    protected $fillable = [
        'id_eleve',
        'id_annee_scolaire',
        'id_periode',
        'id_classe',
        'moyenne',
        'pourcentage',
        'rang',
        'decision',
        'observation',
        'date_generation',
    ];

    protected $casts = [
        'moyenne' => 'decimal:2',
        'pourcentage' => 'decimal:2',
        'rang' => 'integer',
        'date_generation' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $table = 'presences';

    protected $primaryKey = 'id_presence';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_eleve',
        'id_classe',
        'date_presence',
        'statut',
        'motif',
        'observation',
    ];

    protected $casts = [
        'date_presence' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Élève
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

    /*
    |--------------------------------------------------------------------------
    | Classe
    |--------------------------------------------------------------------------
    */

    public function classe()
    {
        return $this->belongsTo(
            Classe::class,
            'id_classe',
            'id_classe'
        );
    }
}
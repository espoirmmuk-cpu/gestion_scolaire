<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    use Syncable;

    protected $table = 'inventaire';

    protected $primaryKey = 'id_inventaire';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'id_categorie',
        'designation',
        'quantite',
        'date_acquisition',
        'etat',
        'localisation',
        'responsable',
        'observation',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'date_acquisition' => 'date',
    ];
}
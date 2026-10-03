<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    use Syncable;

    protected $table = 'activites';

    protected $primaryKey = 'id_activite';

    public $timestamps = false;

    protected $fillable = [
        'id_annee_scolaire',
        'titre',
        'type',
        'date_activite',
        'lieu',
        'description',
        'budget',
        'devise',
        'responsable',
        'statut',
    ];

    protected $casts = [
        'date_activite' => 'date',
        'budget' => 'decimal:2',
    ];
}

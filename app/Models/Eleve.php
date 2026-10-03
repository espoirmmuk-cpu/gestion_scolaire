<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Syncable;

class Eleve extends Model
{
    use Syncable;
    
    protected $table = 'eleves';

    protected $primaryKey = 'id_eleve';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'matricule',
        'nom',
        'postnom',
        'prenom',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'adresse',
        'telephone',
        'email',
        'photo',
        'statut',
        'date_creation',
        'date_modification',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_creation' => 'datetime',
        'date_modification' => 'datetime',
    ];
}



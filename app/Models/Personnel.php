<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    protected $table = 'personnel';

    protected $primaryKey = 'id_personnel';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'matricule',
        'nom',
        'postnom',
        'prenom',
        'sexe',
        'fonction',
        'qualification',
        'telephone',
        'email',
        'adresse',
        'date_engagement',
        'statut',
        'photo',
    ];

    protected $casts = [
        'date_engagement' => 'date',
        'date_creation' => 'datetime',
    ];

    public function etablissement()
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }
}
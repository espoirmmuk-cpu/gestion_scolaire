<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    protected $table = 'etablissements';

    protected $primaryKey = 'id_etablissement';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'code',
        'type',
        'province',
        'ville',
        'commune',
        'adresse',
        'telephone',
        'email',
        'directeur',
        'logo',
        'statut',
        'date_creation',
        'date_modification',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_modification' => 'datetime',
    ];
    public function personnels()
{
    return $this->hasMany(
        Personnel::class,
        'id_etablissement',
        'id_etablissement'
    );
}
}
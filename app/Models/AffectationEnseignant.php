<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffectationEnseignant extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'affectations_enseignants';

    /*
    |--------------------------------------------------------------------------
    | CLÉ PRIMAIRE
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'id_affectation';

    public $incrementing = true;

    protected $keyType = 'int';

    /*
    |--------------------------------------------------------------------------
    | TIMESTAMPS
    |--------------------------------------------------------------------------
    |
    | La table utilise date_creation et date_modification
    | au lieu des timestamps Laravel classiques created_at / updated_at.
    |
    */

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTS AUTORISÉS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'id_etablissement',
        'id_enseignant',
        'id_classe',
        'id_matiere',
        'id_annee_scolaire',
        'est_titulaire',
        'heures_semaine',
        'statut',
        'observation',
        'date_creation',
        'date_modification',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'id_etablissement'   => 'integer',
        'id_enseignant'      => 'integer',
        'id_classe'          => 'integer',
        'id_matiere'         => 'integer',
        'id_annee_scolaire'  => 'integer',
        'est_titulaire'      => 'boolean',
        'date_creation'      => 'datetime',
        'date_modification'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION : ÉTABLISSEMENT
    |--------------------------------------------------------------------------
    */

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION : ENSEIGNANT / PERSONNEL
    |--------------------------------------------------------------------------
    */

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(
            Personnel::class,
            'id_enseignant',
            'id_personnel'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION : CLASSE
    |--------------------------------------------------------------------------
    */

    public function classe(): BelongsTo
    {
        return $this->belongsTo(
            Classe::class,
            'id_classe',
            'id_classe'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION : MATIÈRE
    |--------------------------------------------------------------------------
    */

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(
            Matiere::class,
            'id_matiere',
            'id_matiere'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION : ANNÉE SCOLAIRE
    |--------------------------------------------------------------------------
    */

    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(
            AnneeScolaire::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }
}
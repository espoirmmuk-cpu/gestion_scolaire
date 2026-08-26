<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeScolaire extends Model
{
    protected $table = 'periodes_scolaires';

    protected $primaryKey = 'id_periode';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_annee_scolaire',
        'libelle',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Année scolaire
     */
    public function anneeScolaire()
    {
        return $this->belongsTo(
            AnneeScolaire::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Évaluations
     */
    public function evaluations()
    {
        return $this->hasMany(
            Evaluation::class,
            'id_periode',
            'id_periode'
        );
    }
}
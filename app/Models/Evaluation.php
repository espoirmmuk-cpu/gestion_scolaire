<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'evaluations';

    protected $primaryKey = 'id_evaluation';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_annee_scolaire',
        'id_classe',
        'id_matiere',
        'id_periode',
        'libelle',
        'type_evaluation',
        'note_maximale',
        'date_evaluation',
    ];

    protected $casts = [
        'note_maximale' => 'decimal:2',
        'date_evaluation' => 'date',
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
     * Classe
     */
    public function classe()
    {
        return $this->belongsTo(
            Classe::class,
            'id_classe',
            'id_classe'
        );
    }

    /**
     * Matière
     */
    public function matiere()
    {
        return $this->belongsTo(
            Matiere::class,
            'id_matiere',
            'id_matiere'
        );
    }

    /**
     * Période scolaire
     */
    public function periode()
    {
        return $this->belongsTo(
            PeriodeScolaire::class,
            'id_periode',
            'id_periode'
        );
    }

    /**
     * Notes de l'évaluation
     */
    public function notes()
    {
        return $this->hasMany(
            Note::class,
            'id_evaluation',
            'id_evaluation'
        );
    }
}
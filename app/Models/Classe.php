<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;
use App\Models\Niveau;

class Classe extends Model
{
    protected $table = 'classes';

    protected $primaryKey = 'id_classe';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'id_annee_scolaire',
        'id_niveau',
        'libelle',
        'option_classe',
        'capacite',
        'statut',
    ];

    protected $casts = [
        'capacite' => 'integer',
    ];

    /**
     * Année scolaire de la classe
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
     * Niveau de la classe
     */
    public function niveau()
    {
        return $this->belongsTo(
            Niveau::class,
            'id_niveau',
            'id_niveau'
        );
    }

     /*
    |--------------------------------------------------------------------------
    | Établissement
    |--------------------------------------------------------------------------
    */

    public function etablissement()
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Élèves / présences
    |--------------------------------------------------------------------------
    */

    public function presences()
    {
        return $this->hasMany(
            Presence::class,
            'id_classe',
            'id_classe'
        );
    }

}
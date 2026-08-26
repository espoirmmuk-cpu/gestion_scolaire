<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalActivite extends Model
{
    protected $table = 'journaux_activites';

    protected $primaryKey = 'id_journal';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur',
        'action',
        'table_concernee',
        'id_enregistrement',
        'anciennes_valeurs',
        'nouvelles_valeurs',
        'adresse_ip',
        'navigateur',
        'date_heure',
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];

    /**
     * Utilisateur ayant effectué l'action.
     */
    public function utilisateur()
    {
        return $this->belongsTo(
            User::class,
            'id_utilisateur',
            'id_utilisateur'
        );
    }

    /**
     * Établissement de l'utilisateur ayant effectué l'action.
     *
     * La relation passe par la table utilisateurs.
     */
    public function getIdEtablissementAttribute()
    {
        return $this->utilisateur?->id_etablissement;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Syncable;

class JournalActivite extends Model
{
    use Syncable;

    protected $table = 'journaux_activites';

    protected $primaryKey = 'id_journal';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur',
        'id_etablissement',
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
     * Établissement auquel appartient le journal.
     */
    public function etablissement()
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }

    /**
     * Renseigne automatiquement l'établissement
     * lors de la création du journal.
     */
    protected static function booted(): void
    {
        static::creating(function ($journal) {
            if (
                $journal->id_etablissement === null &&
                auth()->check()
            ) {
                $journal->id_etablissement = auth()->user()->id_etablissement;
            }
        });
    }
}
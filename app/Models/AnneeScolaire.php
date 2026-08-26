<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    /**
     * Table associée au modèle.
     */
    protected $table = 'annees_scolaires';

    /**
     * Clé primaire personnalisée.
     */
    protected $primaryKey = 'id_annee_scolaire';

    /**
     * La table utilise-t-elle les timestamps Laravel ?
     *
     * Non : la table utilise date_creation.
     */
    public $timestamps = false;

    /**
     * Champs pouvant être remplis.
     */
    protected $fillable = [
        'id_etablissement',
        'libelle',
        'date_debut',
        'date_fin',
        'est_active',
        'date_creation',
    ];

    /**
     * Conversion des types.
     */
    protected $casts = [
        'date_debut'   => 'date',
        'date_fin'     => 'date',
        'est_active'   => 'boolean',
        'date_creation' => 'datetime',
    ];

    /**
     * Relation avec l'établissement.
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
     * Relation avec les classes.
     */
    public function classes()
    {
        return $this->hasMany(
            Classe::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Relation avec les inscriptions.
     */
    public function inscriptions()
    {
        return $this->hasMany(
            Inscription::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Relation avec les périodes scolaires.
     */
    public function periodes()
    {
        return $this->hasMany(
            PeriodeScolaire::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Relation avec les évaluations.
     */
    public function evaluations()
    {
        return $this->hasMany(
            Evaluation::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Relation avec les paiements.
     */
    public function paiements()
    {
        return $this->hasMany(
            Paiement::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Relation avec les recettes.
     */
    public function recettes()
    {
        return $this->hasMany(
            Recette::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Relation avec les dépenses.
     */
    public function depenses()
    {
        return $this->hasMany(
            Depense::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Relation avec les bulletins.
     */
    public function bulletins()
    {
        return $this->hasMany(
            Bulletin::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }

    /**
     * Vérifie si l'année scolaire est active.
     */
    public function estActive(): bool
    {
        return (bool) $this->est_active;
    }

    /**
     * Scope permettant de récupérer uniquement
     * les années scolaires actives.
     */
    public function scopeActive($query)
    {
        return $query->where('est_active', 1);
    }

    /**
     * Scope permettant de récupérer les années
     * d'un établissement donné.
     */
    public function scopePourEtablissement($query, $idEtablissement)
    {
        return $query->where(
            'id_etablissement',
            $idEtablissement
        );
    }
}
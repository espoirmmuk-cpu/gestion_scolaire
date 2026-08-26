<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TarifScolaire;
use App\Models\FraisEleve;

class Inscription extends Model
{
    protected $table = 'inscriptions';

    protected $primaryKey = 'id_inscription';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_eleve',
        'id_annee_scolaire',
        'id_classe',
        'date_inscription',
        'statut',
        'observation',
    ];

    protected $casts = [
        'date_inscription' => 'date',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function eleve()
    {
        return $this->belongsTo(
            Eleve::class,
            'id_eleve',
            'id_eleve'
        );
    }


    public function classe()
    {
        return $this->belongsTo(
            Classe::class,
            'id_classe',
            'id_classe'
        );
    }


    public function anneeScolaire()
    {
        return $this->belongsTo(
            AnneeScolaire::class,
            'id_annee_scolaire',
            'id_annee_scolaire'
        );
    }


    public function fraisEleves()
    {
        return $this->hasMany(
            FraisEleve::class,
            'id_inscription',
            'id_inscription'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Génération automatique des frais
    |--------------------------------------------------------------------------
    */

    public function genererFrais()
    {
        $tarifs = TarifScolaire::where(
            'id_classe',
            $this->id_classe
        )
        ->where(
            'id_annee_scolaire',
            $this->id_annee_scolaire
        )
        ->get();


        foreach ($tarifs as $tarif) {

            $existe = FraisEleve::where(
                'id_eleve',
                $this->id_eleve
            )
            ->where(
                'id_inscription',
                $this->id_inscription
            )
            ->where(
                'id_tarif',
                $tarif->id_tarif
            )
            ->exists();


            if ($existe) {
                continue;
            }


            FraisEleve::create([
                'id_eleve' => $this->id_eleve,
                'id_inscription' => $this->id_inscription,
                'id_tarif' => $tarif->id_tarif,
                'montant' => $tarif->montant,
                'remise' => 0,
                'montant_a_payer' => $tarif->montant,
                'montant_paye' => 0,
                'solde' => $tarif->montant,
                'statut' => 'NON_PAYE',
            ]);
        }
    }
}

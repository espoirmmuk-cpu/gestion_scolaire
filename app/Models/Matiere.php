<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Syncable;

class Matiere extends Model
{
    use Syncable;
    
    protected $table = 'matieres';

    protected $primaryKey = 'id_matiere';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'code',
        'libelle',
        'coefficient',
        'statut',
    ];

    protected $casts = [
        'id_etablissement' => 'integer',
        'coefficient' => 'decimal:2',
    ];

    /**
     * Établissement auquel appartient la matière.
     */
    public function etablissement()
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }
}
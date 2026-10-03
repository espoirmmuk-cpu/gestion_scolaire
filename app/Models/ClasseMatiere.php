<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Syncable;

class ClasseMatiere extends Model
{
    use Syncable;

    protected $table = 'classes_matieres';

    protected $primaryKey = 'id_classe_matiere';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_classe',
        'id_matiere',
        'id_enseignant',
        'nombre_heures',
    ];

    protected $casts = [
        'id_classe' => 'integer',
        'id_matiere' => 'integer',
        'id_enseignant' => 'integer',
        'nombre_heures' => 'decimal:2',
    ];
}
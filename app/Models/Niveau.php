<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    protected $table = 'niveaux';

    protected $primaryKey = 'id_niveau';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'libelle',
        'ordre',
    ];

    protected $casts = [
        'ordre' => 'integer',
    ];
}
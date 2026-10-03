<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    use Syncable;

    protected $table = 'responsables';

    protected $primaryKey = 'id_responsable';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'postnom',
        'prenom',
        'telephone',
        'email',
        'adresse',
        'profession',
    ];

    protected $casts = [
        'date_creation' => 'datetime',
    ];
}
<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class DetailBulletin extends Model
{
    use Syncable;

    protected $table = 'details_bulletins';

    protected $primaryKey = 'id_detail';

    public $timestamps = false;

    protected $fillable = [
        'id_bulletin',
        'id_matiere',
        'total',
        'moyenne',
        'coefficient',
        'points',
        'appreciation',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'moyenne' => 'decimal:2',
        'coefficient' => 'decimal:2',
        'points' => 'decimal:2',
    ];
}

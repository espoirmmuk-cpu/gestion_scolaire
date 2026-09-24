<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $primaryKey = 'id_contact';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'message',
        'lu',
        'date_lu',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'date_lu' => 'datetime',
    ];
}
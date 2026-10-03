<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class CategorieInventaire extends Model
{
    use Syncable;

    protected $table = 'categories_inventaire';

    protected $primaryKey = 'id_categorie';

    public $timestamps = false;

    protected $fillable = [
        'libelle',
        'description',
    ];
}
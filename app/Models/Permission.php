<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use Syncable;

    protected $table = 'permissions';

    protected $primaryKey = 'id_permission';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'module',
        'action',
        'nom',
        'description',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'roles_permissions',
            'id_permission',
            'id_role',
            'id_permission',
            'id_role'
        );
    }
}
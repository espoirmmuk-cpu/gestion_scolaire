<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $primaryKey = 'id_permission';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
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
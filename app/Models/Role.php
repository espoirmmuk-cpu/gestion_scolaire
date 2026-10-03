<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Syncable;

    protected $table = 'roles';

    protected $primaryKey = 'id_role';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Utilisateurs ayant ce rôle.
     */
    public function utilisateurs()
    {
        return $this->belongsToMany(
            User::class,
            'utilisateurs_roles',
            'id_role',
            'id_utilisateur',
            'id_role',
            'id_utilisateur'
        );
    }

    /**
     * Permissions associées au rôle.
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'roles_permissions',
            'id_role',
            'id_permission',
            'id_role',
            'id_permission'
        );
    }
}
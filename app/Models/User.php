<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $primaryKey = 'id_utilisateur';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id_etablissement',
        'nom',
        'email',
        'mot_de_passe',
        'statut',
        'derniere_connexion',
        'date_creation',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    protected $casts = [
        'derniere_connexion' => 'datetime',
        'date_creation' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Authentification
    |--------------------------------------------------------------------------
    */

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function getRememberToken()
    {
        return '';
    }

    public function setRememberToken($value)
    {
        // Aucun champ remember_token dans la table utilisateurs.
    }

    public function getRememberTokenName()
    {
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function etablissement()
    {
        return $this->belongsTo(
            Etablissement::class,
            'id_etablissement',
            'id_etablissement'
        );
    }

    public function paiements()
    {
        return $this->hasMany(
            Paiement::class,
            'id_utilisateur',
            'id_utilisateur'
        );
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'utilisateurs_roles',
            'id_utilisateur',
            'id_role'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */

    public function aLaPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('nom', $permission);
            })
            ->exists();
    }

    public function aUnePermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->aLaPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function aLeRole(string $role): bool
    {
        return $this->roles()
            ->where('nom', $role)
            ->exists();
    }
}
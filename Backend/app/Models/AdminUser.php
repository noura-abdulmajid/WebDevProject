<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminUser extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;

    protected $table = 'admin_user';
    protected $primaryKey = 'A_ID';
    public $incrementing = true;
    protected $keyType = 'int';
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_MODERATOR = 'moderator';

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'surname',
        'date_joined',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeOfRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isSuperAdmin()
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }
}

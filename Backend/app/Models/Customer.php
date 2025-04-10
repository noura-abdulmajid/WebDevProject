<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Cashier\Billable;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, Billable;

    protected $table = 'customers';
    protected $primaryKey = 'C_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'first_name',
        'surname',
        'email_address',
        'password',
        'tel_no',
        'shipping_address',
        'billing_address',
        'date_joined',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_joined' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'C_ID');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'cart_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'P_ID',
        'size',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'P_ID', 'P_ID');
    }
}

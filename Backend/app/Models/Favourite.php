<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Products;

class Favourite extends Model
{
    use HasFactory;

    protected $table = 'favourites';
    public $timestamps = false;

    protected $fillable = [
        'C_ID',
        'P_ID',
        'added',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'C_ID');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'P_ID');
    }
}
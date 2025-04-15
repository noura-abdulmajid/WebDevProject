<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'I_ID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'P_ID',
        'color',
        'size',
        'price',
        'stock_level',
        'stock_status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_status' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'P_ID', 'P_ID');
    }
}

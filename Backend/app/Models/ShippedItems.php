<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippedItems extends Model
{
    use HasFactory;

    protected $table = 'shipped_items';
    protected $primaryKey = 'SI_ID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'S_ID',
        'I_ID',
        'quantity',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipped::class, 'S_ID', 'S_ID');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'I_ID', 'I_ID');
    }
}

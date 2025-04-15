<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'reason',
        'status',
        'admin_notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'O_ID');
    }
} 
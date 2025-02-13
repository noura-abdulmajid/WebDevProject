<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'shipping_address',
        'subtotal',
        'delivery_charge',
        'total_payment'
    ];

    public function orderedItems()
    {
        return $this->hasMany(OrderedItem::class, 'order_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

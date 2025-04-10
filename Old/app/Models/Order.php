<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public function ordered_item(): HasMany
    {
        return $this->hasMany(OrderedItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function returns(): HasOne
    {
        return $this->hasOne(Returns::class);
    }
}

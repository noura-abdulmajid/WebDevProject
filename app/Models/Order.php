<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public function ordered_items(): HasMany
    {
        return $this->hasMany(OrderedItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

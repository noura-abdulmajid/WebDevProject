<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'O_ID',
        'quantity'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}

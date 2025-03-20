<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Table name corresponds to the `orders` table
    protected $table = 'orders';

    // Primary key matches the database schema, which is `O_ID`
    protected $primaryKey = 'O_ID';

    // Primary key type is Big Integer and auto-incrementing
    public $incrementing = true;
    protected $keyType = 'int';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'C_ID',
        'order_date',
        'shipping_address',
        'subtotal',
        'delivery_charge',
        'total_payment',
    ];

    /**
     * Relation with OrderedItem: An order has many ordered items.
     */
    public function orderedItems(): HasMany
    {
        return $this->hasMany(OrderedItem::class, 'O_ID', 'O_ID');
    }
    
    /**
     * Relation with Customer: An order belongs to a customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'C_ID', 'C_ID');
    }
}
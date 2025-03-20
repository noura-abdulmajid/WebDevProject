<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedItem extends Model
{
    use HasFactory;

    // Table name corresponds to the `ordered_items` table
    protected $table = 'ordered_items';

    // Primary key of the table
    protected $primaryKey = 'OI_ID';

    // Primary key type
    public $incrementing = true;
    protected $keyType = 'int';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'O_ID',
        'quantity',
    ];

    /**
     * Relation with Order: An ordered item belongs to an order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'O_ID', 'O_ID');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedItems extends Model
{
    use HasFactory;

    protected $table = 'ordered_items';
    protected $primaryKey = 'OI_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'O_ID',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'O_ID', 'O_ID');
    }
}

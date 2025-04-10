<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $primaryKey = 'cart_ID';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'P_ID',
        'size',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'P_ID', 'P_ID');
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'P_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'p_name',
        'description',
        'categories',
        'colours',
        'photo',
        'sizes',
        'price',
        'sustainability',
        'overall_stock_status',
    ];

    protected $casts = [
        'categories' => 'array',
        'colours' => 'array',
        'sizes' => 'array',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'P_ID', 'P_ID');
    }
}
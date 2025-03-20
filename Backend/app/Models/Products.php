<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'P_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'p_name',
        'gender_target',
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
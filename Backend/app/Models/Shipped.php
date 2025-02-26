<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipped extends Model
{
    use HasFactory;

    protected $table = 'shipped';
    protected $primaryKey = 'S_ID';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'O_ID',
        'shipped_date',
        'shipped_by',
        'stationery_printed',
        'delivery_status',
    ];

    protected $casts = [
        'shipped_date' => 'datetime',
        'stationery_printed' => 'boolean',
        'delivery_status' => 'string',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'O_ID', 'O_ID');
    }

    public function admin()
    {
        return $this->belongsTo(AdminUsers::class, 'shipped_by', 'A_ID');
    }
}

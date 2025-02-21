<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingOrder extends Model
{
    use HasFactory;

    protected $table = 'incoming_orders';
    protected $primaryKey = 'IN_ID';
    public $incrementing = true;
    protected $keyType = 'int';
}

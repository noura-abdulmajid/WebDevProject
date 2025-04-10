<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $fillable = [
        'O_ID',
        'return_reason',
        'return_value',
        'date_started',
        'receipt_deadline',
        'receipt_status',
        'receipt_date',
        'refund_status',
        'refund_date'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Returns extends Model
{
    protected $table = 'returns';

    public function order()
    {
        $this->belongsTo(Order::class);
    }

    public function customer()
    {
        $this->belongsTo(Customer::class);
    }

    public function adminUser()
    {
        $this->belongsTo(AdminUser::class);
    }
}

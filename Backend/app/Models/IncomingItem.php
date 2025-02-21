<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    use HasFactory;

    protected $table = 'incoming_items';
    protected $primaryKey = 'INI_ID';

    public $incrementing = true;
    protected $keyType = 'int';
}

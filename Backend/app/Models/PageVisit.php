<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    use HasFactory;

    protected $table = 'page_visits';

    protected $fillable = [
        'ip',
        'page',
        'timestamp',
    ];
}
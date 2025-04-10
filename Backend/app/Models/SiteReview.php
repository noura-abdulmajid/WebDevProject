<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteReview extends Model
{
    use HasFactory;

    protected $table = 'site_reviews';
    protected $primaryKey = 'C_ID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $guarded = [];
}

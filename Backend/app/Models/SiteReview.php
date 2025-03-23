<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteReview extends Model
{
    use HasFactory;

    protected $table = 'site_reviews';
    protected $primaryKey = 'SR_ID';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'review_email',
        'review',
        'rating',
        'is_read',
        'is_replied',
        'reply'
    ];

    public $timestamps = true;
}
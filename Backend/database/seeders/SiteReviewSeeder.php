<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteReview;

class SiteReviewSeeder extends Seeder
{

    public function run(): void
    {

        SiteReview::factory(50)->create();
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeds the products table with pre-set products
        DB::table('products')->insert([
            'name' => Str::random(10),
            'description' => Str::random(50),
            'categories' => 'boots',
            'price' => rand(1, 100),
            'colours' => 'yellow',
            'photo_ref' => '1.jpg',
            'sizes' => '5',
            'sustainability' => 1,
            'overall_stock_status' => 1,

        ]);
    }
}

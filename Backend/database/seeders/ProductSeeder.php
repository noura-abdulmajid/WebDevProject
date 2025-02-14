<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'p_name' => 'White Loafers',
                'description' => 'A pair of stylish white loafers.',
                'categories' => json_encode(['Formal', 'Casual']),
                'colours' => json_encode(['White']),
                'photo' => 'white loafers.avif',
                'sizes' => json_encode(['38', '39', '40']),
                'price' => 40,
                'sustainability' => false,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'p_name' => 'White Laced Trainers',
                'description' => 'Comfortable white trainers with laces.',
                'categories' => json_encode(['Sports', 'Casual']),
                'colours' => json_encode(['White']),
                'photo' => 'white trainers.jpeg',
                'sizes' => json_encode(['38', '39', '40', '41']),
                'price' => 82,
                'sustainability' => true,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'p_name' => 'Cream Heels',
                'description' => 'Elegant cream heels for special occasions.',
                'categories' => json_encode(['Formal']),
                'colours' => json_encode(['Cream']),
                'photo' => 'cream heels.jpeg',
                'sizes' => json_encode(['37', '38', '39']),
                'price' => 60,
                'sustainability' => true,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'p_name' => 'Flats',
                'description' => 'Comfortable and easy-to-wear flats.',
                'categories' => json_encode(['Casual']),
                'colours' => json_encode(['Beige']),
                'photo' => 'flats.avif',
                'sizes' => json_encode(['36', '37', '38']),
                'price' => 25,
                'sustainability' => false,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'p_name' => 'Black Shoes',
                'description' => 'Classic black shoes for formal occasions.',
                'categories' => json_encode(['Formal']),
                'colours' => json_encode(['Black']),
                'photo' => 'black shoes.jpeg',
                'sizes' => json_encode(['39', '40', '41']),
                'price' => 55,
                'sustainability' => true,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'p_name' => 'Red Heels',
                'description' => 'Bright red heels for standout style.',
                'categories' => json_encode(['Formal']),
                'colours' => json_encode(['Red']),
                'photo' => 'red heels.png',
                'sizes' => json_encode(['36', '37', '38']),
                'price' => 65,
                'sustainability' => false,
                'overall_stock_status' => 'out_of_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'p_name' => 'Sport Sneakers',
                'description' => 'Durable sneakers for sports activities.',
                'categories' => json_encode(['Sports']),
                'colours' => json_encode(['Grey', 'White']),
                'photo' => 'https://via.placeholder.com/200',
                'sizes' => json_encode(['39', '40', '41', '42']),
                'price' => 70,
                'sustainability' => true,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'p_name' => 'Brown Loafers',
                'description' => 'Timeless brown loafers for any look.',
                'categories' => json_encode(['Casual', 'Formal']),
                'colours' => json_encode(['Brown']),
                'photo' => 'https://via.placeholder.com/200',
                'sizes' => json_encode(['40', '41', '42']),
                'price' => 50,
                'sustainability' => false,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('P_ID');
            $table->string('p_name');
            $table->text('description');
            $table->json('categories');
            $table->json('colours');
            $table->string('photo');
            $table->json('sizes');
            $table->decimal('price', 10, 2);
            $table->boolean('sustainability');
            $table->enum('overall_stock_status', ['in_stock', 'out_of_stock', 'discontinued']);
            $table->timestamps();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_ID');
            $table->unsignedBigInteger('P_ID');
            $table->string('size', 10);
            $table->integer('quantity')->unsigned()->default(10);
            $table->timestamps();

            $table->foreign('P_ID')->references('P_ID')->on('products')->onDelete('cascade');
        });

        $products = [
            [
                "name" => "Heart Trainers",
                "price" => 40,
                "image" => "/image/banner.png",
                "category" => "Trainers",
                "color" => "Pink",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Cute heart-themed trainers for everyday style."
            ],
            [
                "name" => "Black Trainers",
                "price" => 82,
                "image" => "/image/banner.png",
                "category" => "Trainers",
                "color" => "Black",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Sleek black trainers, perfect for running errands or hitting the gym."
            ],
            [
                "name" => "Black Shoes",
                "price" => 60,
                "image" => "/image/banner.png",
                "category" => "Loafers",
                "color" => "Black",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Classy black shoes for smart or business-casual occasions."
            ],
            [
                "name" => "Red Shoes",
                "price" => 25,
                "image" => "/image/banner.png",
                "category" => "Heels",
                "color" => "Red",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Bright red shoes that add a pop of color to any outfit."
            ],
            [
                "name" => "Silver Shoes",
                "price" => 55,
                "image" => "/image/banner.png",
                "category" => "Loafers",
                "color" => "Silver",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Eye-catching silver loafers to elevate your look."
            ],
            [
                "name" => "Studded Ballerina Flats",
                "price" => 65,
                "image" => "/image/banner.png",
                "category" => "Ballerinas",
                "color" => "Black",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Stylish flats with studded details, combining comfort and flair."
            ],
            [
                "name" => "Black Boots",
                "price" => 70,
                "image" => "/image/banner.png",
                "category" => "Boots",
                "color" => "Black",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Versatile black boots for cooler weather or a bold statement."
            ],
            [
                "name" => "Printed Shoes",
                "price" => 50,
                "image" => "/image/banner.png",
                "category" => "Loafers",
                "color" => "Printed",
                "sizes" => ["3", "4", "5", "6", "7", "8"],
                "description" => "Fun printed design that stands out in any crowd."
            ]
        ];

        foreach ($products as $product) {
            $P_ID = DB::table('products')->insertGetId([
                'p_name' => $product['name'],
                'description' => $product['description'],
                'categories' => json_encode([$product['category']]),
                'colours' => json_encode([$product['color']]),
                'photo' => $product['image'],
                'sizes' => json_encode($product['sizes']),
                'price' => $product['price'],
                'sustainability' => true,
                'overall_stock_status' => 'in_stock',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($product['sizes'] as $size) {
                DB::table('carts')->insert([
                    'P_ID' => $P_ID,
                    'size' => $size,
                    'quantity' => 10,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('products');
    }
};

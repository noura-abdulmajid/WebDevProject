<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description');
            $table->set('categories', ['boots', 'trainers', 'sandals', 'loafers', 'workwear', 'smart shoes', 'occassionwear']);
            $table->set('colours', ['blue', 'green', 'red', 'yellow', 'purple', 'white', 'black', 'pink', 'neutral', 'brown', 'tan']);
            $table->binary('photo');
            $table->set('sizes', [3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9]);
            $table->decimal('price', 9, 2);
            $table->boolean('sustainability');
            $table->boolean('overall_stock_status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

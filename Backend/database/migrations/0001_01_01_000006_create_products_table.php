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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

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
            $table->id('product_id');
            $table->string('p_name');
            $table->text('description');
            $table->text('categories');
            $table->text('colours');
            //$table->binary('photo');
            $table->decimal('price', 6, 2);
            //$table->boolean('sustainability');
            //$table->boolean('overall_stock_status');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->bigIncrements('I_ID');
            $table->unsignedBigInteger('P_ID');
            $table->integer('size');
            $table->decimal('price', 10, 2);
            $table->integer('stock_level');
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');

            $table->foreign('P_ID')->references('P_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};

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
        Schema::create('incoming_items', function (Blueprint $table) {
            $table->bigIncrements('INI_ID');
            $table->unsignedBigInteger('IN_ID');
            $table->unsignedBigInteger('I_ID');
            $table->integer('quantity');

            $table->foreign('IN_ID')->references('IN_ID')->on('incoming_orders')->onDelete('cascade');
            $table->foreign('I_ID')->references('I_ID')->on('inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_items');
    }
};

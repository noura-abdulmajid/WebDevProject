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
        Schema::create('shipped_items', function (Blueprint $table) {
            $table->bigIncrements('SI_ID');
            $table->unsignedBigInteger('S_ID');
            $table->unsignedBigInteger('I_ID');
            $table->integer('quantity');

            $table->foreign('S_ID')->references('S_ID')->on('shipped')->onDelete('cascade');
            $table->foreign('I_ID')->references('I_ID')->on('inventory')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipped_items');
    }
};

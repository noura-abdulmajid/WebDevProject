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
        Schema::create('favourites', function (Blueprint $table) {
            $table->id('favourite_ID');
            $table->unsignedBigInteger('C_ID');
            $table->unsignedBigInteger('P_ID');
            $table->timestamp('added')->useCurrent();

            $table->foreign('C_ID')->references('C_ID')->on('customers')->onDelete('cascade');
            $table->foreign('P_ID')->references('P_ID')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};

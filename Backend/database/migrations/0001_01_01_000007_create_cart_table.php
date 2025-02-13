<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_ID');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('P_ID');
            $table->string('size', 10);
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('C_ID')->on('customers')->onDelete('cascade');
            $table->foreign('P_ID')->references('P_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
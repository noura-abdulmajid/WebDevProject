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
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('cart_ID');
            $table->unsignedBigInteger('C_ID');
            $table->unsignedBigInteger('P_ID');
            $table->string('size', 10);
            $table->integer('quantity')->unsigned()->default(10);
            $table->timestamps();

            $table->foreign('C_ID')->references('C_ID')->on('customers')->onDelete('cascade');
            $table->foreign('P_ID')->references('P_ID')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

<?php

use App\Models\Order;
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
        Schema::create('ordered_items', function (Blueprint $table) {
            $table->id('OI_ID');
            $table->unsignedBigInteger('O_ID');
            $table->unsignedBigInteger('P_ID');
            //$table->foreignIdFor(Inventory::class, 'inventory_id');
            $table->text('name');

            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('O_ID')->references('O_ID')->on('orders')->onDelete('cascade');
            $table->foreign('P_ID')->references('P_ID')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordered_items');
    }
};

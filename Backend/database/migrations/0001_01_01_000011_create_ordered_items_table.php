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
            $table->id('O_ID');
            $table->foreignIdFor(Order::class, 'order_id');
            //$table->foreignIdFor(Inventory::class, 'inventory_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('O_ID')->references('O_ID')->on('orders')->onDelete('cascade');
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

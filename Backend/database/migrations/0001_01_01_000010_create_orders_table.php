<?php

use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('O_ID');
            $table->unsignedBigInteger('C_ID')->nullable();
            $table->dateTime('order_date');
            $table->text('shipping_address');
            $table->double('subtotal');
            $table->double('delivery_charge');
            $table->double('total_payment');
            $table->timestamps();

            $table->foreign('C_ID')->references('C_ID')->on('customers')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

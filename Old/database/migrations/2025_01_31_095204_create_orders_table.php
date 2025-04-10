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
            $table->id();
            //$table->foreignIdFor(User::class);
            $table->dateTime('order_date');
            $table->text('shipping_address');
            $table->decimal('subtotal', 6, 2);
            $table->decimal('delivery_charge', 6, 2);
            $table->decimal('total_payment', 6, 2);
            //$table->set('payment_status');
            $table->timestamps();
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

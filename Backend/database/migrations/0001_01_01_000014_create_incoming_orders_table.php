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
        Schema::create('incoming_orders', function (Blueprint $table) {
            $table->id('IN_ID');
            $table->integer('quantity');
            $table->dateTime('order_date');
            $table->unsignedBigInteger('ordered_by');
            $table->date('expected_arrival');
            $table->set('status', ['pending', 'shipped', 'delivered', 'canceled'])->default('pending');
            $table->date('arrival_date')->nullable();

            $table->foreign('ordered_by')->references('A_ID')->on('admin_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_orders');
    }
};

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
        Schema::create('shipped', function (Blueprint $table) {
            $table->bigIncrements('S_ID');
            $table->unsignedBigInteger('O_ID');
            $table->timestamp('shipped_date')->nullable();
            $table->unsignedBigInteger('shipped_by');
            $table->boolean('stationery_printed')->default(false);
            $table->set('delivery_status', ['pending', 'shipped', 'delivered', 'canceled'])->default('pending');

            $table->foreign('O_ID')->references('O_ID')->on('orders')->onDelete('cascade');
            $table->foreign('shipped_by')->references('A_ID')->on('admin_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipped');
    }
};

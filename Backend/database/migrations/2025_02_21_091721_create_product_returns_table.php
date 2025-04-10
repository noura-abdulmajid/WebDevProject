<?php

use App\Models\AdminUser;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductReturn;
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
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id('R_ID');
            $table->foreignIdFor(Customer::class, 'C_ID')->default(0)->nullable();
            $table->foreignIdFor(Order::class, 'O_ID')->default(0);
            //$table->foreignIdFor(AdminUser::class, 'A_ID')->default(0);
            $table->text('return_reason');
            $table->decimal('return_value', 10, 2)->default(0);
            $table->date('date_started');
            $table->date('return_deadline')->nullable();
            $table->boolean('receipt_status')->default(false);
            $table->date('receipt_date')->nullable();
            $table->boolean('refund_rejected)')->default(false);
            $table->text('rejection_reason')->nullable();
            $table->boolean('refund_status')->default(false);
            $table->date('refund_date')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};

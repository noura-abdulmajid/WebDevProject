<?php

use App\Models\AdminUser;
use App\Models\Customer;
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
        Schema::create('returns', function (Blueprint $table) {
            $table->id('R_ID');
            $table->foreignIdFor(Customer::class, 'C_ID');
            $table->foreignIdFor(Order::class, 'O_ID');
            //$table->list('return_list');
            $table->set('return_reason', [
                "Does Not Fit", "Low Quality", "Bought Multiple Sizes", "Do Not Like Them", "Other"]);
            $table->decimal('refund_value', 6, 2);
            $table->dateTime('date_started');
            $table->boolean('receipt_status');
            $table->dateTime('receipt_date');
            $table->foreignIdFor(AdminUser::class, 'A_ID');
            $table->boolean('refund_status');
            $table->dateTime('refund_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};

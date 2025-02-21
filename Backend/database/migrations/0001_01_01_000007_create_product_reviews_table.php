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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id('PR_ID');
            $table->unsignedBigInteger('C_ID');
            $table->string('title')->nullable();
            $table->text('review_body');
            $table->set('rating', ['1', '2', '3', '4', '5']);
            $table->date('review_date')->useCurrent();

            $table->foreign('C_ID')->references('C_ID')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};

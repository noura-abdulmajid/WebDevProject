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
        // Add indexes to products table
        Schema::table('products', function (Blueprint $table) {
            // Index for frequently searched fields
            $table->index('p_name');
            $table->index('gender_target');
            $table->index('overall_stock_status');
            
            // Fulltext index for search functionality
            $table->fullText(['p_name', 'description']);
        });

        // Add indexes to inventory table
        Schema::table('inventory', function (Blueprint $table) {
            // Index for foreign key
            $table->index('P_ID');
            
            // Index for frequently filtered fields
            $table->index('color');
            $table->index('size');
            $table->index('stock_status');
            
            // Composite index for common queries
            $table->index(['P_ID', 'color', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['p_name']);
            $table->dropIndex(['gender_target']);
            $table->dropIndex(['overall_stock_status']);
            $table->dropFullText(['p_name', 'description']);
        });

        // Remove indexes from inventory table
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropIndex(['P_ID']);
            $table->dropIndex(['color']);
            $table->dropIndex(['size']);
            $table->dropIndex(['stock_status']);
            $table->dropIndex(['P_ID', 'color', 'size']);
        });
    }
}; 
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
        Schema::table('phones', function (Blueprint $table) {
            // Cached balance; the source of truth is the stock_movements ledger.
            $table->unsignedInteger('stock_quantity')->default(0)->after('cost_price');
            $table->unsignedInteger('reorder_level')->default(5)->after('stock_quantity');
            $table->index('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->dropIndex(['stock_quantity']);
            $table->dropColumn(['stock_quantity', 'reorder_level']);
        });
    }
};

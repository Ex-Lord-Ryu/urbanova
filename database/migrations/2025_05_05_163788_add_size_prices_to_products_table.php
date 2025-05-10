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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_size_prices')->default(false)->after('price');
            $table->decimal('base_price', 12, 2)->nullable()->after('has_size_prices');
            $table->decimal('price_increase', 5, 2)->nullable()->after('base_price');
            $table->json('size_prices')->nullable()->after('price_increase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_size_prices');
            $table->dropColumn('base_price');
            $table->dropColumn('price_increase');
            $table->dropColumn('size_prices');
        });
    }
};
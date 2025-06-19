<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration {
    public function up(): void {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();

            // Removing the unique constraint here allows multiple colors per size
            // and different prices per size/color combination
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_variants');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescriptionTemplatesTable extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('description_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Buat pivot table untuk relasi many-to-many
        Schema::create('description_template_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('description_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('description_template_product');
        Schema::dropIfExists('description_templates');
    }
}

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
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained()->onDelete('cascade');
            $table->foreignId('source_product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('target_product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('source_quantity', 10, 3); // количество исходного продукта
            $table->decimal('target_quantity', 10, 3); // количество целевого продукта
            $table->enum('conversion_type', ['equal', 'unequal']); // равнозначная или неравнозначная
            $table->text('notes')->nullable(); // заметки о конвертации
            $table->timestamps();
            
            $table->index(['source_product_id', 'target_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
}; 
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recount_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recount_id')->constrained()->onDelete('cascade');
            $table->morphs('countable'); // product_id или element_id
            $table->decimal('expected_quantity', 10, 3); // Ожидаемое количество
            $table->decimal('actual_quantity', 10, 3)->nullable(); // Фактическое количество
            $table->decimal('discrepancy', 10, 3)->default(0); // Расхождение
            $table->decimal('unit_price', 10, 2); // Цена за единицу на момент переучета
            $table->decimal('discrepancy_amount', 15, 2)->default(0); // Сумма расхождения
            $table->text('notes')->nullable(); // Примечания к позиции
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recount_items');
    }
}; 
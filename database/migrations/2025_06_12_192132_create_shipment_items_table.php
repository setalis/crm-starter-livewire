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
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->float('weight'); // вес отгружаемого металла
            $table->enum('writeoff_type', ['partial', 'full']); // тип списания: с остатком или в ноль
            $table->float('stock_after')->nullable(); // остаток на складе после отгрузки
            // Второй этап
            $table->float('actual_weight')->nullable(); // фактический вес по позиции
            $table->float('actual_price')->nullable(); // цена на заводе по позиции
            $table->float('actual_clogging')->nullable(); // засор по позиции
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_items');
    }
};

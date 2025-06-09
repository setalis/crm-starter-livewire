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
        Schema::create('elements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Наименование');
            $table->foreignId('unit_id')
                ->comment('Единица измерения')
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('price', 15, 2)->comment('Стоимость');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elements');
    }
};

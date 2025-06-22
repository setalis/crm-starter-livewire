<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_recounts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Номер переучета кассы
            $table->foreignId('cash_register_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Кто проводит
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->decimal('expected_balance', 15, 2); // Ожидаемый баланс
            $table->decimal('actual_balance', 15, 2)->nullable(); // Фактический баланс
            $table->decimal('discrepancy', 15, 2)->default(0); // Расхождение
            $table->text('reason')->nullable(); // Причина переучета
            $table->text('notes')->nullable(); // Примечания
            $table->timestamp('started_at')->nullable(); // Когда начат
            $table->timestamp('completed_at')->nullable(); // Когда завершен
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_recounts');
    }
}; 
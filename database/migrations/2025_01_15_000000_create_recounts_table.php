<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recounts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Номер переучета
            $table->enum('type', ['products', 'elements']); // Тип переучета
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Кто проводит
            $table->text('reason')->nullable(); // Причина переучета
            $table->text('notes')->nullable(); // Примечания
            $table->timestamp('started_at')->nullable(); // Когда начат
            $table->timestamp('completed_at')->nullable(); // Когда завершен
            $table->decimal('total_discrepancy_amount', 15, 2)->default(0); // Общая сумма расхождений
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recounts');
    }
}; 
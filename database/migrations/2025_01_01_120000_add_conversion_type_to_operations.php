<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Для SQLite используем другой подход
        if (DB::getDriverName() === 'sqlite') {
            // Удаляем временную таблицу, если она существует
            Schema::dropIfExists('operations_temp');
            
            // Создаем временную таблицу
            Schema::create('operations_temp', function (Blueprint $table) {
                $table->id();
                $table->string('operation_number')->nullable();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('type', ['purchase', 'sale', 'conversion']);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->foreignId('cash_register_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });

            // Копируем данные, учитывая существующие столбцы
            $columns = Schema::getColumnListing('operations');
            
            if (in_array('type', $columns)) {
                // Столбец type уже существует
                if (in_array('operation_number', $columns) && in_array('cash_register_id', $columns)) {
                    DB::statement('INSERT INTO operations_temp (id, operation_number, user_id, type, total_amount, cash_register_id, created_at, updated_at) SELECT id, operation_number, user_id, type, total_amount, cash_register_id, created_at, updated_at FROM operations');
                } else {
                    DB::statement('INSERT INTO operations_temp (id, user_id, type, total_amount, created_at, updated_at) SELECT id, user_id, type, total_amount, created_at, updated_at FROM operations');
                }
            } else {
                // Столбец type отсутствует, устанавливаем значение по умолчанию
                if (in_array('operation_number', $columns) && in_array('cash_register_id', $columns)) {
                    DB::statement("INSERT INTO operations_temp (id, operation_number, user_id, type, total_amount, cash_register_id, created_at, updated_at) SELECT id, operation_number, user_id, 'purchase', total_amount, cash_register_id, created_at, updated_at FROM operations");
                } else {
                    DB::statement("INSERT INTO operations_temp (id, user_id, type, total_amount, created_at, updated_at) SELECT id, user_id, 'purchase', total_amount, created_at, updated_at FROM operations");
                }
            }

            // Удаляем старую таблицу
            Schema::dropIfExists('operations');

            // Переименовываем временную таблицу
            Schema::rename('operations_temp', 'operations');
        } else {
            // Для других СУБД
            Schema::table('operations', function (Blueprint $table) {
                $table->dropColumn('type');
            });
            
            Schema::table('operations', function (Blueprint $table) {
                $table->enum('type', ['purchase', 'sale', 'conversion'])->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        
        Schema::table('operations', function (Blueprint $table) {
            $table->enum('type', ['purchase', 'sale'])->after('user_id');
        });
    }
}; 
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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // пользователь, создавший отгрузку
            $table->string('car_number')->nullable(); // номер автомобиля
            $table->string('driver_name')->nullable(); // ФИО водителя
            $table->string('company')->nullable(); // предприятие
            $table->text('comment')->nullable(); // комментарий
            $table->enum('stage', ['draft', 'confirmed'])->default('draft'); // этап: черновик/подтверждено
            $table->float('actual_weight')->nullable(); // фактический вес с завода
            $table->float('actual_price')->nullable(); // цена на заводе
            $table->float('actual_clogging')->nullable(); // засор на заводе
            $table->float('shipping_cost')->default(0); // затраты на отгрузку
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};

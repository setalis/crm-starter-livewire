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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['simple', 'composite'])->default('simple');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->decimal('purchase_price', 8, 2)->nullable();
            $table->decimal('selling_price', 8, 2);
            $table->float('clogging')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->decimal('stock', 10, 3)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

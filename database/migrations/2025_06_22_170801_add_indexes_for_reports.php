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
        Schema::table('operations', function (Blueprint $table) {
            $table->index(['type', 'created_at']);
        });

        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->index(['created_at', 'type']);
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('operation_item_elements', function (Blueprint $table) {
            $table->index('element_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            $table->dropIndex(['type', 'created_at']);
        });

        Schema::table('cash_transactions', function (Blueprint $table) {
            $table->dropIndex(['created_at', 'type']);
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('operation_item_elements', function (Blueprint $table) {
            $table->dropIndex(['element_id']);
        });
    }
};

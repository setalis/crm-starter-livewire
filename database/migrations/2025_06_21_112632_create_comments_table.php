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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable'); // commentable_type и commentable_id для полиморфных связей
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // автор комментария
            $table->text('content'); // содержимое комментария
            $table->string('type')->default('comment'); // тип: comment, note, warning, info
            $table->boolean('is_read')->default(false); // прочитан ли комментарий
            $table->timestamp('read_at')->nullable(); // когда прочитан
            $table->foreignId('read_by')->nullable()->constrained('users')->onDelete('set null'); // кем прочитан
            $table->boolean('is_important')->default(false); // важный комментарий
            $table->json('metadata')->nullable(); // дополнительные метаданные
            $table->timestamps();
            
            // Индексы для быстрого поиска (morphs уже создает индекс для commentable_type, commentable_id)
            $table->index(['user_id', 'created_at']);
            $table->index(['is_read', 'created_at']);
            $table->index(['is_important', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

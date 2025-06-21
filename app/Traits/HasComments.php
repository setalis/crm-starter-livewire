<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    /**
     * Получить все комментарии для этой модели
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    /**
     * Получить непрочитанные комментарии
     */
    public function unreadComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->unread()->latest();
    }

    /**
     * Получить важные комментарии
     */
    public function importantComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->important()->latest();
    }

    /**
     * Добавить комментарий к модели
     */
    public function addComment(string $content, string $type = 'comment', bool $isImportant = false, array $metadata = null, int $userId = null): Comment
    {
        $comment = $this->comments()->create([
            'user_id' => $userId ?? auth()->id(),
            'content' => $content,
            'type' => $type,
            'is_important' => $isImportant,
            'metadata' => $metadata,
        ]);

        // События будут отправляться из компонентов Livewire, а не из трейта

        return $comment;
    }

    /**
     * Добавить системный комментарий (автоматически создается системой)
     */
    public function addSystemComment(string $content, array $metadata = null): Comment
    {
        return $this->addComment($content, 'system', false, $metadata, auth()->id());
    }

    /**
     * Добавить важную заметку
     */
    public function addImportantNote(string $content, array $metadata = null): Comment
    {
        return $this->addComment($content, 'note', true, $metadata);
    }

    /**
     * Добавить предупреждение
     */
    public function addWarning(string $content, array $metadata = null): Comment
    {
        return $this->addComment($content, 'warning', true, $metadata);
    }

    /**
     * Получить количество непрочитанных комментариев
     */
    public function getUnreadCommentsCountAttribute(): int
    {
        return $this->unreadComments()->count();
    }

    /**
     * Получить количество всех комментариев
     */
    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    /**
     * Проверить, есть ли непрочитанные комментарии
     */
    public function hasUnreadComments(): bool
    {
        return $this->unreadComments()->exists();
    }

    /**
     * Проверить, есть ли важные комментарии
     */
    public function hasImportantComments(): bool
    {
        return $this->importantComments()->exists();
    }

    /**
     * Отметить все комментарии как прочитанные
     */
    public function markAllCommentsAsRead(int $userId = null): void
    {
        $this->unreadComments()->each(function ($comment) use ($userId) {
            $comment->markAsRead($userId);
        });
    }

    /**
     * Получить последний комментарий
     */
    public function getLastCommentAttribute(): ?Comment
    {
        return $this->comments()->first();
    }

    /**
     * Получить комментарии определенного типа
     */
    public function getCommentsByType(string $type): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->byType($type)->latest();
    }
} 
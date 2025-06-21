<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'user_id',
        'content',
        'type',
        'is_read',
        'read_at',
        'read_by',
        'is_important',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_important' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Полиморфная связь с комментируемой сущностью
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Автор комментария
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Пользователь, который прочитал комментарий
     */
    public function readBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    /**
     * Скоупы для фильтрации
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Отметить комментарий как прочитанный
     */
    public function markAsRead($userId = null)
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
            'read_by' => $userId ?? auth()->id(),
        ]);
    }

    /**
     * Отметить комментарий как непрочитанный
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
            'read_by' => null,
        ]);
    }

    /**
     * Получить имя типа для отображения
     */
    public function getTypeDisplayNameAttribute()
    {
        return match($this->type) {
            'comment' => 'Комментарий',
            'note' => 'Заметка',
            'warning' => 'Предупреждение',
            'info' => 'Информация',
            'system' => 'Системное сообщение',
            default => 'Комментарий',
        };
    }

    /**
     * Получить CSS класс для типа
     */
    public function getTypeCssClassAttribute()
    {
        return match($this->type) {
            'comment' => 'text-gray-600',
            'note' => 'text-blue-600',
            'warning' => 'text-yellow-600',
            'info' => 'text-green-600',
            'system' => 'text-purple-600',
            default => 'text-gray-600',
        };
    }

    /**
     * Получить описание связанной сущности
     */
    public function getCommentableDescriptionAttribute()
    {
        $commentable = $this->commentable;
        
        if (!$commentable) {
            return 'Неизвестная сущность';
        }

        return match($this->commentable_type) {
            'App\Models\Product' => "Продукт: {$commentable->name}",
            'App\Models\Element' => "Элемент: {$commentable->name}",
            'App\Models\Operation' => "Операция: {$commentable->operation_number}",
            'App\Models\Shipment' => "Отгрузка #{$commentable->id}",
            'App\Models\CashTransaction' => "Касса: {$commentable->description}",
            'App\Models\Conversion' => "Конвертация #{$commentable->id}",
            default => "Объект #{$commentable->id}",
        };
    }
}

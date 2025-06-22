<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Получить значение настройки
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = 'setting_' . $key;
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Установить значение настройки
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): void
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group
            ]
        );

        // Очищаем кеш
        Cache::forget('setting_' . $key);
    }

    /**
     * Получить все настройки группы
     */
    public static function getGroup(string $group): array
    {
        $cacheKey = 'settings_group_' . $group;
        
        return Cache::remember($cacheKey, 3600, function () use ($group) {
            return static::where('group', $group)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Очистить кеш группы настроек
     */
    public static function clearGroupCache(string $group): void
    {
        Cache::forget('settings_group_' . $group);
    }
} 
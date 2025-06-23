<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class Settings
{
    /**
     * Получить название компании
     */
    public static function companyName(): string
    {
        return Setting::get('company_name', 'CRM Starter Kit');
    }

    /**
     * Получить валюту
     */
    public static function currency(): string
    {
        return Setting::get('currency', 'RUB');
    }

    /**
     * Получить символ валюты
     */
    public static function currencySymbol(): string
    {
        $symbols = [
            'RUB' => '₽',
            'USD' => '$',
            'EUR' => '€',
            'UAH' => '₴',
            'BYN' => 'Br',
            'KZT' => '₸',
            'UZS' => 'сўм',
        ];

        return $symbols[self::currency()] ?? self::currency();
    }

    /**
     * Получить часовой пояс
     */
    public static function timezone(): string
    {
        return Setting::get('timezone', 'Europe/Moscow');
    }

    /**
     * Получить URL логотипа компании
     */
    public static function companyLogo(): ?string
    {
        $logoPath = Setting::get('company_logo');
        
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            return Storage::url($logoPath);
        }

        return null;
    }

    /**
     * Получить формат даты
     */
    public static function dateFormat(): string
    {
        return Setting::get('date_format', 'd.m.Y');
    }

    /**
     * Получить формат времени
     */
    public static function timeFormat(): string
    {
        return Setting::get('time_format', 'H:i');
    }

    /**
     * Получить количество элементов на странице
     */
    public static function itemsPerPage(): int
    {
        return (int) Setting::get('items_per_page', 25);
    }

    /**
     * Проверить включены ли уведомления
     */
    public static function notificationsEnabled(): bool
    {
        return (bool) Setting::get('notifications_enabled', true);
    }

    /**
     * Проверить включены ли email уведомления
     */
    public static function emailNotificationsEnabled(): bool
    {
        return (bool) Setting::get('email_notifications', true);
    }

    /**
     * Форматировать цену с символом валюты
     */
    public static function formatPrice(?float $price): string
    {
        return number_format($price ?? 0, 2, '.', ' ') . ' ' . self::currencySymbol();
    }

    /**
     * Форматировать дату согласно настройкам
     */
    public static function formatDate($date): string
    {
        if (!$date) {
            return '';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        // Конвертируем в часовой пояс из настроек
        $date = $date->setTimezone(self::timezone());

        return $date->format(self::dateFormat());
    }

    /**
     * Форматировать дату и время согласно настройкам
     */
    public static function formatDateTime($datetime): string
    {
        if (!$datetime) {
            return '';
        }

        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }

        // Конвертируем в часовой пояс из настроек
        $datetime = $datetime->setTimezone(self::timezone());

        return $datetime->format(self::dateFormat() . ' ' . self::timeFormat());
    }

    /**
     * Форматировать только время согласно настройкам
     */
    public static function formatTime($time): string
    {
        if (!$time) {
            return '';
        }

        if (is_string($time)) {
            $time = \Carbon\Carbon::parse($time);
        }

        // Конвертируем в часовой пояс из настроек
        $time = $time->setTimezone(self::timezone());

        return $time->format(self::timeFormat());
    }

    /**
     * Получить текущее время в часовом поясе из настроек
     */
    public static function now(): \Carbon\Carbon
    {
        return \Carbon\Carbon::now(self::timezone());
    }

    /**
     * Форматировать длительность времени (diffForHumans)
     */
    public static function formatDuration($start, $end = null): string
    {
        if (!$start) {
            return '';
        }

        if (is_string($start)) {
            $start = \Carbon\Carbon::parse($start);
        }

        $start = $start->setTimezone(self::timezone());

        if ($end === null) {
            $end = self::now();
        } else {
            if (is_string($end)) {
                $end = \Carbon\Carbon::parse($end);
            }
            $end = $end->setTimezone(self::timezone());
        }

        return $start->diffForHumans($end, true);
    }
}
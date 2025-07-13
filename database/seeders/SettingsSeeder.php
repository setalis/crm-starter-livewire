<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Настройки компании
            [
                'key' => 'company_name',
                'value' => 'Metal CRM',
                'type' => 'string',
                'group' => 'company'
            ],
            [
                'key' => 'currency',
                'value' => 'UAH',
                'type' => 'string',
                'group' => 'company'
            ],
            [
                'key' => 'timezone',
                'value' => 'Europe/Kiev',
                'type' => 'string',
                'group' => 'company'
            ],
            [
                'key' => 'company_logo',
                'value' => null,
                'type' => 'file',
                'group' => 'company'
            ],

            // Общие настройки приложения
            [
                'key' => 'app_name',
                'value' => 'CRM System',
                'type' => 'string',
                'group' => 'general'
            ],
            [
                'key' => 'date_format',
                'value' => 'd.m.Y',
                'type' => 'string',
                'group' => 'general'
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'string',
                'group' => 'general'
            ],
            [
                'key' => 'items_per_page',
                'value' => 25,
                'type' => 'integer',
                'group' => 'general'
            ],

            // Настройки уведомлений
            [
                'key' => 'notifications_enabled',
                'value' => true,
                'type' => 'boolean',
                'group' => 'notifications'
            ],
            [
                'key' => 'email_notifications',
                'value' => true,
                'type' => 'boolean',
                'group' => 'notifications'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

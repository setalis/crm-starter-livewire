<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'name' => 'user_management',
                'display_name' => 'Управление пользователями',
                'description' => 'Управление пользователями, ролями и разрешениями',
                'icon' => 'users',
                'sort_order' => 1,
            ],
            [
                'name' => 'products',
                'display_name' => 'Товары',
                'description' => 'Управление товарами и продуктами',
                'icon' => 'package',
                'sort_order' => 2,
            ],
            [
                'name' => 'elements',
                'display_name' => 'Элементы',
                'description' => 'Управление элементами и единицами измерения',
                'icon' => 'components',
                'sort_order' => 3,
            ],
            [
                'name' => 'operations',
                'display_name' => 'Операции',
                'description' => 'Управление операциями и транзакциями',
                'icon' => 'activity',
                'sort_order' => 4,
            ],
            [
                'name' => 'warehouse',
                'display_name' => 'Склад',
                'description' => 'Управление складскими операциями',
                'icon' => 'warehouse',
                'sort_order' => 5,
            ],
            [
                'name' => 'cash',
                'display_name' => 'Касса',
                'description' => 'Управление кассовыми операциями',
                'icon' => 'banknote',
                'sort_order' => 6,
            ],
            [
                'name' => 'shipments',
                'display_name' => 'Отправки',
                'description' => 'Управление отправками товаров',
                'icon' => 'truck',
                'sort_order' => 7,
            ],
            [
                'name' => 'conversions',
                'display_name' => 'Конверсии',
                'description' => 'Управление конверсиями и преобразованиями',
                'icon' => 'refresh-cw',
                'sort_order' => 8,
            ],
            [
                'name' => 'analytics',
                'display_name' => 'Аналитика',
                'description' => 'Просмотр отчетов и аналитики',
                'icon' => 'bar-chart',
                'sort_order' => 9,
            ],
            [
                'name' => 'settings',
                'display_name' => 'Настройки',
                'description' => 'Системные настройки и конфигурация',
                'icon' => 'settings',
                'sort_order' => 10,
            ],
            [
                'name' => 'comments',
                'display_name' => 'Комментарии',
                'description' => 'Управление комментариями и системными сообщениями',
                'icon' => 'message-circle',
                'sort_order' => 11,
            ],
        ];

        foreach ($sections as $section) {
            Section::updateOrCreate(
                ['name' => $section['name']],
                $section
            );
        }
    }
}

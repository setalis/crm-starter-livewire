<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем роли
        $roles = [
            [
                'name' => 'super-admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'accountant',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manager',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        // Получаем разделы
        $sections = Section::all()->keyBy('name');

        // Создаем разрешения по разделам
        $permissions = [
            // Управление пользователями
            'user_management' => [
                'users.view' => 'Просмотр пользователей',
                'users.create' => 'Создание пользователей',
                'users.edit' => 'Редактирование пользователей',
                'users.delete' => 'Удаление пользователей',
                'roles.view' => 'Просмотр ролей',
                'roles.create' => 'Создание ролей',
                'roles.edit' => 'Редактирование ролей',
                'roles.delete' => 'Удаление ролей',
                'permissions.view' => 'Просмотр разрешений',
                'permissions.create' => 'Создание разрешений',
                'permissions.edit' => 'Редактирование разрешений',
                'permissions.delete' => 'Удаление разрешений',
                'sections.view' => 'Просмотр разделов',
                'sections.create' => 'Создание разделов',
                'sections.edit' => 'Редактирование разделов',
                'sections.delete' => 'Удаление разделов',
            ],
            // Товары
            'products' => [
                'products.view' => 'Просмотр товаров',
                'products.create' => 'Создание товаров',
                'products.edit' => 'Редактирование товаров',
                'products.delete' => 'Удаление товаров',
                'products.import' => 'Импорт товаров',
                'products.export' => 'Экспорт товаров',
            ],
            // Элементы
            'elements' => [
                'elements.view' => 'Просмотр элементов',
                'elements.create' => 'Создание элементов',
                'elements.edit' => 'Редактирование элементов',
                'elements.delete' => 'Удаление элементов',
                'units.view' => 'Просмотр единиц измерения',
                'units.create' => 'Создание единиц измерения',
                'units.edit' => 'Редактирование единиц измерения',
                'units.delete' => 'Удаление единиц измерения',
            ],
            // Операции
            'operations' => [
                'operations.view' => 'Просмотр операций',
                'operations.create' => 'Создание операций',
                'operations.edit' => 'Редактирование операций',
                'operations.delete' => 'Удаление операций',
                'operations.approve' => 'Подтверждение операций',
            ],
            // Склад
            'warehouse' => [
                'warehouse.view' => 'Просмотр складских остатков',
                'warehouse.manage' => 'Управление складом',
                'warehouse.inventory' => 'Инвентаризация',
            ],
            // Касса
            'cash' => [
                'cash.view' => 'Просмотр кассовых операций',
                'cash.create' => 'Создание кассовых операций',
                'cash.edit' => 'Редактирование кассовых операций',
                'cash.delete' => 'Удаление кассовых операций',
                'cash.close' => 'Закрытие кассы',
            ],
            // Отправки
            'shipments' => [
                'shipments.view' => 'Просмотр отправок',
                'shipments.create' => 'Создание отправок (черновик)',
                'shipments.edit' => 'Редактирование отправок',
                'shipments.confirm' => 'Подтверждение отправок (ввод фактических данных)',
                'shipments.delete' => 'Удаление отправок',
                'shipments.export' => 'Экспорт отчетов по отправкам',
            ],
            // Конверсии
            'conversions' => [
                'conversions.view' => 'Просмотр конверсий',
                'conversions.create' => 'Создание конверсий',
                'conversions.edit' => 'Редактирование конверсий',
                'conversions.delete' => 'Удаление конверсий',
            ],
            // Переучеты
            'recounts' => [
                'recounts.view' => 'Просмотр переучетов',
                'recounts.create' => 'Создание переучетов',
                'recounts.edit' => 'Редактирование переучетов',
                'recounts.start' => 'Начало переучетов',
                'recounts.complete' => 'Завершение переучетов',
                'recounts.cancel' => 'Отмена переучетов',
                'recounts.delete' => 'Удаление переучетов',
                'cash_recounts.view' => 'Просмотр переучетов кассы',
                'cash_recounts.create' => 'Создание переучетов кассы',
                'cash_recounts.edit' => 'Редактирование переучетов кассы',
                'cash_recounts.complete' => 'Завершение переучетов кассы',
                'cash_recounts.cancel' => 'Отмена переучетов кассы',
                'cash_recounts.delete' => 'Удаление переучетов кассы',
            ],
            // Аналитика
            'analytics' => [
                'analytics.view' => 'Просмотр аналитики',
                'analytics.export' => 'Экспорт отчетов',
                'analytics.advanced' => 'Расширенная аналитика',
            ],
            // Настройки
            'settings' => [
                'settings.view' => 'Просмотр настроек',
                'settings.edit' => 'Редактирование настроек',
                'settings.system' => 'Системные настройки',
            ],
            // Комментарии
            'comments' => [
                'comments.view' => 'Просмотр комментариев',
                'comments.create' => 'Создание комментариев',
                'comments.edit' => 'Редактирование комментариев',
                'comments.delete' => 'Удаление комментариев',
                'comments.manage' => 'Управление статусом прочтения',
                'comments.system' => 'Просмотр системных сообщений',
                'comments.all' => 'Доступ ко всем комментариям пользователей',
            ],
        ];

        // Создаем разрешения
        foreach ($permissions as $sectionName => $sectionPermissions) {
            $section = $sections->get($sectionName);
            
            foreach ($sectionPermissions as $permissionName => $displayName) {
                Permission::updateOrCreate(
                    ['name' => $permissionName],
                    [
                        'name' => $permissionName,
                        'display_name' => $displayName,
                        'guard_name' => 'web',
                        'section_id' => $section?->id,
                    ]
                );
            }
        }

        // Назначаем разрешения ролям
        $superAdmin = Role::where('name', 'super-admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $accountant = Role::where('name', 'accountant')->first();
        $manager = Role::where('name', 'manager')->first();

        // Супер админ получает все разрешения
        $superAdmin->syncPermissions(Permission::all());

        // Админ получает все разрешения кроме управления пользователями
        $adminPermissions = Permission::whereHas('section', function ($query) {
            $query->whereNotIn('name', ['user_management']);
        })->get();
        $admin->syncPermissions($adminPermissions);

        // Бухгалтер получает разрешения на финансовые операции, отгрузки, переучеты, аналитику и комментарии
        $accountantPermissions = Permission::whereHas('section', function ($query) {
            $query->whereIn('name', ['operations', 'cash', 'shipments', 'recounts', 'analytics', 'settings', 'comments']);
        })->whereNotIn('name', ['settings.system', 'comments.all', 'comments.delete'])->get();
        $accountant->syncPermissions($accountantPermissions);

        // Менеджер получает ограниченные разрешения для торговых операций и базовые комментарии
        $managerPermissions = Permission::whereIn('name', [
            'products.view',
            'operations.view',
            'operations.create',
            'operations.edit',
            'warehouse.view',
            'cash.view',
            'cash.create',
            'shipments.view',
            'shipments.create',
            'shipments.edit',
            'shipments.confirm',
            'recounts.view',
            'recounts.create',
            'recounts.edit',
            'recounts.start',
            'comments.view',
            'comments.create',
            'comments.edit',
            'comments.manage',
        ])->get();
        $manager->syncPermissions($managerPermissions);
    }
}

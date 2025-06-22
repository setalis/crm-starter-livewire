<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Сначала создаем разделы
        $this->call(SectionSeeder::class);
        
        // Затем роли и разрешения
        $this->call(RolePermissionSeeder::class);

        // Создаем супер админа
        $superAdmin = User::updateOrCreate(
            ['email' => 'slavrtm@gmail.com'],
            [
            'name' => 'Администратор',
            'email' => 'slavrtm@gmail.com',
            'password' => Hash::make('77788399'),
            ]
        );

        // Назначаем роль супер админа
        $superAdmin->assignRole('super-admin');

        // Создаем тестовых пользователей
        $this->call(TestUsersSeeder::class);

        // Остальные сидеры
        $this->call(UnitSeeder::class);
        $this->call(ElementSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CashRegisterSeeder::class);
        $this->call(RecountSeeder::class);
    }
}

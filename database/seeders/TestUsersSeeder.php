<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testUsers = [
            [
                'name' => 'Тестовый Админ',
                'email' => 'admin@test.ua',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ],
            [
                'name' => 'Тестовый Бухгалтер',
                'email' => 'accountant@test.ua',
                'password' => Hash::make('password'),
                'role' => 'accountant'
            ],
            [
                'name' => 'Тестовый Менеджер',
                'email' => 'manager@test.ua',
                'password' => Hash::make('password'),
                'role' => 'manager'
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => $userData['password'],
                ]
            );

            $user->assignRole($userData['role']);
        }
    }
}

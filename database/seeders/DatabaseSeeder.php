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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Администратор',
            'email' => 'slavrtm@gmail.com',
            'password' => Hash::make('77788399'),
        ]);

        $this->call(UnitSeeder::class);
        $this->call(ElementSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CashRegisterSeeder::class);
    }
}

<?php

namespace Database\Factories;

use App\Models\CashRegister;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashRegister>
 */
class CashRegisterFactory extends Factory
{
    protected $model = CashRegister::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Основная касса',
                'Вторая касса', 
                'Касса №1',
                'Касса металлоприема',
                'Касса склада'
            ]),
            'description' => $this->faker->optional()->sentence(),
            'balance' => $this->faker->randomFloat(2, 0, 500000), // от 0 до 500,000
            'is_active' => $this->faker->boolean(90), // 90% шанс что активна
        ];
    }

    /**
     * Indicate that the cash register is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the cash register has a specific balance.
     */
    public function withBalance(float $balance): static
    {
        return $this->state(fn (array $attributes) => [
            'balance' => $balance,
        ]);
    }
} 
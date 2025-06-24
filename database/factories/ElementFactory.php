<?php

namespace Database\Factories;

use App\Models\Element;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Element>
 */
class ElementFactory extends Factory
{
    protected $model = Element::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement([
                'Железо',
                'Медь', 
                'Алюминий',
                'Цинк',
                'Свинец',
                'Олово',
                'Никель'
            ]),
            'unit_id' => Unit::factory(),
            'price' => $this->faker->randomFloat(2, 0.05, 1.5), // от 5 копеек до 1.5 рублей за грамм
            'unit_price' => $this->faker->randomFloat(2, 50, 1500), // цена за единицу
            'stock' => $this->faker->randomFloat(3, 0, 10000), // остаток в граммах
        ];
    }
} 
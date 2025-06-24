<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['simple', 'composite']);
        
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement([
                'Медь электролитическая',
                'Алюминий вторичный',
                'Железо лом',
                'Латунь ЛС59-1',
                'Бронза БрАЖ9-4',
                'Нержавеющая сталь',
                'Свинец кабельный'
            ]),
            'type' => $type,
            'unit_id' => Unit::factory(),
            'purchase_price' => $type === 'simple' ? $this->faker->randomFloat(2, 50, 1000) : null,
            'selling_price' => $this->faker->randomFloat(2, 80, 1200),
            'clogging' => $type === 'simple' ? $this->faker->randomFloat(1, 0, 15) : null,
            'image' => null,
            'is_published' => $this->faker->boolean(80), // 80% шанс что опубликован
            'stock' => $this->faker->randomFloat(3, 0, 500),
            'position' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the product is simple type.
     */
    public function simple(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'simple',
            'purchase_price' => $this->faker->randomFloat(2, 50, 1000),
            'clogging' => $this->faker->randomFloat(1, 0, 15),
        ]);
    }

    /**
     * Indicate that the product is composite type.
     */
    public function composite(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'composite',
            'purchase_price' => null,
            'clogging' => null,
        ]);
    }

    /**
     * Indicate that the product is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }
} 
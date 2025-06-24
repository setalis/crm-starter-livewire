<?php

namespace Database\Factories;

use App\Models\ProductPriceScale;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPriceScale>
 */
class ProductPriceScaleFactory extends Factory
{
    protected $model = ProductPriceScale::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'threshold_kg' => $this->faker->randomElement([5, 10, 25, 50, 100]), // пороги в кг
            'price' => $this->faker->randomFloat(2, 50, 800), // цена за кг
        ];
    }
} 
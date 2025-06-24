<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Element;
use App\Models\Unit;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_composite_product_price_correctly()
    {
        // Создаем пользователя
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Создаем единицу измерения
        $unit = Unit::create([
            'name' => 'Килограмм',
            'short_name' => 'кг'
        ]);

        // Создаем элементы
        $iron = Element::create([
            'name' => 'Железо',
            'unit_id' => $unit->id,
            'price' => 100,
            'user_id' => $user->id,
            'stock' => 1000
        ]);

        $copper = Element::create([
            'name' => 'Медь',
            'unit_id' => $unit->id,
            'price' => 500,
            'user_id' => $user->id,
            'stock' => 1000
        ]);

        // Создаем составной продукт
        $product = Product::create([
            'name' => 'Латунь',
            'type' => 'composite',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'selling_price' => 550,
            'is_published' => true,
            'stock' => 0
        ]);

        // Добавляем элементы с процентами
        $product->elements()->attach($iron->id, ['percentage' => 70]);
        $product->elements()->attach($copper->id, ['percentage' => 30]);

        // Проверяем расчет цены за килограмм
        // Ожидаемая цена: (100 * 70) + (500 * 30) = 7000 + 15000 = 22000
        $this->assertEquals(22000, $product->getCompositeProductPricePerKg());

        // Проверяем расчет общей стоимости для 2.5 кг
        $this->assertEquals(55000, $product->getCompositeProductTotalPrice(2.5));
    }

    /** @test */
    public function it_validates_elements_percentage_correctly()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $unit = Unit::create(['name' => 'Килограмм', 'short_name' => 'кг']);

        $iron = Element::create([
            'name' => 'Железо',
            'unit_id' => $unit->id,
            'price' => 100,
            'user_id' => $user->id,
            'stock' => 1000
        ]);

        $copper = Element::create([
            'name' => 'Медь',
            'unit_id' => $unit->id,
            'price' => 500,
            'user_id' => $user->id,
            'stock' => 1000
        ]);

        $product = Product::create([
            'name' => 'Сплав',
            'type' => 'composite',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'selling_price' => 300,
            'stock' => 0
        ]);

        // Добавляем элементы с общим процентом <= 100%
        $product->elements()->attach($iron->id, ['percentage' => 60]);
        $product->elements()->attach($copper->id, ['percentage' => 40]);

        $this->assertTrue($product->validateElementsPercentage());

        // Добавляем еще один элемент, чтобы превысить 100%
        $zinc = Element::create([
            'name' => 'Цинк',
            'unit_id' => $unit->id,
            'price' => 200,
            'user_id' => $user->id,
            'stock' => 1000
        ]);
        $product->elements()->attach($zinc->id, ['percentage' => 10]);
        $product->refresh(); // Перезагружаем связи

        $this->assertFalse($product->validateElementsPercentage());
    }

    /** @test */
    public function simple_product_returns_zero_for_composite_calculation()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $unit = Unit::create(['name' => 'Килограмм', 'short_name' => 'кг']);

        $product = Product::create([
            'name' => 'Медь простая',
            'type' => 'simple',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'purchase_price' => 650,
            'selling_price' => 700,
            'stock' => 0
        ]);

        $this->assertEquals(0, $product->getCompositeProductPricePerKg());
    }

    /** @test */
    public function it_returns_correct_price_for_weight()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $unit = Unit::create(['name' => 'Килограмм', 'short_name' => 'кг']);

        $product = Product::create([
            'name' => 'Медь',
            'type' => 'simple',
            'user_id' => $user->id,
            'unit_id' => $unit->id,
            'purchase_price' => 650,
            'selling_price' => 700,
            'stock' => 0
        ]);

        // Без ценовых шкал должна возвращаться базовая цена
        $this->assertEquals(650, $product->getPriceForWeight(10, 'purchase'));
        $this->assertEquals(700, $product->getPriceForWeight(10, 'sale'));
    }
} 
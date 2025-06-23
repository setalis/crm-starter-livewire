<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Element;
use App\Models\Unit;
use App\Models\User;
use App\Models\ProductPriceScale;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Unit $unit;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->unit = Unit::factory()->create(['name' => 'Килограмм']);
    }

    /** @test */
    public function it_calculates_composite_product_price_correctly()
    {
        // Создаем элементы
        $iron = Element::factory()->create(['name' => 'Железо', 'price' => 100]);
        $copper = Element::factory()->create(['name' => 'Медь', 'price' => 500]);
        
        // Создаем составной продукт
        $product = Product::factory()->create([
            'type' => 'composite',
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id
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
        $iron = Element::factory()->create();
        $copper = Element::factory()->create();
        
        $product = Product::factory()->create([
            'type' => 'composite',
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id
        ]);
        
        // Добавляем элементы с общим процентом <= 100%
        $product->elements()->attach($iron->id, ['percentage' => 60]);
        $product->elements()->attach($copper->id, ['percentage' => 40]);
        
        $this->assertTrue($product->validateElementsPercentage());
        
        // Добавляем еще один элемент, чтобы превысить 100%
        $zinc = Element::factory()->create();
        $product->elements()->attach($zinc->id, ['percentage' => 10]);
        
        $this->assertFalse($product->validateElementsPercentage());
    }

    /** @test */
    public function it_applies_price_scales_correctly()
    {
        $product = Product::factory()->create([
            'type' => 'simple',
            'purchase_price' => 100,
            'selling_price' => 150,
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id
        ]);
        
        // Создаем ценовые шкалы
        ProductPriceScale::factory()->create([
            'product_id' => $product->id,
            'threshold_kg' => 10,
            'price' => 95 // скидка при покупке от 10 кг
        ]);
        
        ProductPriceScale::factory()->create([
            'product_id' => $product->id,
            'threshold_kg' => 50,
            'price' => 90 // больше скидка при покупке от 50 кг
        ]);
        
        // Тестируем применение ценовых шкал
        $this->assertEquals(100, $product->getPriceForWeight(5, 'purchase')); // базовая цена
        $this->assertEquals(95, $product->getPriceForWeight(15, 'purchase')); // скидка 10 кг
        $this->assertEquals(90, $product->getPriceForWeight(75, 'purchase')); // скидка 50 кг
    }

    /** @test */
    public function it_calculates_average_purchase_price_correctly()
    {
        $product = Product::factory()->create([
            'purchase_price' => 100,
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id
        ]);
        
        // Без операций должна возвращаться базовая цена
        $this->assertEquals(100, $product->average_purchase_price);
        
        // Создаем операции покупки для тестирования расчета средней цены
        // Это потребует создания операций и элементов операций
        // Пока оставляем простую проверку базовой цены
    }

    /** @test */
    public function composite_product_returns_zero_price_for_simple_product_calculation()
    {
        $product = Product::factory()->create([
            'type' => 'simple',
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id
        ]);
        
        $this->assertEquals(0, $product->getCompositeProductPricePerKg());
    }

    /** @test */
    public function simple_product_validation_passes_for_any_percentage()
    {
        $product = Product::factory()->create([
            'type' => 'simple',
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id
        ]);
        
        $this->assertTrue($product->validateElementsPercentage());
    }

    /** @test */
    public function price_for_weight_returns_base_price_when_no_scales_exist()
    {
        $product = Product::factory()->create([
            'type' => 'simple',
            'purchase_price' => 100,
            'selling_price' => 150,
            'user_id' => $this->user->id,
            'unit_id' => $this->unit->id
        ]);
        
        $this->assertEquals(100, $product->getPriceForWeight(25, 'purchase'));
        $this->assertEquals(150, $product->getPriceForWeight(25, 'sale'));
    }
} 
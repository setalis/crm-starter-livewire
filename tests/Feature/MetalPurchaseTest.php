<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Unit;
use App\Models\CashRegister;
use App\Models\Operation;
use App\Models\OperationItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Admin\Operations\OperationManager;

class MetalPurchaseTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Unit $unit;
    private CashRegister $cashRegister;
    private Product $metalProduct;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем пользователя с необходимыми правами
        $this->user = User::factory()->create();
        
        // Создаем единицы измерения
        $this->unit = Unit::factory()->create(['name' => 'Килограмм']);
        
        // Создаем кассу
        $this->cashRegister = CashRegister::factory()->create([
            'name' => 'Основная касса',
            'balance' => 100000,
            'is_active' => true
        ]);
        
        // Создаем металлический продукт
        $this->metalProduct = Product::factory()->create([
            'name' => 'Медь электролитическая',
            'type' => 'simple',
            'unit_id' => $this->unit->id,
            'purchase_price' => 650,
            'selling_price' => 700,
            'is_published' => true,
            'stock' => 0,
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function user_can_complete_full_metal_purchase_workflow()
    {
        $this->actingAs($this->user);
        
        // 1. Создание операции покупки
        $component = Livewire::test(OperationManager::class)
            ->call('addNewOperation', 'purchase');
        
        $this->assertTrue($component->get('isModal'));
        $this->assertNotNull($component->get('activeOperationId'));
        
        $operationId = $component->get('activeOperationId');
        $operation = $component->get('operations')[$operationId];
        
        $this->assertEquals('purchase', $operation['type']);
        
        // 2. Добавление продукта в корзину
        $component->call('addProductToCart', $this->metalProduct->id);
        
        $cartItems = $component->get('operations')[$operationId]['cartItems'];
        $this->assertCount(1, $cartItems);
        $this->assertEquals($this->metalProduct->id, $cartItems[0]['product_id']);
        
        // 3. Указание веса и цены
        $weight = 15.5; // кг
        $price = 650; // цена за кг
        
        $component->set("operations.{$operationId}.cartItems.0.weight", $weight)
                  ->set("operations.{$operationId}.cartItems.0.price", $price);
        
        // 4. Выбор кассы (через установку в операции)
        $component->set("operations.{$operationId}.cash_register_id", $this->cashRegister->id);
        
        // 5. Добавление комментария
        $comment = 'Покупка меди от постоянного поставщика';
        $component->set('operationComment', $comment);
        
        // Проверяем расчет итогов
        $expectedTotal = $weight * $price; // 15.5 * 650 = 10075
        
        $component->call('calculateTotals', $operationId);
        $totalAmount = $component->get('operations')[$operationId]['totalAmount'];
        $this->assertEquals($expectedTotal, $totalAmount);
        
        // 6. Завершение операции
        $initialBalance = $this->cashRegister->balance;
        $initialStock = $this->metalProduct->stock;
        
        $component->call('store');
        
        // 7. Проверка обновления остатков продукта
        $this->metalProduct->refresh();
        $this->assertEquals($initialStock + $weight, $this->metalProduct->stock);
        
        // 8. Проверка движения денег в кассе
        $this->cashRegister->refresh();
        $this->assertEquals($initialBalance - $expectedTotal, $this->cashRegister->balance);
        
        // 9. Проверка создания операции в базе данных
        $savedOperation = Operation::where('type', 'purchase')
            ->where('user_id', $this->user->id)
            ->where('total_amount', $expectedTotal)
            ->first();
        
        $this->assertNotNull($savedOperation);
        $this->assertEquals($this->cashRegister->id, $savedOperation->cash_register_id);
        
        // 10. Проверка создания элементов операции
        $operationItem = OperationItem::where('operation_id', $savedOperation->id)
            ->where('product_id', $this->metalProduct->id)
            ->first();
        
        $this->assertNotNull($operationItem);
        $this->assertEquals($weight, $operationItem->weight);
        $this->assertEquals($price, $operationItem->price);
        
        // 11. Проверка создания транзакции в кассе
        $transaction = $this->cashRegister->transactions()
            ->where('type', 'expense')
            ->where('amount', $expectedTotal)
            ->first();
        
        $this->assertNotNull($transaction);
        $this->assertEquals('Покупка (Операция #' . $savedOperation->operation_number . ')', $transaction->description);
    }

    /** @test */
    public function user_cannot_purchase_without_sufficient_cash()
    {
        $this->actingAs($this->user);
        
        // Устанавливаем недостаточный баланс в кассе
        $this->cashRegister->update(['balance' => 100]);
        
        $component = Livewire::test(OperationManager::class)
            ->call('addNewOperation', 'purchase')
            ->call('addProductToCart', $this->metalProduct->id);
        
        $operationId = $component->get('activeOperationId');
        
        // Пытаемся купить металл на сумму больше баланса кассы
        $component->set("operations.{$operationId}.cartItems.0.weight", 1)
                  ->set("operations.{$operationId}.cartItems.0.price", 1000)
                  ->set("operations.{$operationId}.cash_register_id", $this->cashRegister->id);
        
        // Операция должна быть отклонена
        $component->call('store')
                  ->assertHasErrors();
    }

    /** @test */
    public function operation_calculates_totals_correctly_for_multiple_products()
    {
        $this->actingAs($this->user);
        
        // Создаем второй продукт
        $aluminumProduct = Product::factory()->create([
            'name' => 'Алюминий вторичный',
            'type' => 'simple',
            'unit_id' => $this->unit->id,
            'purchase_price' => 120,
            'selling_price' => 150,
            'is_published' => true,
            'user_id' => $this->user->id
        ]);
        
        $component = Livewire::test(OperationManager::class)
            ->call('addNewOperation', 'purchase')
            ->call('addProductToCart', $this->metalProduct->id)
            ->call('addProductToCart', $aluminumProduct->id);
        
        $operationId = $component->get('activeOperationId');
        
        // Устанавливаем вес и цены для обоих продуктов
        $component->set("operations.{$operationId}.cartItems.0.weight", 10)  // медь
                  ->set("operations.{$operationId}.cartItems.0.price", 650)
                  ->set("operations.{$operationId}.cartItems.1.weight", 5)   // алюминий
                  ->set("operations.{$operationId}.cartItems.1.price", 120);
        
        $component->call('calculateTotals', $operationId);
        
        $expectedTotal = (10 * 650) + (5 * 120); // 6500 + 600 = 7100
        $totalAmount = $component->get('operations')[$operationId]['totalAmount'];
        
        $this->assertEquals($expectedTotal, $totalAmount);
    }

    /** @test */
    public function operation_validates_required_fields()
    {
        $this->actingAs($this->user);
        
        $component = Livewire::test(OperationManager::class)
            ->call('addNewOperation', 'purchase')
            ->call('addProductToCart', $this->metalProduct->id);
        
        $operationId = $component->get('activeOperationId');
        
        // Пытаемся сохранить операцию без указания веса
        $component->set("operations.{$operationId}.cartItems.0.weight", null)
                  ->set("operations.{$operationId}.cartItems.0.price", 650)
                  ->call('store')
                  ->assertHasErrors();
        
        // Пытаемся сохранить операцию без указания цены
        $component->set("operations.{$operationId}.cartItems.0.weight", 10)
                  ->set("operations.{$operationId}.cartItems.0.price", null)
                  ->call('store')
                  ->assertHasErrors();
    }

    /** @test */
    public function user_can_remove_items_from_cart()
    {
        $this->actingAs($this->user);
        
        $component = Livewire::test(OperationManager::class)
            ->call('addNewOperation', 'purchase')
            ->call('addProductToCart', $this->metalProduct->id);
        
        $operationId = $component->get('activeOperationId');
        $cartItems = $component->get('operations')[$operationId]['cartItems'];
        $this->assertCount(1, $cartItems);
        
        // Удаляем товар из корзины
        $component->call('removeCartItem', 0);
        
        $cartItems = $component->get('operations')[$operationId]['cartItems'];
        $this->assertCount(0, $cartItems);
    }
} 
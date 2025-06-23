<?php

namespace Tests;

use App\Models\User;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Element;
use App\Models\CashRegister;
use Illuminate\Support\Facades\Artisan;

/**
 * Вспомогательный класс для тестов
 * Содержит общие методы создания тестовых данных
 */
class TestHelper
{
    /**
     * Создает базовые данные для тестирования системы приема металла
     */
    public static function createBasicMetalReceptionData(): array
    {
        // Создаем единицы измерения
        $kilogram = Unit::factory()->create(['name' => 'Килограмм', 'short_name' => 'кг']);
        $gram = Unit::factory()->create(['name' => 'Грамм', 'short_name' => 'г']);
        
        // Создаем пользователя
        $user = User::factory()->create([
            'name' => 'Тестовый оператор',
            'email' => 'operator@test.com'
        ]);
        
        // Создаем кассу
        $cashRegister = CashRegister::factory()->create([
            'name' => 'Основная касса',
            'balance' => 100000.00,
            'is_active' => true
        ]);
        
        // Создаем химические элементы
        $iron = Element::factory()->create([
            'name' => 'Железо',
            'unit_id' => $gram->id,
            'price' => 0.1, // цена за грамм
            'user_id' => $user->id
        ]);
        
        $copper = Element::factory()->create([
            'name' => 'Медь',
            'unit_id' => $gram->id,
            'price' => 0.5,
            'user_id' => $user->id
        ]);
        
        $aluminum = Element::factory()->create([
            'name' => 'Алюминий',
            'unit_id' => $gram->id,
            'price' => 0.12,
            'user_id' => $user->id
        ]);
        
        // Создаем простые продукты (металлы)
        $copperProduct = Product::factory()->create([
            'name' => 'Медь электролитическая',
            'type' => 'simple',
            'unit_id' => $kilogram->id,
            'purchase_price' => 650.00,
            'selling_price' => 700.00,
            'clogging' => 5.0, // 5% засоренность
            'is_published' => true,
            'stock' => 0,
            'user_id' => $user->id
        ]);
        
        $aluminumProduct = Product::factory()->create([
            'name' => 'Алюминий вторичный',
            'type' => 'simple',
            'unit_id' => $kilogram->id,
            'purchase_price' => 120.00,
            'selling_price' => 150.00,
            'clogging' => 8.0,
            'is_published' => true,
            'stock' => 0,
            'user_id' => $user->id
        ]);
        
        // Создаем составной продукт (сплав)
        $brassProduct = Product::factory()->create([
            'name' => 'Латунь ЛС59-1',
            'type' => 'composite',
            'unit_id' => $kilogram->id,
            'selling_price' => 550.00,
            'is_published' => true,
            'stock' => 0,
            'user_id' => $user->id
        ]);
        
        // Добавляем элементы в составной продукт
        $brassProduct->elements()->attach($copper->id, ['percentage' => 60]); // 60% меди
        $brassProduct->elements()->attach($iron->id, ['percentage' => 39]);   // 39% железа
        $brassProduct->elements()->attach($aluminum->id, ['percentage' => 1]); // 1% алюминия
        
        return [
            'user' => $user,
            'units' => [
                'kilogram' => $kilogram,
                'gram' => $gram
            ],
            'elements' => [
                'iron' => $iron,
                'copper' => $copper,
                'aluminum' => $aluminum
            ],
            'products' => [
                'copper' => $copperProduct,
                'aluminum' => $aluminumProduct,
                'brass' => $brassProduct
            ],
            'cashRegister' => $cashRegister
        ];
    }
    
    /**
     * Создает тестовые данные для системы переучетов
     */
    public static function createRecountTestData(): array
    {
        $basicData = self::createBasicMetalReceptionData();
        
        // Добавляем остатки продуктам для тестирования переучетов
        $basicData['products']['copper']->update(['stock' => 150.5]);
        $basicData['products']['aluminum']->update(['stock' => 89.2]);
        $basicData['products']['brass']->update(['stock' => 45.8]);
        
        // Добавляем остатки элементам
        $basicData['elements']['iron']->update(['stock' => 5000]);
        $basicData['elements']['copper']->update(['stock' => 3200]);
        $basicData['elements']['aluminum']->update(['stock' => 1800]);
        
        return $basicData;
    }
    
    /**
     * Очищает тестовые данные после выполнения тестов
     */
    public static function cleanupTestData(): void
    {
        // В Laravel с RefreshDatabase это обычно не требуется,
        // но может быть полезно для интеграционных тестов
        Artisan::call('migrate:fresh');
    }
    
    /**
     * Генерирует случайные данные для нагрузочного тестирования
     */
    public static function generateLoadTestData(int $operationsCount = 100): array
    {
        $basicData = self::createBasicMetalReceptionData();
        $operations = [];
        
        for ($i = 0; $i < $operationsCount; $i++) {
            $operations[] = [
                'type' => fake()->randomElement(['purchase', 'sale']),
                'product_id' => fake()->randomElement(array_values($basicData['products']))->id,
                'weight' => fake()->randomFloat(2, 1, 100),
                'price' => fake()->randomFloat(2, 50, 1000),
                'user_id' => $basicData['user']->id,
                'cash_register_id' => $basicData['cashRegister']->id
            ];
        }
        
        return [
            'basic_data' => $basicData,
            'operations' => $operations
        ];
    }
    
    /**
     * Проверяет целостность данных после операций
     */
    public static function validateDataIntegrity(): array
    {
        $issues = [];
        
        // Проверяем отрицательные остатки продуктов
        $negativeStockProducts = Product::where('stock', '<', 0)->get();
        if ($negativeStockProducts->count() > 0) {
            $issues[] = "Найдены продукты с отрицательными остатками: " . 
                       $negativeStockProducts->pluck('name')->implode(', ');
        }
        
        // Проверяем отрицательные остатки элементов
        $negativeStockElements = Element::where('stock', '<', 0)->get();
        if ($negativeStockElements->count() > 0) {
            $issues[] = "Найдены элементы с отрицательными остатками: " . 
                       $negativeStockElements->pluck('name')->implode(', ');
        }
        
        // Проверяем составные продукты с некорректными процентами
        $compositeProducts = Product::where('type', 'composite')->get();
        foreach ($compositeProducts as $product) {
            if (!$product->validateElementsPercentage()) {
                $issues[] = "Продукт '{$product->name}' имеет некорректное процентное содержание элементов";
            }
        }
        
        return $issues;
    }
    
    /**
     * Создает данные для тестирования прав доступа
     */
    public static function createRoleTestData(): array
    {
        $admin = User::factory()->create(['name' => 'Администратор']);
        $manager = User::factory()->create(['name' => 'Менеджер']);
        $operator = User::factory()->create(['name' => 'Оператор']);
        
        // Здесь можно добавить назначение ролей через Spatie Permission
        // если они используются в приложении
        
        return [
            'admin' => $admin,
            'manager' => $manager,
            'operator' => $operator
        ];
    }
}

/**
 * Константы для тестирования
 */
class TestConstants
{
    // Типовые цены за килограмм (в рублях)
    const COPPER_PRICE = 650.00;
    const ALUMINUM_PRICE = 120.00;
    const IRON_PRICE = 45.00;
    const BRASS_PRICE = 550.00;
    
    // Типовые проценты засоренности
    const LOW_CLOGGING = 2.0;
    const MEDIUM_CLOGGING = 5.0;
    const HIGH_CLOGGING = 10.0;
    
    // Минимальные и максимальные веса для тестирования
    const MIN_WEIGHT = 0.1;
    const MAX_WEIGHT = 1000.0;
    
    // Баланс кассы по умолчанию для тестов
    const DEFAULT_CASH_BALANCE = 100000.00;
    
    // Тестовые email адреса
    const TEST_ADMIN_EMAIL = 'admin@metalreception.test';
    const TEST_MANAGER_EMAIL = 'manager@metalreception.test';
    const TEST_OPERATOR_EMAIL = 'operator@metalreception.test';
} 
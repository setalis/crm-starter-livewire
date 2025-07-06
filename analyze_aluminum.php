<?php

require_once 'vendor/autoload.php';

use App\Models\Product;
use App\Models\Operation;
use App\Models\OperationItem;

// Загружаем Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Анализ операций с алюминием ===\n";

// Находим продукт алюминий (с двумя л)
$aluminum = Product::where('name', 'like', '%ллюмин%')->first();
if (!$aluminum) {
    echo "Алюминий не найден\n";
    return;
}

echo "Найден алюминий: ID {$aluminum->id}, Название: {$aluminum->name}\n\n";

// Получаем все операции с алюминием
$operations = Operation::whereHas('items', function($q) use ($aluminum) {
    $q->where('product_id', $aluminum->id);
})->with(['items' => function($q) use ($aluminum) {
    $q->where('product_id', $aluminum->id);
}])->orderBy('created_at')->get();

echo "Операций с алюминием: {$operations->count()}\n\n";

$totalPurchaseAmount = 0;
$totalPurchaseWeight = 0;
$purchaseCount = 0;

foreach($operations as $operation) {
    echo "Операция ID: {$operation->id}, Тип: {$operation->type}, Дата: {$operation->created_at->format('Y-m-d')}\n";
    
    foreach($operation->items as $item) {
        echo "  - Вес: {$item->weight} кг\n";
        echo "  - Цена (общая): {$item->price}\n";
        echo "  - Цена за кг: " . ($item->weight > 0 ? round($item->price / $item->weight, 2) : 0) . "\n";
        echo "  - Засор: {$item->clogging}%\n";
        
        if ($operation->type === 'purchase') {
            $totalPurchaseAmount += $item->price;
            $totalPurchaseWeight += $item->weight;
            $purchaseCount++;
        }
    }
    echo "\n";
}

if ($totalPurchaseWeight > 0) {
    $avgPrice = $totalPurchaseAmount / $totalPurchaseWeight;
    echo "=== ИТОГИ ПОКУПОК ===\n";
    echo "Операций покупки: $purchaseCount\n";
    echo "Общий вес: $totalPurchaseWeight кг\n";
    echo "Общая сумма: $totalPurchaseAmount\n";
    echo "Средняя цена за кг: " . round($avgPrice, 2) . "\n";
} 
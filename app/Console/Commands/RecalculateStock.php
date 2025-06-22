<?php

namespace App\Console\Commands;

use App\Models\Element;
use App\Models\Operation;
use App\Models\Product;
use Illuminate\Console\Command;

class RecalculateStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:recalculate {--confirm : Подтвердить пересчет без запроса}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Пересчитывает остатки склада на основе всех операций с правильной логикой засора';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('confirm')) {
            if (!$this->confirm('Это действие сбросит все остатки на складе и пересчитает их заново. Продолжить?')) {
                $this->info('Операция отменена.');
                return;
            }
        }

        $this->info('Начинаем пересчет остатков склада...');

        // Сбрасываем все остатки до нуля
        $this->info('Сброс остатков товаров...');
        Product::query()->update(['stock' => 0]);
        
        $this->info('Сброс остатков элементов...');
        Element::query()->update(['stock' => 0]);

        // Получаем все операции (покупки, продажи, но не конвертации)
        $operations = Operation::with(['items.product', 'items.elements.element'])
            ->whereIn('type', ['purchase', 'sale'])
            ->orderBy('created_at')
            ->get();

        $this->info("Найдено операций для пересчета: {$operations->count()}");

        $progressBar = $this->output->createProgressBar($operations->count());
        $progressBar->start();

        foreach ($operations as $operation) {
            $this->processOperation($operation);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        // Показываем статистику
        $totalProducts = Product::where('stock', '>', 0)->count();
        $totalElements = Element::where('stock', '>', 0)->count();
        
        $this->info("Пересчет завершен!");
        $this->info("Товаров с остатками: {$totalProducts}");
        $this->info("Элементов с остатками: {$totalElements}");
    }

    private function processOperation(Operation $operation)
    {
        $multiplier = $operation->type === 'purchase' ? 1 : -1;

        foreach ($operation->items as $item) {
            $product = $item->product;
            if (!$product) continue;

            $weight = (float)$item->weight;
            $clogging = (float)($item->clogging ?? 0);

            // Рассчитываем чистый вес (для простых продуктов учитываем засор)
            $effectiveWeight = $weight;
            if ($product->type === 'simple') {
                $effectiveWeight = $weight - ($weight * $clogging / 100);
            }

            // Обновляем остаток товара (ЧИСТЫЙ вес)
            $product->increment('stock', $effectiveWeight * $multiplier);

            // Для составных продуктов также обновляем остатки элементов
            if ($product->type === 'composite') {
                foreach ($item->elements as $operationItemElement) {
                    $element = Element::find($operationItemElement->element_id);
                    if ($element) {
                        $elementWeight = $weight * ((float)$operationItemElement->percentage / 100);
                        $element->increment('stock', $elementWeight * $multiplier);
                    }
                }
            }
        }
    }
}

<?php

namespace App\Models;

use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    use HasFactory, HasComments;

    protected $fillable = [
        'operation_id',
        'source_product_id',
        'target_product_id',
        'source_quantity',
        'target_quantity',
        'conversion_type',
        'notes',
    ];

    protected $casts = [
        'source_quantity' => 'decimal:3',
        'target_quantity' => 'decimal:3',
    ];

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function sourceProduct()
    {
        return $this->belongsTo(Product::class, 'source_product_id');
    }

    public function targetProduct()
    {
        return $this->belongsTo(Product::class, 'target_product_id');
    }

    public function elements()
    {
        return $this->hasMany(ConversionElement::class);
    }

    /**
     * Выполняет конвертацию продукта
     */
    public function execute()
    {
        // Проверяем наличие достаточного количества исходного продукта
        if ($this->sourceProduct->stock < $this->source_quantity) {
            throw new \Exception('Недостаточно запасов исходного продукта для конвертации');
        }

        // Уменьшаем запасы исходного продукта
        $this->sourceProduct->decrement('stock', $this->source_quantity);

        // Увеличиваем запасы целевого продукта
        $this->targetProduct->increment('stock', $this->target_quantity);

        // Если целевой продукт составной, добавляем элементы
        if ($this->targetProduct->type === 'composite') {
            foreach ($this->elements as $conversionElement) {
                $conversionElement->element->increment('stock', $conversionElement->quantity);
            }
        }
    }

    /**
     * Отменяет конвертацию (обратная операция)
     */
    public function reverse()
    {
        // Проверяем наличие достаточного количества целевого продукта
        if ($this->targetProduct->stock < $this->target_quantity) {
            throw new \Exception('Недостаточно запасов целевого продукта для отмены конвертации');
        }

        // Если целевой продукт составной, проверяем наличие элементов для отмены
        if ($this->targetProduct->type === 'composite') {
            foreach ($this->elements as $conversionElement) {
                if ($conversionElement->element->stock < $conversionElement->quantity) {
                    throw new \Exception("Недостаточно запасов элемента {$conversionElement->element->name} для отмены конвертации");
                }
            }
        }

        // Увеличиваем запасы исходного продукта
        $this->sourceProduct->increment('stock', $this->source_quantity);

        // Уменьшаем запасы целевого продукта
        $this->targetProduct->decrement('stock', $this->target_quantity);

        // Если целевой продукт составной, убираем элементы
        if ($this->targetProduct->type === 'composite') {
            foreach ($this->elements as $conversionElement) {
                $conversionElement->element->decrement('stock', $conversionElement->quantity);
            }
        }
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'type',
        'unit_id',
        'purchase_price',
        'selling_price',
        'clogging',
        'image',
        'is_published',
        'position',
        'stock'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function priceScales()
    {
        return $this->hasMany(ProductPriceScale::class);
    }

    public function elements()
    {
        return $this->belongsToMany(Element::class)->withPivot('percentage');
    }

    public function operationItems()
    {
        return $this->hasMany(OperationItem::class);
    }

    public function sourceConversions()
    {
        return $this->hasMany(Conversion::class, 'source_product_id');
    }

    public function targetConversions()
    {
        return $this->hasMany(Conversion::class, 'target_product_id');
    }

    public function getAveragePurchasePriceAttribute()
    {
        $purchaseItems = $this->operationItems()
            ->whereHas('operation', function ($query) {
                $query->where('type', 'purchase');
            })
            ->get();

        if ($purchaseItems->isEmpty()) {
            return $this->purchase_price ?? 0;
        }

        $totalAmount = $purchaseItems->sum('price');
        $totalWeight = $purchaseItems->sum('weight');

        return $totalWeight > 0 ? $totalAmount / $totalWeight : $this->purchase_price ?? 0;
    }

    /**
     * Рассчитывает стоимость составного продукта за 1 кг на основе элементов
     */
    public function getCompositeProductPricePerKg()
    {
        if ($this->type !== 'composite') {
            return 0;
        }

        $totalPrice = 0;
        
        foreach ($this->elements as $element) {
            // Стоимость элемента = цена за 1% * процентное содержание
            $elementPrice = $element->price * $element->pivot->percentage;
            $totalPrice += $elementPrice;
        }

        return $totalPrice;
    }

    /**
     * Рассчитывает общую стоимость составного продукта для указанного веса
     */
    public function getCompositeProductTotalPrice($weight)
    {
        return $this->getCompositeProductPricePerKg() * $weight;
    }

    /**
     * Проверяет корректность процентного содержания элементов (должно быть <= 100%)
     */
    public function validateElementsPercentage()
    {
        if ($this->type !== 'composite') {
            return true;
        }

        $totalPercentage = $this->elements->sum('pivot.percentage');
        return $totalPercentage <= 100;
    }
}

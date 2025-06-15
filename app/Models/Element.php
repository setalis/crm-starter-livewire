<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Element extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_id',
        'price',
        'stock',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('percentage');
    }

    public function operationItemElements()
    {
        return $this->hasMany(OperationItemElement::class);
    }

    public function getAveragePurchasePriceAttribute()
    {
        $purchaseElements = $this->operationItemElements()
            ->whereHas('item.operation', function ($query) {
                $query->where('type', 'purchase');
            })
            ->with('item')
            ->get();

        if ($purchaseElements->isEmpty()) {
            return $this->price ?? 0;
        }

        $totalAmount = 0;
        $totalPercentage = 0;

        foreach ($purchaseElements as $operationElement) {
            $item = $operationElement->item;
            // Цена за единицу веса с учетом процентного содержания элемента
            $pricePerKg = $item->price * ($operationElement->percentage / 100);
            // Общая стоимость элемента в этой операции
            $elementTotalPrice = $pricePerKg * $item->weight;
            
            $totalAmount += $elementTotalPrice;
            $totalPercentage += $operationElement->percentage * $item->weight;
        }

        // Возвращаем среднюю стоимость за 1% содержания
        return $totalPercentage > 0 ? $totalAmount / $totalPercentage : $this->price ?? 0;
    }
}

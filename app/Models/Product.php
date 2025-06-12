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

        $totalAmount = $purchaseItems->sum(function ($item) {
            return $item->price * $item->weight;
        });

        $totalWeight = $purchaseItems->sum('weight');

        return $totalWeight > 0 ? $totalAmount / $totalWeight : $this->purchase_price ?? 0;
    }
}

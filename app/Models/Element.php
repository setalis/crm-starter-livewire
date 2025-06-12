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
        $totalWeight = 0;

        foreach ($purchaseElements as $operationElement) {
            $item = $operationElement->item;
            $elementWeight = $item->weight * ($operationElement->percentage / 100);
            $elementPrice = $item->price * ($operationElement->percentage / 100);
            
            $totalAmount += $elementPrice * $elementWeight;
            $totalWeight += $elementWeight;
        }

        return $totalWeight > 0 ? $totalAmount / $totalWeight : $this->price ?? 0;
    }
}

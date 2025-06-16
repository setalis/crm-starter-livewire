<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    protected $fillable = [
        'shipment_id',
        'product_id',
        'weight',
        'writeoff_type',
        'stock_after',
        'actual_weight',
        'actual_price',
        'actual_clogging',
        'expected_stock_before',
        'actual_stock_before',
        'stock_discrepancy',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

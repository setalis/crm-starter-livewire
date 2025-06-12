<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'car_number',
        'driver_name',
        'company',
        'comment',
        'stage',
        'actual_weight',
        'actual_price',
        'actual_clogging',
        'shipping_cost',
    ];

    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }
}

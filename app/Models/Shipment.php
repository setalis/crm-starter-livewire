<?php

namespace App\Models;

use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasComments;

    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

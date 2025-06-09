<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

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
        return $this->belongsToMany(Element::class);
    }
}

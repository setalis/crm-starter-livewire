<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'product_id',
        'weight',
        'clogging',
        'price',
    ];

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function elements()
    {
        return $this->hasMany(OperationItemElement::class);
    }
}

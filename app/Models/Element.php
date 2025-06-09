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
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}

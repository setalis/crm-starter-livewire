<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversion_id',
        'element_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
    ];

    public function conversion()
    {
        return $this->belongsTo(Conversion::class);
    }

    public function element()
    {
        return $this->belongsTo(Element::class);
    }
} 
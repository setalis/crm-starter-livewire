<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecountItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'recount_id',
        'countable_type',
        'countable_id',
        'expected_quantity',
        'actual_quantity',
        'discrepancy',
        'unit_price',
        'discrepancy_amount',
        'notes',
    ];

    protected $casts = [
        'expected_quantity' => 'decimal:3',
        'actual_quantity' => 'decimal:3',
        'discrepancy' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'discrepancy_amount' => 'decimal:2',
    ];

    public function recount()
    {
        return $this->belongsTo(Recount::class);
    }

    public function countable()
    {
        return $this->morphTo();
    }

    public function calculateDiscrepancy()
    {
        if ($this->actual_quantity === null) {
            return;
        }

        $this->discrepancy = $this->actual_quantity - $this->expected_quantity;
        $this->discrepancy_amount = $this->discrepancy * $this->unit_price;
        $this->save();
    }

    public function getDiscrepancyTypeAttribute()
    {
        if ($this->discrepancy > 0) {
            return 'surplus'; // Излишек
        } elseif ($this->discrepancy < 0) {
            return 'shortage'; // Недостача
        }
        return 'match'; // Соответствие
    }

    public function getDiscrepancyTextAttribute()
    {
        return match($this->discrepancy_type) {
            'surplus' => 'Излишек',
            'shortage' => 'Недостача',
            'match' => 'Соответствует',
            default => 'Неизвестно'
        };
    }

    public function getAbsDiscrepancyAttribute()
    {
        return abs($this->discrepancy);
    }

    public function getAbsDiscrepancyAmountAttribute()
    {
        return abs($this->discrepancy_amount);
    }
} 
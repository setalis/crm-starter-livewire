<?php

namespace App\Models;

use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Recount extends Model
{
    use HasFactory, HasComments;

    protected $fillable = [
        'number',
        'type',
        'status',
        'user_id',
        'reason',
        'notes',
        'started_at',
        'completed_at',
        'total_discrepancy_amount',
    ];

    protected $attributes = [
        'status' => 'pending',
        'total_discrepancy_amount' => 0,
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_discrepancy_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(RecountItem::class);
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'В ожидании',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен',
            default => 'Неизвестен'
        };
    }

    public function getTypeTextAttribute()
    {
        return match($this->type) {
            'products' => 'Продукты',
            'elements' => 'Элементы',
            default => 'Неизвестен'
        };
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at) {
            return null;
        }

        $end = $this->completed_at ?? now();
        return $this->started_at->diffForHumans($end, true);
    }

    public function start()
    {
        $this->update([
            'status' => 'pending',
            'started_at' => now(),
        ]);
    }

    public function complete()
    {
        // Пересчитываем общую сумму расхождений
        $totalDiscrepancy = $this->items->sum('discrepancy_amount');
        
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'total_discrepancy_amount' => $totalDiscrepancy,
        ]);

        // Обновляем остатки
        $this->updateStocks();
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'completed_at' => now(),
        ]);
    }

    protected function updateStocks()
    {
        if ($this->status !== 'completed') {
            return;
        }

        foreach ($this->items as $item) {
            if ($item->discrepancy != 0) {
                $countable = $item->countable;
                $countable->update([
                    'stock' => $item->actual_quantity
                ]);
            }
        }
    }

    public static function generateNumber()
    {
        $lastRecount = static::latest('id')->first();
        $nextId = $lastRecount ? $lastRecount->id + 1 : 1;
        
        return 'REC-' . date('Y') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
} 
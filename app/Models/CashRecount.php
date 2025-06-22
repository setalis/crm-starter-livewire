<?php

namespace App\Models;

use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRecount extends Model
{
    use HasFactory, HasComments;

    protected $fillable = [
        'number',
        'cash_register_id',
        'user_id',
        'status',
        'expected_balance',
        'actual_balance',
        'discrepancy',
        'reason',
        'notes',
        'started_at',
        'completed_at',
    ];

    protected $attributes = [
        'status' => 'pending',
        'discrepancy' => 0,
    ];

    protected $casts = [
        'expected_balance' => 'decimal:2',
        'actual_balance' => 'decimal:2',
        'discrepancy' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function getDurationAttribute()
    {
        if (!$this->started_at) {
            return null;
        }

        $end = $this->completed_at ?? now();
        return $this->started_at->diffForHumans($end, true);
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

    public function start()
    {
        $this->update([
            'status' => 'pending',
            'started_at' => now(),
            'expected_balance' => $this->cashRegister->balance,
        ]);
    }

    public function complete()
    {
        if ($this->actual_balance === null) {
            throw new \Exception('Не указан фактический баланс');
        }

        $this->discrepancy = $this->actual_balance - $this->expected_balance;
        
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Корректируем баланс кассы
        $this->adjustCashRegister();
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'completed_at' => now(),
        ]);
    }

    protected function adjustCashRegister()
    {
        if ($this->status !== 'completed' || $this->discrepancy == 0) {
            return;
        }

        $register = $this->cashRegister;
        
        if ($this->discrepancy > 0) {
            // Излишек - добавляем к балансу
            $register->addMoney(
                $this->discrepancy,
                "Корректировка по переучету #{$this->number} - излишек",
                $this->user_id
            );
        } else {
            // Недостача - списываем с баланса
            $register->withdrawMoney(
                abs($this->discrepancy),
                "Корректировка по переучету #{$this->number} - недостача",
                $this->user_id
            );
        }
    }

    public static function generateNumber()
    {
        $lastRecount = static::latest('id')->first();
        $nextId = $lastRecount ? $lastRecount->id + 1 : 1;
        
        return 'CRC-' . date('Y') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
} 
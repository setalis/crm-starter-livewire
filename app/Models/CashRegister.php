<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    public function addMoney($amount, $description = null, $user_id = null)
    {
        $this->increment('balance', $amount);
        $this->refresh(); // Обновляем модель чтобы получить актуальный баланс
        
        return $this->transactions()->create([
            'type' => 'income',
            'amount' => $amount,
            'description' => $description,
            'user_id' => $user_id,
            'balance_after' => $this->balance,
        ]);
    }

    public function withdrawMoney($amount, $description = null, $user_id = null)
    {
        if ($this->balance < $amount) {
            throw new \Exception('Недостаточно средств в кассе');
        }

        $this->decrement('balance', $amount);
        $this->refresh(); // Обновляем модель чтобы получить актуальный баланс
        
        return $this->transactions()->create([
            'type' => 'expense',
            'amount' => $amount,
            'description' => $description,
            'user_id' => $user_id,
            'balance_after' => $this->balance,
        ]);
    }
} 
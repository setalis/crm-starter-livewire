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

    public function cashRecounts()
    {
        return $this->hasMany(CashRecount::class);
    }
} 
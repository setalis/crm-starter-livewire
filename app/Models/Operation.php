<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_number',
        'user_id',
        'type',
        'total_amount',
        'cash_register_id',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OperationItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function conversion()
    {
        return $this->hasOne(Conversion::class);
    }
}

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
    ];

    public function items()
    {
        return $this->hasMany(OperationItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

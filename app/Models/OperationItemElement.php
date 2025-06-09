<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationItemElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_item_id',
        'element_id',
        'percentage',
    ];

    public function item()
    {
        return $this->belongsTo(OperationItem::class, 'operation_item_id');
    }

    public function element()
    {
        return $this->belongsTo(Element::class);
    }
}

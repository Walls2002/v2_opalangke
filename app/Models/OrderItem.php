<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'unit_price',
        'quantity',
    ];

    /**
     * Get the order who owns the order line item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

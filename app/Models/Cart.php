<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'product_id',
        'quantity',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get store who owns the product.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * The added product.
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'store_id',
        'name',
        'price',
        'quantity',
        'measurement',
        'image'
    ];

    public function getAverageRatings(): string
    {
        $orderItems = $this->orderItems()->whereHas('orderItemReview')->with('orderItemReview')->get();

        if ($orderItems->isEmpty()) {
            return '0';
        }

        $totalStars = 0;
        $reviewCount = 0;

        foreach ($orderItems as $orderItem) {
            if ($orderItem->orderItemReview) {
                $totalStars += $orderItem->orderItemReview->stars;
                $reviewCount++;
            }
        }

        if ($reviewCount === 0) {
            return '0';
        }

        $averageRating = $totalStars / $reviewCount;

        return number_format($averageRating, 2);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
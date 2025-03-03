<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Rider extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['rating'];

    protected $fillable = [
        'user_id',
        'license_number',
        'plate_number',
    ];

    public function rating(): Attribute
    {
        return new Attribute(
            get: function () {
                $reviews = $this->reviews()->get();

                if ($reviews->isEmpty()) {
                    return '0';
                }

                $totalStars = 0;
                $reviewCount = 0;

                foreach ($reviews as $review) {
                    $totalStars += $review->stars;
                    $reviewCount++;
                }

                if ($reviewCount === 0) {
                    return '0';
                }

                $averageRating = $totalStars / $reviewCount;

                return number_format($averageRating, 2);
            }
        );
    }

    /**
     * The user record of this rider.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(ReviewRider::class);
    }
}

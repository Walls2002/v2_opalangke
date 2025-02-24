<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'province',
        'city',
        'city_code',
        'barangay',
        'barangay_code',
        'shipping_fee',
    ];
}

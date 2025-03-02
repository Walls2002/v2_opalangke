<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'store_id',
    ];

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rider extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'vendor_id',
        'name',
        'contact_number',
        'license_number',
        'plate_number',
        'email',
        'password',
    ];

    /**
     * Hash the password automatically when setting it.
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Relationship with vendor (user table).
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}

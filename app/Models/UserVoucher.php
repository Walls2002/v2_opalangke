<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voucher_id',
        'used_at',
        'expired_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_percent' => 'boolean',
        'is_deleted' => 'boolean',
    ];


    /**
     * Get the voucher the used.
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'expired_at' => $this->expired_at,
            'expired_at_readable' => $this->expired_at->diffForHumans(),
            'used_at' => $this->used_at,
            'min_order_price' => $this->voucher->min_order_price,
            'value' => $this->voucher->value,
            'description' => $this->voucher->description,
            'is_percent' => $this->voucher->is_percent,
        ];
    }
}
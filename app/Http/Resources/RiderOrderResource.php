<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderOrderResource extends JsonResource
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
            'status' => [
                'id' => $this->status,
                'name' => $this->status->getLabel(),
            ],
            'rider_team_only' => $this->rider_team_only,
            'total_price' => $this->total_price,
            'final_price' => $this->final_price,
            'address' => $this->address,
            'note' => $this->note,
            'delivery_image' => $this->delivery_image,
            'delivered_at' => $this->delivered_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => $this->user,
            'store' => $this->store,
            'items' => $this->items,
            'voucher' => $this->userVoucher,
        ];
    }
}
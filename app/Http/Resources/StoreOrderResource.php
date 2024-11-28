<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderResource extends JsonResource
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
            'total_price' => $this->total_price,
            'address' => $this->address,
            'note' => $this->note,
            'delivery_image' => $this->delivery_image,
            'delivered_at' => $this->delivered_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => $this->user,
            'rider' => $this->rider,
            'items' => $this->items,
        ];
    }
}

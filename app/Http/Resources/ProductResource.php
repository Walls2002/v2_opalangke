<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category' => $this->category,
            'store' => $this->store,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'measurement' => $this->measurement,
            'image' => $this->image,
            'average_rating' => $this->getAverageRatings(),
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPrice = 0;

        switch ($this->measurement) {
            case '1/4 kilo':
                $totalPrice = ($this->unit_price * 0.25) * $this->quantity;
                break;
            case '1/2 kilo':
                $totalPrice = ($this->unit_price * 0.50) * $this->quantity;
                break;
            case '1 kilo':
                $totalPrice = $this->unit_price * $this->quantity;
                break;
            case 'piece':
            default:
                $totalPrice = $this->unit_price * $this->quantity;
                break;
        }


        return [
            'id' => $this->id,
            'name' => $this->name,
            'unit_price' => $this->unit_price,
            'quantity' => $this->quantity,
            'measurement' => $this->measurement,
            'total_cost' => Number::format($totalPrice, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

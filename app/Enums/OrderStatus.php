<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 1;
    case CONFIRMED = 2;
    case DISPATCHED = 3;
    case ASSIGNED = 4;
    case DELIVERED = 5;
    case CANCELED = 6;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::CONFIRMED => 'confirmed',
            self::DISPATCHED => 'dispatched',
            self::ASSIGNED => 'assigned',
            self::DELIVERED => 'delivered',
            self::CANCELED => 'canceled',
        };
    }
}
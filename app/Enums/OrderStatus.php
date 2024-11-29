<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 1;
    case CONFIRMED = 2;
    case ASSIGNED = 3;
    case DELIVERED = 4;
    case CANCELED = 5;

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::CONFIRMED => 'confirmed',
            self::ASSIGNED => 'assigned',
            self::DELIVERED => 'delivered',
            self::CANCELED => 'canceled',
        };
    }
}

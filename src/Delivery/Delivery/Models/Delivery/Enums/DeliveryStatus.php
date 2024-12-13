<?php

namespace Delivery\Models\Delivery\Enums;

enum DeliveryStatus: int
{
    case OUT_FOR_DELIVERY = 0;
    case PARTIALLY_COMPLETED = 1;
    case COMPLETED = 2;
    case CANCELLED = 3;

    public function displayName(): string
    {
        return match ($this) {
            self::OUT_FOR_DELIVERY => 'Out for delivery',
            self::PARTIALLY_COMPLETED => 'Partially Completed',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}

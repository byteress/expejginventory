<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ItemConfirmedForDelivery extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $productId,
        public string $reservationId,
        public int $quantity
    )
    {
    }
}

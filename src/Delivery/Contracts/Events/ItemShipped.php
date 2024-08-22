<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ItemShipped extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $productId,
        public int $quantity
    )
    {
    }
}

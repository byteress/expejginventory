<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ItemDelivered extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $productId,
        public int $success,
        public int $failure
    )
    {
    }
}

<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PickupOrderPlaced extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public array $items,
        public string $branchId
    )
    {
    }
}

<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DeliveryOrderPlaced extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public array $items,
        public int $deliveryFee,
        public string $address,
        public string $branchId
    )
    {
    }
}

<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DeliveryPartiallyCompleted extends ShouldBeStored
{
    public function __construct(
        public string $deliveryId,
        public string $branchId
    )
    {
    }
}

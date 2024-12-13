<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DeliveryAssigned extends ShouldBeStored
{
    public function __construct(
        public string $deliveryId,
        public string $driver,
        public string $truck,
        public string $branchId,
        public array $items,
        public ?string $notes
    )
    {
    }
}

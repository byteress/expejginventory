<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderShipped extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $shippingId,
        public string $driver,
        public string $truck,
        public ?string $notes = null
    )
    {
    }
}

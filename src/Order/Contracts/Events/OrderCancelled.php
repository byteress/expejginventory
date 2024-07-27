<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderCancelled extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $actor
    )
    {
    }
}

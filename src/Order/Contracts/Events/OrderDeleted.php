<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderDeleted extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $actor,
        public ?string $notes = null
    )
    {
    }
}

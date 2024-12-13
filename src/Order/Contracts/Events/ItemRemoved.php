<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ItemRemoved extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $productId,
        public bool $authorizationRequired
    )
    {
        
    }
}
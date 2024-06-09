<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ItemPriceUpdated extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $productId,
        public int $newPrice,
        public bool $itemAuthRequred,
        public bool $orderAuthRequired
    )
    {
        
    }
}
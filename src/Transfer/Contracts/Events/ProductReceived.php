<?php

namespace TransferContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ProductReceived extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public int $quantity,
        public string $receiver,
        public string $actor
    )
    {
        
    }
}
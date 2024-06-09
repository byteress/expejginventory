<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ItemAdded extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $productId,
        public string $title,
        public int $price,
        public int $quantity,
        public string $reservationId
    )
    {
        
    }
}
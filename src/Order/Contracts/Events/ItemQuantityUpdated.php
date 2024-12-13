<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ItemQuantityUpdated extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $productId,
        public int $newQuantity,
        public string $reservationId
    )
    {
        
    }
}
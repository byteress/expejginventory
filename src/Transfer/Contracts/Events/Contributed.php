<?php

namespace TransferContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class Contributed extends ShouldBeStored
{
    public function __construct(
        public string $transferId,
        public string $productId,
        public int $quantity,
        public string $reservationId,
        public string $branchId,
        public string $actor
    )
    {
        
    }
}
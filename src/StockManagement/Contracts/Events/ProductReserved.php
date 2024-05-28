<?php

namespace StockManagementContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ProductReserved extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public string $reservationId,
        public string $branchId,
        public int $quantity,
        public string $actor
    )
    {
        
    }
}
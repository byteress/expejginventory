<?php

namespace StockManagementContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DamagedProductReceived extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public string $branchId,
        public int $quantity,
        public string $actor
    )
    {

    }
}

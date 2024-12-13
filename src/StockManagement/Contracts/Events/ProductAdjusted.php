<?php

namespace StockManagementContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ProductAdjusted extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public string $branchId,
        public ?int    $available,
        public ?int $damaged,
        public string $actor
    )
    {
    }
}

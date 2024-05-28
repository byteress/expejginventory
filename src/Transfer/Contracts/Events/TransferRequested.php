<?php

namespace TransferContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TransferRequested extends ShouldBeStored
{
    /**
     * @param array<string, int> $products
     */
    public function __construct(
        public string $transferId,
        public array $products,
        public string $branchId,
        public string $actor
    )
    {
        
    }
}
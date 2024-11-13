<?php

namespace TransferContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ProductReceived extends ShouldBeStored
{
    public function __construct(
        public string $transferId,
        public string $productId,
        public int $received,
        public int $damaged,
        public string $actor,
        public int $lacking = 0
    )
    {

    }
}

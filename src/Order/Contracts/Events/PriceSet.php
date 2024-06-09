<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PriceSet extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public int $regularPrice,
        public int $salePrice
    )
    {
        
    }
}
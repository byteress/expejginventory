<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderPlaced extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $customerId,
        public string $assistantId,
        public string $branchId,
        public array $items,
        public string $orderType = 'regular',
        public ?string $cancelledOrder = null
    )
    {

    }
}

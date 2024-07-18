<?php

namespace OrderContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderDelivered extends ShouldBeStored
{
    public function __construct(public string $orderId)
    {
    }
}

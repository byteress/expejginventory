<?php

namespace OrderContracts\Events;

use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderSetAsPrevious extends ShouldBeStored
{
    public function __construct(public string $orderId, public ?DateTime $installmentStartDate)
    {
    }
}

<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PenaltyApplied extends ShouldBeStored
{
    public function __construct(
        public string $installmentId,
        public int $index,
        public string $customerId,
        public string $orderId,
        public int $amount,
        public string $actor
    )
    {

    }
}

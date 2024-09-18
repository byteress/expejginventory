<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CodReceived extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $customerId,
        public int $amount,
        public string $paymentType
    )
    {
    }
}

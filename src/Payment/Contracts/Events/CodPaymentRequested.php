<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CodPaymentRequested extends ShouldBeStored
{
    public function __construct(
        public int $amount,
        public string $orderId,
        public string $customerId,
        public string $cashier,
        public ?string $transactionId = null,
        public ?string $orNumber = null,
    )
    {

    }
}

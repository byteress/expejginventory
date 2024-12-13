<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class InstallmentStarted extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public string $customerId,
        public array $dues,
        public int $codBalance
    )
    {
    }
}

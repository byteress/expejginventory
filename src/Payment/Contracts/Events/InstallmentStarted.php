<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class InstallmentStarted extends ShouldBeStored
{
    public function __construct(
        public string $orderId,
        public array $dues
    )
    {
    }
}

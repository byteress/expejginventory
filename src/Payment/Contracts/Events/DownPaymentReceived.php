<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DownPaymentReceived extends ShouldBeStored
{
    public function __construct(
        public string $transactionId,
        public string $orderId,
        public string $customerId,
        public array $paymentMethods,
        public string $orNumber,
        public string $cashier
    )
    {}
}

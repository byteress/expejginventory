<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class InstallmentPaymentReceived extends ShouldBeStored
{
    public function __construct(
        public string $transactionId,
        public string $customerId,
        public array $paymentMethods,
        public string $orNumber,
        public string $cashier,
        public int $amount,
        public string $orderId,
        public array $installmentIds = []
    )
    {
    }
}

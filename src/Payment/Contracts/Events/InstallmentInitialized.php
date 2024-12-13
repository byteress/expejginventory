<?php

namespace PaymentContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class InstallmentInitialized extends ShouldBeStored
{
    public function __construct(
        public string $installmentId,
        public int $amount,
        public int $months,
        public float $interestRate,
        public array $installments,
        public string $orderId,
        public string $customerId,
        public string $cashier,
        public ?string $transactionId = null,
        public ?string $orNumber = null,
    )
    {

    }
}

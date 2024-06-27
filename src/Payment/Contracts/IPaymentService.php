<?php

namespace PaymentContracts;

use PaymentContracts\Utils\Result;

interface IPaymentService
{
    /**
     * @param string $customerId
     * @param string $installmentId
     * @param int $orderTotal
     * @param int $months
     * @param float $interestRate
     * @param string $orderId
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $downPayment
     * @param string|null $transactionId
     * @param string|null $orNumber
     * @return Result
     */
    public function initializeInstallment(
        string $customerId,
        string $installmentId,
        int $orderTotal,
        int $months,
        float $interestRate,
        string $orderId,
        array $downPayment,
        string $cashier,
        ?string $transactionId,
        ?string $orNumber
    ): Result;

    /**
     * @param string $customerId
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $paymentMethods
     * @param string $cashier
     * @param string $transactionId
     * @param string $orNumber
     * @return Result
     */
    public function payInstallment(
        string $customerId,
        array $paymentMethods,
        string $cashier,
        string $transactionId,
        string $orNumber
    ): Result;
}

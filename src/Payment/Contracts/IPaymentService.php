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
     * @param array<array{'method': string, 'reference': string, 'amount': int, 'credit': bool}> $downPayment
     * @param string $cashier
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
    public function applyPenalty(
        string $customerId,
        string $installmentId,
        int $index,
        string $orderId,
        int $amount,
        string $actor
    ): Result;
    public function removePenalty(
        string $customerId,
        string $installmentId,
        int $index,
        string $orderId,
        string $actor
    ): Result;

    /**
     * @param string $customerId
     * @param int $orderTotal
     * @param string $orderId
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $downPayment
     * @param string $cashier
     * @param string|null $transactionId
     * @param string|null $orNumber
     * @return Result
     */
    public function requestCod(
        string $customerId,
        int $orderTotal,
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
     * @param string $orderId
     * @return Result
     */
    public function payCod(
        string $customerId,
        array $paymentMethods,
        string $cashier,
        string $transactionId,
        string $orNumber,
        string $orderId,
    ): Result;

    /**
     * @param string $customerId
     * @param array<array{'method': string, 'reference': string, 'amount': int, 'credit': bool}> $paymentMethods
     * @param string $cashier
     * @param string $transactionId
     * @param string $orNumber
     * @param string $orderId
     * @return Result
     */
    public function pay(
        string $customerId,
        array $paymentMethods,
        string $cashier,
        string $transactionId,
        string $orNumber,
        string $orderId,
    ): Result;

    public function startInstallment(string $customerId, string $orderId): Result;

    /**
     * @param string $customerId
     * @param array<array{'method': string, 'reference': string, 'amount': int, 'credit': bool}> $paymentMethods
     * @param string $cashier
     * @param string $transactionId
     * @param string $orNumber
     * @param string $orderId
     * @return Result
     */
    public function collectCod(
        string $customerId,
        array $paymentMethods,
        string $cashier,
        string $transactionId,
        string $orNumber,
        string $orderId,
    ): Result;
}

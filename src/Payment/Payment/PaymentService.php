<?php

namespace Payment;

use Exception;
use Payment\Models\Customer\Customer;
use PaymentContracts\IPaymentService;
use PaymentContracts\Utils\Result;

class PaymentService implements IPaymentService
{

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
    ): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->initializeInstallment(
                $installmentId,
                $orderTotal,
                $months,
                $interestRate,
                $orderId,
                $downPayment,
                $cashier,
                $transactionId,
                $orNumber
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }

    }

    public function payInstallment(string $customerId, array $paymentMethods, string $cashier, string $transactionId, string $orNumber): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->payInstallment(
                $paymentMethods,
                $cashier,
                $transactionId,
                $orNumber
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }
    }

    public function requestCod(
        string $customerId,
        int $orderTotal,
        string $orderId,
        array $downPayment,
        string $cashier,
        ?string $transactionId,
        ?string $orNumber
    ): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->requestCod(
                $orderTotal,
                $orderId,
                $downPayment,
                $cashier,
                $transactionId,
                $orNumber
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }
    }

    public function payCod(
        string $customerId,
        array $paymentMethods,
        string $cashier,
        string $transactionId,
        string $orNumber,
        string $orderId
    ): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->payCod(
                $paymentMethods,
                $cashier,
                $transactionId,
                $orNumber,
                $orderId
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }
    }
}

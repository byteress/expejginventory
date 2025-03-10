<?php

namespace Payment;

use Exception;
use Illuminate\Support\Facades\DB;
use Order\Models\Order\Order;
use Payment\Models\Customer\Customer;
use PaymentContracts\Exceptions\InvalidDomainException;
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
            $order = DB::table('orders')
                ->where('order_id', $orderId)
                ->first();

            if(!$order) throw new Exception('Order not found');

            $orderAggregate = Order::retrieve($orderId);

//            if (!$orderAggregate->cancelledOrder && !$orderAggregate->isPrevious()) {
//                foreach ($downPayment as $dp) {
//                    if ($dp['credit']) throw new InvalidDomainException('Credit not allowed for new orders.', ['credit' => 'Credit not allowed for new orders.']);
//                }
//            }

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
                $orNumber,
                $order->delivery_type,
                $orderAggregate->getInstallmentStartDate(),
                $this->isSameDayCancelled($orderAggregate)
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }

    }
    public function payInstallment(string $customerId, array $paymentMethods, string $cashier, string $transactionId, string $orNumber, array $installmentIds): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->payInstallment(
                $paymentMethods,
                $cashier,
                $transactionId,
                $orNumber,
                $installmentIds
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }
    }

    public function applyPenalty(
        string $customerId,
        string $installmentId,
        int $index,
        string $orderId,
        int $amount,
        string $actor
    ): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->applyPenalty(
                $installmentId,
                $index,
                $orderId,
                $amount,
                $actor
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }
    }

    public function removePenalty(
        string $customerId,
        string $installmentId,
        int $index,
        string $orderId,
        string $actor
    ): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->removePenalty(
                $installmentId,
                $index,
                $orderId,
                $actor
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

    public function pay(
        string $customerId,
        array $paymentMethods,
        string $cashier,
        string $transactionId,
        string $orNumber,
        string $orderId,
    ): Result
    {
        try {
            $order = Order::retrieve($orderId);

//            if (!$order->cancelledOrder && !$order->isPrevious()) {
//                foreach ($paymentMethods as $dp) {
//                    if ($dp['credit']) throw new InvalidDomainException('Credit not allowed for new orders.', ['credit' => 'Credit not allowed for new orders.']);
//                }
//            }

            $customer = Customer::retrieve($customerId);
            $customer->pay(
                $paymentMethods,
                $cashier,
                $transactionId,
                $orNumber,
                $orderId,
                $this->isSameDayCancelled($order)
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }
    }

    public function startInstallment(string $customerId, string $orderId): Result
    {
        try {
            $customer = Customer::retrieve($customerId);
            $customer->startInstallment(
                $orderId
            );

            $customer->persist();

            return Result::success(null);
        } catch (Exception $exception) {
            report($exception);
            return Result::failure($exception);
        }
    }

    public function collectCod(string $customerId, array $paymentMethods, string $cashier, string $transactionId, string $orNumber, string $orderId,): Result
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

    private function isSameDayCancelled(Order $cancelledOrder): bool
    {
        if(!$cancelledOrder->cancelledOrder || $cancelledOrder->isPrevious()) return false;

        $cancelled = DB::table('orders')
            ->where('order_id', $cancelledOrder->cancelledOrder)
            ->first();

        if(!$cancelled) return false;

        return date('Y-m-d', strtotime($cancelled->completed_at)) == date('Y-m-d');
    }
}

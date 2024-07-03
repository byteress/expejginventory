<?php

namespace Payment\Models\Customer;

use Carbon\Carbon;
use Payment\Models\Aggregate;
use PaymentContracts\Events\CodPaymentReceived;
use PaymentContracts\Events\CodPaymentRequested;
use PaymentContracts\Events\InstallmentInitialized;
use PaymentContracts\Events\DownPaymentReceived;
use PaymentContracts\Events\InstallmentPaymentReceived;
use PaymentContracts\Exceptions\InvalidDomainException;

class Customer extends Aggregate
{
    public int $balance = 0;

    /**
     * @var array<string, int>
     */
    public array $orders = [];

    /**
     * @var array<array{'due': Carbon, 'balance': int, 'amount': int, 'index': int}>
     */
    public array $installments = [];

    /**
     * @param string $installmentId
     * @param int $orderTotal
     * @param int $months
     * @param float $interestRate
     * @param string $orderId
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $downPayment
     * @param string $cashier
     * @param string|null $transactionId
     * @param string|null $orNumber
     * @return Customer
     * @throws InvalidDomainException
     */
    public function initializeInstallment(
        string $installmentId,
        int $orderTotal,
        int $months,
        float $interestRate,
        string $orderId,
        array $downPayment,
        string $cashier,
        ?string $transactionId,
        ?string $orNumber
    ): self
    {
        if(!empty($downPayment) && !$transactionId)
            throw new InvalidDomainException('Transaction ID is required.', ['transaction_id' => 'Transaction ID is required.']);

        if(!empty($downPayment) && !$orNumber)
            throw new InvalidDomainException('OR Number is required.', ['or_number' => 'OR Number is required.']);

        $totalDownPayment = 0;
        foreach($downPayment as $dp){
            $totalDownPayment += $dp['amount'];
        }

        $installmentAmount = $orderTotal - $totalDownPayment;
        $withInterest = round($installmentAmount + ($installmentAmount * ($interestRate / 100)));

        $installments = [];
        for($i = 1; $i <= $months; $i++){
            $installments[] = [
                'due' => \Date::now()->addMonths($i),
                'amount' => round($withInterest / $months),
                'penalty' => 0,
                'balance' => round($withInterest / $months),
                'order_id' => $orderId,
            ];
        }

        $event = new InstallmentInitialized(
            $installmentId,
            (int) $withInterest,
            $months,
            $interestRate,
            $installments,
            $orderId,
            $this->uuid(),
            $cashier,
            $transactionId,
            $orNumber
        );

        $this->recordThat($event);

        if(empty($downPayment) || is_null($transactionId) || !$orNumber)
            return $this;

        $paymentReceivedEvent = new DownPaymentReceived(
            $transactionId,
            $orderId,
            $this->uuid(),
            $downPayment,
            $orNumber,
            $cashier
        );

        $this->recordThat($paymentReceivedEvent);

        return $this;
    }

    /**
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $paymentMethods
     * @param string $cashier
     * @param string $transactionId
     * @param string $orNumber
     * @return $this
     */
    public function payInstallment(array $paymentMethods, string $cashier, string $transactionId, string $orNumber): self
    {
        $total = 0;
        foreach($paymentMethods as $dp){
            $total += $dp['amount'];
        }

//        foreach ($this->installments as $installment){
//            if($installment['balance'] >= $total){
//                $event = new InstallmentPaymentReceived(
//
//                );
//            }
//        }

        $event = new InstallmentPaymentReceived(
            $transactionId,
            $this->uuid(),
            $paymentMethods,
            $orNumber,
            $cashier,
             $total
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @param int $orderTotal
     * @param string $orderId
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $downPayment
     * @param string $cashier
     * @param string|null $transactionId
     * @param string|null $orNumber
     * @return $this
     */
    public function requestCod(
        int $orderTotal,
        string $orderId,
        array $downPayment,
        string $cashier,
        ?string $transactionId,
        ?string $orNumber
    ): self
    {
        $totalDownPayment = 0;
        foreach($downPayment as $dp){
            $totalDownPayment += $dp['amount'];
        }

        $event = new CodPaymentRequested(
            $orderTotal - $totalDownPayment,
            $orderId,
            $this->uuid(),
            $cashier,
            $transactionId,
            $orNumber
        );

        $this->recordThat($event);

        if(empty($downPayment) || is_null($transactionId) || !$orNumber)
            return $this;

        $paymentReceivedEvent = new DownPaymentReceived(
            $transactionId,
            $orderId,
            $this->uuid(),
            $downPayment,
            $orNumber,
            $cashier
        );

        $this->recordThat($paymentReceivedEvent);

        return $this;
    }

    /**
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $paymentMethods
     * @param string $cashier
     * @param string $transactionId
     * @param string $orNumber
     * @return $this
     */
    public function payCod(array $paymentMethods, string $cashier, string $transactionId, string $orNumber, string $orderId): self
    {
        $total = 0;
        foreach($paymentMethods as $dp){
            $total += $dp['amount'];
        }

        $event = new CodPaymentReceived(
            $transactionId,
            $this->uuid(),
            $paymentMethods,
            $orNumber,
            $cashier,
            $total,
            $orderId
        );

        $this->recordThat($event);

        return $this;
    }

    public function applyInstallmentInitialized(InstallmentInitialized $event): void
    {
        $this->balance += (int) round($event->amount + ($event->amount * ($event->interestRate / 100)));
        $this->orders[$event->orderId] = (int) round($event->amount + ($event->amount * ($event->interestRate / 100)));
        $merge = array_merge($this->installments, $event->installments);

//        usort($merge, function ($a, $b) {
//            return $b['due']->gt($a['due']);
//        });

        $this->installments = $merge;
    }
}

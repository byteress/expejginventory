<?php

namespace Payment\Models\Customer;

use Carbon\Carbon;
use DateTime;
use Exception;
use Payment\Models\Aggregate;
use PaymentContracts\Events\CodPaymentCollected;
use PaymentContracts\Events\CodPaymentRequested;
use PaymentContracts\Events\CodReceived;
use PaymentContracts\Events\FullPaymentReceived;
use PaymentContracts\Events\InstallmentInitialized;
use PaymentContracts\Events\DownPaymentReceived;
use PaymentContracts\Events\InstallmentPaymentReceived;
use PaymentContracts\Events\InstallmentStarted;
use PaymentContracts\Events\PenaltyApplied;
use PaymentContracts\Events\PenaltyRemoved;
use PaymentContracts\Exceptions\InvalidDomainException;

class Customer extends Aggregate
{
    public int $balance = 0;

    /**
     * @var array<string, int>
     */
    public array $orders = [];

    /**
     * @var array<array{'due': ?Carbon, 'balance': int, 'amount': int, 'index': int, 'installmentId': string, 'orderId': string}>
     */
    public array $installments = [];
    /**
     * @var array<string, array{'amount': int, 'balance': int, 'type': string}>
     */
    public array $codBalances = [];
    /**
     * @var array<string, InstallmentInitialized>
     */
    public array $installmentRequests = [];

    /**
     * @param string $installmentId
     * @param int $orderTotal
     * @param int $months
     * @param float $interestRate
     * @param string $orderId
     * @param array<array{'method': string, 'reference': string, 'amount': int, 'credit': bool}> $downPayment
     * @param string $cashier
     * @param string|null $transactionId
     * @param string|null $orNumber
     * @param string $deliveryType
     * @param DateTime|null $installmentStartDate
     * @param bool $isSameDayCancelled
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
        ?string $orNumber,
        string $deliveryType,
        ?DateTime $installmentStartDate = null,
        bool $isSameDayCancelled = false
    ): self
    {
        if(!empty($downPayment) && !$transactionId)
            throw new InvalidDomainException('Transaction ID is required.', ['transaction_id' => 'Transaction ID is required.']);

        if(!empty($downPayment) && !$orNumber)
            throw new InvalidDomainException('OR Number is required.', ['or_number' => 'OR Number is required.']);

        $this->validatePaymentMethods($downPayment);

        $totalDownPayment = 0;
        foreach($downPayment as $dp){
            if($isSameDayCancelled && $dp['credit']) throw new InvalidDomainException('Credit not allowed for same day cancel.', ['credit' => 'Credit not allowed for same day cancel.']);

            $totalDownPayment += $dp['amount'];
        }

        $codAmount = $this->calculateCODTotalAmount($downPayment);
        $installmentAmount = $orderTotal - $totalDownPayment;
        $withInterest = round($installmentAmount + ($installmentAmount * ($interestRate / 100)));

        $installments = [];
        for($i = 1; $i <= $months; $i++){
            $installments[] = [
//                'due' => \Date::now()->addMinutes($i),
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

        if($codAmount == 0 && $deliveryType == 'pickup')
            $this->startInstallment($orderId, installmentStartDate: $installmentStartDate);

        if(is_null($transactionId) || !$orNumber)
            return $this;

        $paymentReceivedEvent = new DownPaymentReceived(
            $transactionId,
            $orderId,
            $this->uuid(),
            $downPayment,
            $orNumber,
            $cashier,
            $isSameDayCancelled
        );

        $this->recordThat($paymentReceivedEvent);

        if($codAmount == 0) return $this;

        $this->recordThat(new CodReceived(
            $orderId,
            $this->uuid(),
            $codAmount,
            'installment'
        ));

        return $this;
    }

    public function startInstallment(string $orderId, int $codBalance = 0, ?DateTime $installmentStartDate = null): self
    {
        $balance = $this->codBalances[$orderId]['balance'] ?? 0;

        if($balance != $codBalance) return $this;

        $interestRate = $this->installmentRequests[$orderId]->interestRate;
        $withInterest = round($codBalance + ($codBalance * ($interestRate / 100)));

        $dues = [];
        $i = $installmentStartDate ? 0 : 1;
        foreach($this->installments as $installment){
            if($installment['orderId'] == $orderId && !isset($installment['due'])){
                $date = $installmentStartDate ? \Date::parse($installmentStartDate->format('Y-m-d')) : Carbon::now();
                $dues[] = [
                    'due' => $date->addMonths($i),
                    'index' => $installment['index'],
                    'amount' => round($withInterest / $this->installmentRequests[$orderId]->months),
                ];

                $i++;
            }
        }

        $this->recordThat(new InstallmentStarted($orderId, $this->uuid(), $dues, $codBalance));

        return $this;
    }

    /**
     * @param array<array{'method': string, 'reference': string, 'amount': int}> $paymentMethods
     * @param string $cashier
     * @param string $transactionId
     * @param string $orNumber
     * @param array<string> $installmentIds
     * @return $this
     * @throws InvalidDomainException
     */
    public function payInstallment(array $paymentMethods, string $cashier, string $transactionId, string $orNumber, array $installmentIds): self
    {
        $total = 0;
        foreach($paymentMethods as $dp){
            $total += $dp['amount'];
        }

        if(!$this->areInstallmentsFromSameOrder($installmentIds)) throw new InvalidDomainException('Please select bills from the same order.', ['installment_ids' => 'Please select bills from the same order.']);

        if($total > $this->getTotalBalance($installmentIds)) throw new InvalidDomainException('Amount should not be greater than the total bill.', ['installment_ids' => 'Amount should not be greater than the total bill.']);

        $event = new InstallmentPaymentReceived(
            $transactionId,
            $this->uuid(),
            $paymentMethods,
            $orNumber,
            $cashier,
            $total,
            $this->getOrderIdByInstallments($installmentIds),
            $installmentIds
        );

        $this->recordThat($event);

        return $this;
    }

    public function applyPenalty(
        string $installmentId,
        int $index,
        string $orderId,
        int $amount,
        string $actor
    ): self
    {
        $event = new PenaltyApplied(
            $installmentId,
            $index,
            $this->uuid(),
            $orderId,
            $amount,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function removePenalty(
        string $installmentId,
        int $index,
        string $orderId,
        string $actor
    ): self
    {
        $amount = 0;
        foreach($this->installments as $installment){
            if($installment['installmentId'] === $installmentId && $installment['index'] === $index) {
                $amount = $installment['penalty'];
            }
        }

        $event = new PenaltyRemoved(
            $installmentId,
            $index,
            $this->uuid(),
            $orderId,
            $amount,
            $actor
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
     * @param string $orderId
     * @return $this
     * @throws InvalidDomainException
     */
    public function payCod(array $paymentMethods, string $cashier, string $transactionId, string $orNumber, string $orderId): self
    {
        $total = 0;
        foreach($paymentMethods as $dp){
            $total += $dp['amount'];
        }

        if($total > $this->codBalances[$orderId]['balance']) throw new InvalidDomainException('Amount should not be greater than the total balance.', ['total' => 'Amount should not be greater than the total balance.']);

        $event = new CodPaymentCollected(
            $transactionId,
            $this->uuid(),
            $paymentMethods,
            $orNumber,
            $cashier,
            $total,
            $orderId
        );

        $this->recordThat($event);

        if($this->codBalances[$orderId]['type'] != 'installment') return $this;

        $this->startInstallment($orderId, $this->codBalances[$event->orderId]['balance']);

        return $this;
    }

    /**
     * @param array<array{'method': string, 'reference': string, 'amount': int, 'credit': bool}> $paymentMethods
     * @param string $cashier
     * @param string $transactionId
     * @param string $orNumber
     * @param string $orderId
     * @param bool $isSameDayCancelled
     * @return $this
     * @throws InvalidDomainException
     */
    public function pay(array $paymentMethods, string $cashier, string $transactionId, string $orNumber, string $orderId, bool $isSameDayCancelled): self
    {
        $this->validatePaymentMethods($paymentMethods);

        $total = 0;
        foreach($paymentMethods as $dp){
            if($isSameDayCancelled && $dp['credit']) throw new InvalidDomainException('Credit not allowed for same day cancel.', ['credit' => 'Credit not allowed for same day cancel.']);

            $total += $dp['amount'];
        }

        $event = new FullPaymentReceived(
            $transactionId,
            $this->uuid(),
            $paymentMethods,
            $orNumber,
            $cashier,
            $total,
            $orderId,
            $isSameDayCancelled
        );

        $this->recordThat($event);

        $codAmount = $this->calculateCODTotalAmount($paymentMethods);
        if($codAmount == 0) return $this;

        $this->recordThat(new CodReceived(
            $orderId,
            $this->uuid(),
            $codAmount,
            'full'
        ));

        return $this;
    }

    /**
     * @param array<array{'method': string, 'reference': string, 'amount': int, 'credit': bool}> $paymentMethods
     * @return int
     */
    private function calculateCODTotalAmount(array $paymentMethods): int
    {
        $totalCOD = 0;

        foreach ($paymentMethods as $payment) {
            if ($payment['method'] === 'COD' && !$payment['credit']) {
                // Add the amount if the method is COD and it's not a credit payment
                $totalCOD += $payment['amount'];
            }
        }

        return $totalCOD;
    }

    /**
     * @param array<string> $installmentIds
     * @return bool
     */
    public function areInstallmentsFromSameOrder(array $installmentIds): bool
    {
        // Filter the installments that match the given installmentIds
        $filteredInstallments = array_filter($this->installments, function ($installment) use ($installmentIds) {
            return in_array($installment['installmentId'], $installmentIds, true);
        });

        // If no installments found or only one, we can assume they match
        if (count($filteredInstallments) <= 1) {
            return true;
        }

        // Get the orderId of the first installment and compare with the rest
        $firstOrderId = reset($filteredInstallments)['orderId'];

        foreach ($filteredInstallments as $installment) {
            if ($installment['orderId'] !== $firstOrderId) {
                return false; // Different orderId found
            }
        }

        return true; // All orderIds are the same
    }

    /**
     * @param array<string> $installmentIds
     * @return int
     */
    private function getTotalBalance(array $installmentIds): int
    {
        // Filter the installments that match the given installmentIds
        $filteredInstallments = array_filter($this->installments, function ($installment) use ($installmentIds) {
            return in_array($installment['installmentId'], $installmentIds, true);
        });

        // Sum up the balance of the filtered installments
        $totalBalance = array_reduce($filteredInstallments, function ($carry, $installment) {
            return $carry + $installment['balance'];
        }, 0);

        return $totalBalance;
    }

    /**
     * @param array<array{'method': string, 'reference': string, 'amount': int, 'credit': bool}> $payments
     * @return void
     * @throws InvalidDomainException
     */
    private function validatePaymentMethods(array $payments): void {
        foreach ($payments as $payment) {

            if (strtoupper($payment['method']) === 'COD' && $payment['credit'] === true) {
                throw new InvalidDomainException(
                    'Credited COD is not allowed.',
                    ['methods' => 'Credited COD is not allowed.']
                );
            }
        }
    }

    /**
     * @param array<string> $installmentIds
     * @return string
     * @throws InvalidDomainException
     */
    public function getOrderIdByInstallments(array $installmentIds): string
    {
        // Filter the installments that match the given installmentIds
        $filteredInstallments = array_filter($this->installments, function ($installment) use ($installmentIds) {
            return in_array($installment['installmentId'], $installmentIds, true);
        });

        // Check if there is at least one installment
        if (!empty($filteredInstallments)) {
            // Return the orderId from the first installment (assuming all have the same orderId)
            return reset($filteredInstallments)['orderId'];
        }

        // Return null if no installments found
        throw new InvalidDomainException('No installments found.', ['installment' => 'No installments found.']);
    }

    public function applyInstallmentInitialized(InstallmentInitialized $event): void
    {
        $this->balance += (int) round($event->amount + ($event->amount * ($event->interestRate / 100)));
        $this->orders[$event->orderId] = (int) round($event->amount + ($event->amount * ($event->interestRate / 100)));
        $this->installmentRequests[$event->orderId] = $event;

        $installments = [];
        foreach($event->installments as $key => $installment){
            $i = $installment;
            $i['installmentId'] = $event->installmentId;
            $i['index'] = $key;
            $i['due'] = null;
            $i['orderId'] = $event->orderId;
            $installments[] = $i;
        }

        $merge = array_merge($this->installments, $installments);

//        usort($merge, function ($a, $b) {
//            return $b['due']->gt($a['due']);
//        });

        $this->installments = $merge;
    }

    public function applyInstallmentStarted(InstallmentStarted $event): void
    {
        foreach($this->installments as $key => $installment){
            foreach ($event->dues as $due){
                if($installment['orderId'] === $event->orderId && $installment['index'] === $due['index']){
                    $this->installments[$key]['due'] = $due['due'];
                    $oldAmount = $this->installments[$key]['amount'];

                    $this->installments[$key]['amount'] = $oldAmount + $due['amount'];
                    $this->installments[$key]['balance'] = $oldAmount + $due['amount'];
                }
            }
        }

        $oldBalance = $this->codBalances[$event->orderId]['balance'] ?? 0;
        $this->codBalances[$event->orderId]['balance'] = $oldBalance - $event->codBalance;
    }

    public function applyInstallmentPaymentReceived(InstallmentPaymentReceived $event): void
    {
        $this->balance -= $event->amount;

        $installmentIds = $event->installmentIds;
        $paymentTotal = $event->amount;
        // Filter the installments that match the given installmentIds
        $filteredInstallments = array_filter($this->installments, function ($installment) use ($installmentIds) {
            return in_array($installment['installmentId'], $installmentIds, true);
        });

        // Apply the payment to each installment
        foreach ($filteredInstallments as &$installment) {
            if ($paymentTotal <= 0) {
                break; // No payment left to apply
            }

            if ($installment['balance'] <= $paymentTotal) {
                // The payment covers the full balance of this installment
                $paymentTotal -= $installment['balance'];
                $installment['balance'] = 0;
            } else {
                // Only part of the installment is covered by the payment
                $installment['balance'] -= $paymentTotal;
                $paymentTotal = 0;
            }
        }

        // Update the main $installments array with the modified balances
        foreach ($this->installments as &$originalInstallment) {
            foreach ($filteredInstallments as $updatedInstallment) {
                if ($originalInstallment['installmentId'] === $updatedInstallment['installmentId']) {
                    $originalInstallment = $updatedInstallment;
                }
            }
        }
    }

    public function applyPenaltyApplied(PenaltyApplied $event): void
    {
        foreach ($this->installments as $key => $installment){
            if($installment['installmentId'] === $event->installmentId && $installment['index'] === $event->index){
                $this->installments[$key]['penalty'] = $event->amount;
                $this->installments[$key]['balance'] = $this->installments[$key]['balance'] + $event->amount;
                break;
            }
        }
    }

    public function applyCodReceived(CodReceived $event): void
    {
        $this->codBalances[$event->orderId] = [
            'amount' => $event->amount,
            'balance' => $event->amount,
            'type' => $event->paymentType,
        ];
    }

    public function applyCodPaymentCollected(CodPaymentCollected $event): void
    {
        $oldBalance = $this->codBalances[$event->orderId]['balance'] ?? 0;

        $this->codBalances[$event->orderId]['balance'] = $oldBalance - $event->amount;
    }
}

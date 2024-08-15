<?php

namespace Payment\Projectors;

use Illuminate\Support\Facades\DB;
use OrderContracts\Events\OrderCancelled;
use PaymentContracts\Events\CodPaymentReceived;
use PaymentContracts\Events\CodPaymentRequested;
use PaymentContracts\Events\InstallmentInitialized;
use PaymentContracts\Events\InstallmentPaymentReceived;
use PaymentContracts\Events\PenaltyApplied;
use PaymentContracts\Events\PenaltyRemoved;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class CustomerBalanceProjector extends Projector
{
    public function onInstallmentInitialized(InstallmentInitialized $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'increment', 'installment');
    }

    public function onInstallmentPaymentReceived(InstallmentPaymentReceived $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'decrement', 'installment');
    }

    public function onCodPaymentRequested(CodPaymentRequested $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'increment', 'cod');
    }

    public function onCodPaymentReceived(CodPaymentReceived $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'decrement', 'cod');
    }

    public function onPenaltyApplied(PenaltyApplied $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'increment', 'installment');
    }

    public function onPenaltyRemoved(PenaltyRemoved $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'decrement', 'installment');
    }

    public function onOrderCancelled(OrderCancelled $event): void
    {
        $balance = DB::table('installment_bills')
            ->where('order_id', $event->orderId)
            ->sum('balance');

        $order = DB::table('orders')
            ->where('order_id', $event->orderId)
            ->first();

        if(!$order) return;

        $this->updateBalance($order->customer_id, $balance, 'decrement', 'installment');
    }

    private function updateBalance(string $customerId, int $amount, string $action, string $type): void
    {
        $balance = DB::table('customer_balances')
            ->where('customer_id', $customerId)
            ->first();

        if(!$balance){
            DB::table('customer_balances')
                ->insert([
                    'customer_id' => $customerId,
                    'balance' => $amount,
                    $type => $amount
                ]);

            return;
        }

        if($action == 'increment'){
            DB::table('customer_balances')
                ->where('customer_id', $customerId)
                ->increment('balance', $amount);

            DB::table('customer_balances')
                ->where('customer_id', $customerId)
                ->increment($type, $amount);
        }else{
            DB::table('customer_balances')
                ->where('customer_id', $customerId)
                ->decrement('balance', $amount);

            DB::table('customer_balances')
                ->where('customer_id', $customerId)
                ->decrement($type, $amount);
        }
    }

    public function onStartingEventReplay(): void
    {
        DB::table('customer_balances')
            ->truncate();
    }
}

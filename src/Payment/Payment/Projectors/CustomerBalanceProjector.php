<?php

namespace Payment\Projectors;

use Illuminate\Support\Facades\DB;
use PaymentContracts\Events\CodPaymentReceived;
use PaymentContracts\Events\CodPaymentRequested;
use PaymentContracts\Events\InstallmentInitialized;
use PaymentContracts\Events\InstallmentPaymentReceived;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class CustomerBalanceProjector extends Projector
{
    public function onInstallmentInitialized(InstallmentInitialized $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'increment');
    }

    public function onInstallmentPaymentReceived(InstallmentPaymentReceived $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'decrement');
    }

    public function onCodPaymentRequested(CodPaymentRequested $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'increment');
    }

    public function onCodPaymentReceived(CodPaymentReceived $event): void
    {
        $this->updateBalance($event->customerId, $event->amount, 'decrement');
    }

    private function updateBalance(string $customerId, int $amount, string $action): void
    {
        $balance = DB::table('customer_balances')
            ->where('customer_id', $customerId)
            ->first();

        if(!$balance){
            DB::table('customer_balances')
                ->insert([
                    'customer_id' => $customerId,
                    'balance' => $amount,
                ]);

            return;
        }

        if($action == 'increment'){
            DB::table('customer_balances')
                ->where('customer_id', $customerId)
                ->increment('balance', $amount);
        }else{
            DB::table('customer_balances')
                ->where('customer_id', $customerId)
                ->decrement('balance', $amount);
        }
    }

    public function onStartingEventReplay(): void
    {
        DB::table('customer_balances')
            ->truncate();
    }
}

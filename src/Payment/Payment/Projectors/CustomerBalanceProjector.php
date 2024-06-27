<?php

namespace Payment\Projectors;

use Illuminate\Support\Facades\DB;
use PaymentContracts\Events\InstallmentInitialized;
use PaymentContracts\Events\InstallmentPaymentReceived;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class CustomerBalanceProjector extends Projector
{
    public function onInstallmentInitialized(InstallmentInitialized $event): void
    {
        $this->updateBalance($event->customerId, $event->amount);
    }

    public function onInstallmentPaymentReceived(InstallmentPaymentReceived $event): void
    {
        $this->updateBalance($event->customerId, $event->amount);
    }

    private function updateBalance(string $customerId, int $amount): void
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

        DB::table('customer_balances')
            ->where('customer_id', $customerId)
            ->increment('balance', $amount);
    }

    public function onStartingEventReplay(): void
    {
        DB::table('customer_balances')
            ->truncate();
    }
}

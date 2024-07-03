<?php

namespace Payment\Projectors;

use Illuminate\Support\Facades\DB;
use PaymentContracts\Events\CodPaymentReceived;
use PaymentContracts\Events\CodPaymentRequested;
use PaymentContracts\Events\InstallmentInitialized;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class OrderProjector extends Projector
{
    public function onInstallmentInitialized(InstallmentInitialized $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'payment_type' => 'installment',
                'status' => 1,
                'receipt_number' => $event->orNumber,
                'cashier' => $event->cashier,
                'months' => $event->months,
                'rate' => $event->interestRate
            ]);
    }

    public function onCodPaymentRequested(CodPaymentRequested $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'payment_type' => 'cod',
                'status' => 1,
                'receipt_number' => $event->orNumber,
                'cashier' => $event->cashier
            ]);
    }

    public function onCodPaymentReceived(CodPaymentReceived $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'status' => 2,
            ]);
    }
}

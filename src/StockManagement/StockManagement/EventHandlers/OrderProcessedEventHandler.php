<?php

namespace StockManagement\EventHandlers;

use App\Jobs\ProcessOrderJob;
use Illuminate\Support\Facades\DB;
use PaymentContracts\Events\CodPaymentRequested;
use PaymentContracts\Events\FullPaymentReceived;
use PaymentContracts\Events\InstallmentInitialized;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class OrderProcessedEventHandler extends Reactor
{
    public function onFullPaymentReceived(FullPaymentReceived $event): void
    {
        ProcessOrderJob::dispatch($event->orderId);
    }

    public function onInstallmentInitialized(InstallmentInitialized $event): void
    {
        ProcessOrderJob::dispatch($event->orderId);
    }

    public function onCodPaymentRequested(CodPaymentRequested $event): void
    {
        $order = DB::table('orders')
            ->where('order_id', $event->orderId)
            ->first();

        if(!$order || $order->order_type != 'regular') return;

        ProcessOrderJob::dispatch($event->orderId);
    }
}

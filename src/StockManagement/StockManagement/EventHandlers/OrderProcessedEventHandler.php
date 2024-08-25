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
        $this->processOrder($event->orderId);
    }

    public function onInstallmentInitialized(InstallmentInitialized $event): void
    {
        $this->processOrder($event->orderId);
    }

    private function processOrder(string $orderId): void
    {
        $order = DB::table('orders')
            ->where('order_id', $orderId)
            ->first();

        if(!$order || $order->order_type != 'regular') return;

        ProcessOrderJob::dispatch($orderId);
    }
}

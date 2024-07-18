<?php

namespace StockManagement\EventHandlers;

use App\Jobs\ProcessAdvancedOrderJob;
use Illuminate\Support\Facades\Bus;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use StockManagementContracts\Events\ProductReceived;

class AdvancedOrderEventHandler extends Reactor
{
    /**
     * @throws \Throwable
     */
    public function onProductReceived(ProductReceived $event): void
    {
        Bus::batch([
            new ProcessAdvancedOrderJob($event->productId)
        ])->dispatch();
    }
}

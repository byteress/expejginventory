<?php

namespace StockManagement\EventHandlers;

use App\Jobs\ProcessAdvancedOrderJob;
use Illuminate\Support\Facades\Bus;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use StockManagementContracts\Events\ProductAdjusted;
use StockManagementContracts\Events\ProductReceived;
use Throwable;

class AdvancedOrderEventHandler extends Reactor
{
    /**
     * @throws Throwable
     */
    public function onProductReceived(ProductReceived $event): void
    {
        Bus::batch([
            new ProcessAdvancedOrderJob($event->productId)
        ])->dispatch();
    }

    /**
     * @throws Throwable
     */
    public function onProductAdjusted(ProductAdjusted $event): void
    {
        Bus::batch([
            new ProcessAdvancedOrderJob($event->productId)
        ])->dispatch();
    }
}

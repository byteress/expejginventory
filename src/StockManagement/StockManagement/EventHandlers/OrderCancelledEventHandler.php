<?php

namespace StockManagement\EventHandlers;

use App\Jobs\CancelledOrderJob;
use OrderContracts\Events\OrderCancelled;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class OrderCancelledEventHandler extends Reactor
{
    public function onOrderCancelled(OrderCancelled $event): void
    {
        CancelledOrderJob::dispatch($event->orderId, $event->actor);
    }
}

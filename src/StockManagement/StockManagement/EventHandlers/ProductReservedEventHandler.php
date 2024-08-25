<?php

namespace StockManagement\EventHandlers;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use StockManagementContracts\Events\ProductReserved;

class ProductReservedEventHandler extends Reactor
{
    public function onProductReserved(ProductReserved $event): void
    {
        dispatch(function () use ($event) {
            app()->call('StockManagementContracts\IStockManagementService@cancelReservation', [
                'productId' => $event->productId,
                'reservationId' => $event->reservationId,
                'actor' => null,
                'expired' => true
            ]);
        })->delay(now()->addHour());
    }
}

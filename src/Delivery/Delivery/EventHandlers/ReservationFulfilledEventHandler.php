<?php

namespace Delivery\EventHandlers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use StockManagementContracts\Events\ReservationFulfilled;

class ReservationFulfilledEventHandler extends Reactor
{
    /**
     * @throws Exception
     */
    public function onReservationFulfilled(ReservationFulfilled $event): void
    {
        Log::info("Event handler ran");
        $item = DB::table('delivery_items')
            ->where('reservation_id', $event->reservationId)
            ->where('product_id', $event->productId)
            ->first();

        if(!$item) throw new Exception('Delivery item not found');

        app()->call('DeliveryContracts\IDeliveryService@confirmItemForDelivery', [
            'orderId' => $item->order_id,
            'productId' => $event->productId,
            'reservationId' => $event->reservationId,
            'quantity' => $event->quantity
        ]);
    }
}

<?php

namespace StockManagement\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use StockManagementContracts\Events\ProductReserved;
use StockManagementContracts\Events\ReservationCancelled;
use StockManagementContracts\Events\ReservationFulfilled;

class AdvancedReservationsProjector extends Projector
{
    public function onProductReserved(ProductReserved $event): void
    {
        if(!$event->advancedOrder) return;

        DB::table('advanced_reservations')
            ->insert([
                'reservation_id' => $event->reservationId,
                'product_id' => $event->productId
            ]);
    }

    public function onReservationCancelled(ReservationCancelled $event): void
    {
        DB::table('advanced_reservations')
            ->where('reservation_id', $event->reservationId)
            ->delete();
    }

    public function onReservationFulfilled(ReservationFulfilled $event): void
    {
        DB::table('advanced_reservations')
            ->where('reservation_id', $event->reservationId)
            ->delete();
    }
}

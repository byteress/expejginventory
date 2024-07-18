<?php

namespace StockManagement\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use StockManagementContracts\Events\ReservationFulfilled;

class ConfirmedItemProjector extends Projector
{
    public function onReservationFulfilled(ReservationFulfilled $event): void
    {
        DB::table('line_items')
            ->where('product_id', $event->productId)
            ->where('reservation_id', $event->reservationId)
            ->update([
                'confirmed' => true,
            ]);
    }
}

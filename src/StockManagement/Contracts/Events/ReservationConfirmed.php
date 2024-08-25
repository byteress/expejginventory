<?php

namespace StockManagementContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ReservationConfirmed extends ShouldBeStored
{
    public function __construct(
        public string $productId,
        public string $reservationId,
        public string $branchId,
    )
    {

    }
}

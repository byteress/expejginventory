<?php

namespace DeliveryContracts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DeliveryNotesUpdated extends ShouldBeStored
{
    public function __construct(
        public string $deliveryId,
        public string $notes
    )
    {
    }
}

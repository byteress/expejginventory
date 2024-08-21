<?php

namespace Delivery\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

final class DeliveryEvent extends EloquentStoredEvent
{
    public $table = 'delivery_stored_events';
}

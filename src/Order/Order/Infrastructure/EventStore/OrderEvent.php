<?php

namespace Order\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

final class OrderEvent extends EloquentStoredEvent
{
    public $table = 'order_stored_events';
}
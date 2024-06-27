<?php

namespace Payment\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

final class PaymentEvent extends EloquentStoredEvent
{
    public $table = 'payment_stored_events';
}

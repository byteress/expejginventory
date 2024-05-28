<?php

namespace Transfer\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

final class TransferEvent extends EloquentStoredEvent
{
    public $table = 'transfer_stored_events';
}
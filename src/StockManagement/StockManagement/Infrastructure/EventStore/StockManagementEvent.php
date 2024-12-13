<?php

namespace StockManagement\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

final class StockManagementEvent extends EloquentStoredEvent
{
    public $table = 'stock_management_stored_events';
}
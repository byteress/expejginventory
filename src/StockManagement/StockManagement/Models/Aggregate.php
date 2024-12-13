<?php

namespace StockManagement\Models;

use StockManagement\Infrastructure\EventStore\StockManagementEventsRepository;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use Spatie\EventSourcing\StoredEvents\Repositories\StoredEventRepository;

abstract class Aggregate extends AggregateRoot
{
    protected function getStoredEventRepository(): StoredEventRepository
    {
        return app()->make(
            abstract:StockManagementEventsRepository::class,
        );
    }
}

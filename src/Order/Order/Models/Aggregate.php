<?php

namespace Order\Models;

use Order\Infrastructure\EventStore\OrderEventsRepository;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use Spatie\EventSourcing\StoredEvents\Repositories\StoredEventRepository;

abstract class Aggregate extends AggregateRoot
{
    protected function getStoredEventRepository(): StoredEventRepository
    {
        return app()->make(
            abstract:OrderEventsRepository::class,
        );
    }
}

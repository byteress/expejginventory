<?php

namespace Transfer\Models;

use Transfer\Infrastructure\EventStore\TransferEventsRepository;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use Spatie\EventSourcing\StoredEvents\Repositories\StoredEventRepository;

abstract class Aggregate extends AggregateRoot
{
    protected function getStoredEventRepository(): StoredEventRepository
    {
        return app()->make(
            abstract:TransferEventsRepository::class,
        );
    }
}

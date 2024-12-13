<?php

namespace Delivery\Models;

use Delivery\Infrastructure\EventStore\DeliveryEventsRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use Spatie\EventSourcing\StoredEvents\Repositories\StoredEventRepository;

abstract class Aggregate extends AggregateRoot
{
    /**
     * @throws BindingResolutionException
     */
    protected function getStoredEventRepository(): StoredEventRepository
    {
        return app()->make(
            abstract:DeliveryEventsRepository::class,
        );
    }
}

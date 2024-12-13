<?php

namespace Payment\Models;

use Illuminate\Contracts\Container\BindingResolutionException;
use Payment\Infrastructure\EventStore\PaymentEventsRepository;
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
            abstract:PaymentEventsRepository::class,
        );
    }
}

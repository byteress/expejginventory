<?php

namespace Delivery\Infrastructure\EventStore;

use Spatie\EventSourcing\AggregateRoots\Exceptions\InvalidEloquentStoredEventModel;
use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

final class DeliveryEventsRepository extends EloquentStoredEventRepository
{
    /**
     * @throws InvalidEloquentStoredEventModel
     */
    public function __construct(
        string $storedEventModel = DeliveryEvent::class,
    ) {
        parent::__construct();

        $this->storedEventModel = $storedEventModel;
    }
}

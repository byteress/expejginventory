<?php

namespace Payment\Infrastructure\EventStore;

use Spatie\EventSourcing\AggregateRoots\Exceptions\InvalidEloquentStoredEventModel;
use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

final class PaymentEventsRepository extends EloquentStoredEventRepository
{
    /**
     * @throws InvalidEloquentStoredEventModel
     */
    public function __construct(
        string $storedEventModel = PaymentEvent::class,
    ) {
        parent::__construct();

        $this->storedEventModel = $storedEventModel;
    }
}

<?php

namespace Order\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

final class OrderEventsRepository extends EloquentStoredEventRepository
{
    public function __construct(
        protected string $storedEventModel = OrderEvent::class,
    ) {
        parent::__construct();

        $this->storedEventModel = $storedEventModel;
    }
}
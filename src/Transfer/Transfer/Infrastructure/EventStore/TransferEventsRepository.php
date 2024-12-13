<?php

namespace Transfer\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

final class TransferEventsRepository extends EloquentStoredEventRepository
{
    public function __construct(
        protected string $storedEventModel = TransferEvent::class,
    ) {
        parent::__construct();

        $this->storedEventModel = $storedEventModel;
    }
}
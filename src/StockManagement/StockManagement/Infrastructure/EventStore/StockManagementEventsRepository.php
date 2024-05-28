<?php

namespace StockManagement\Infrastructure\EventStore;

use Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository;

final class StockManagementEventsRepository extends EloquentStoredEventRepository
{
    public function __construct(
        protected string $storedEventModel = StockManagementEvent::class,
    ) {
        parent::__construct();

        $this->storedEventModel = $storedEventModel;
    }
}
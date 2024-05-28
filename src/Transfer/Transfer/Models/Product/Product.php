<?php

namespace Transfer\Models\Product;

use Transfer\Models\Aggregate;
use TransferContracts\Events\ProductReceived;
use TransferContracts\Events\ProductRequested;
use TransferContracts\Events\ProductTransferred;
use TransferContracts\Exceptions\InvalidDomainException;

class Product extends Aggregate
{   
    /** @var array<string, array<string, int>> */
    private array $state = [];

    public function request(
        int $quantity,
        string $receiver,
        string $actor
    ): self
    {
        $current = $this->state[$receiver]['requested'] ?? 0;

        if($current > 0) throw new InvalidDomainException('Already requested for this product', ['request' => 'Already requested for this product']);

        $event = new ProductRequested(
            $this->uuid(),
            $quantity,
            $receiver,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function transfer(
        int $quantity,
        string $receiver,
        string $sender,
        string $actor
    ): self
    {
        $requested = $this->state[$receiver]['requested'] ?? 0;
        $transferred = $this->state[$receiver]['transferred'] ?? 0;

        $pending = $requested - $transferred;

        if($pending < $quantity) throw new InvalidDomainException('Cannot transfer more than the requested quantity', ['request' => 'Cannot transfer more than the requested quantityt']);

        $event = new ProductTransferred(
            $this->uuid(),
            $quantity,
            $receiver,
            $sender,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function receive(
        int $quantity,
        string $receiver,
        string $actor
    ): self
    {
        $event = new ProductReceived(
            $this->uuid(),
            $quantity,
            $receiver,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function applyProductRequested(ProductRequested $event): void
    {
        $this->state[$event->receiver]['requested'] = $event->quantity;
    }

    public function applyProductTransferred(ProductTransferred $event): void
    {
        $transferred = $this->state[$event->receiver]['transferred'] ?? 0;
        $this->state[$event->receiver]['transferred'] = $transferred + $event->quantity;
    }

    public function applyEventsProductReceived(ProductReceived $event): void
    {
        $requested = $this->state[$event->receiver]['requested'] ?? 0;
        $this->state[$event->receiver]['requested'] = max(0, ($requested - $event->quantity));

        $transferred = $this->state[$event->receiver]['transferred'] ?? 0;
        $this->state[$event->receiver]['transferred'] = max(0, ($transferred - $event->quantity));
    }
}
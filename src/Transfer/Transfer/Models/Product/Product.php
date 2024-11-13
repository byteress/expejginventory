<?php

namespace Transfer\Models\Product;

use Transfer\Models\Aggregate;
use TransferContracts\Events\ProductReceived;
use TransferContracts\Events\ProductRequested;
use TransferContracts\Events\ProductTransferred;
use TransferContracts\Exceptions\ErrorCode;
use TransferContracts\Exceptions\InvalidDomainException;

class Product extends Aggregate
{
    /** @var array<string, array<string, int>> */
    private array $state = [];

    /**
     * @var array<string, int>
     */
    private array $transferred = [];

    /**
     * @throws InvalidDomainException
     */
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

    /**
     * @throws InvalidDomainException
     */
    public function transfer(
        int $quantity,
        string $receiver,
        string $sender,
        string $actor,
        string $transferId
    ): self
    {
        if($receiver == $sender) throw new InvalidDomainException('Cannot transfer to yourself', ['request' => 'Cannot transfer to yourself'], ErrorCode::CANNOT_TRANSFER_TO_SELF->value, ['product_id' => $this->uuid()]);

        $requested = $this->state[$receiver]['requested'] ?? 0;

        if($requested < $quantity) throw new InvalidDomainException('Cannot transfer more than the requested quantity', ['request' => 'Cannot transfer more than the requested quantity'], ErrorCode::EXCEEDED_REQUESTED_QUANTITY->value, ['product_id' => $this->uuid()]);

        $event = new ProductTransferred(
            $this->uuid(),
            $quantity,
            $receiver,
            $sender,
            $actor,
            $transferId
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function receive(
        string $transferId,
        int $received,
        int $damaged,
        int $lacking,
        string $actor
    ): self
    {
        if(!isset($this->transferred[$transferId])) throw new InvalidDomainException(
            'Transfer already completed.',
            ['transfer' => 'Transfer already completed.']
        );

        if($this->transferred[$transferId] != ($received + $damaged + $lacking)) throw new InvalidDomainException(
            'Received and damaged should be equal to the transferred quantity.',
            ['transfer' => 'Received and damaged should be equal to the transferred quantity.'],
            ErrorCode::EXCEEDED_TRANSFERRED_QUANTITY->value,
            ['product_id' => $this->uuid()]
        );

        $event = new ProductReceived(
            $transferId,
            $this->uuid(),
            $received,
            $damaged,
            $actor,
            $lacking
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
        $requested = $this->state[$event->receiver]['requested'] ?? 0;
        $this->state[$event->receiver]['requested'] = $requested - $event->quantity;

        $this->transferred[$event->transferId] = $event->quantity;
    }

    public function applyEventsProductReceived(ProductReceived $event): void
    {
        unset($this->transferred[$event->transferId]);
    }
}

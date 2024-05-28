<?php

namespace StockManagement\Models\Product;

use StockManagement\Models\Aggregate;
use StockManagementContracts\Events\ProductReceived;
use StockManagementContracts\Events\ProductReleased;
use StockManagementContracts\Events\ProductReserved;
use StockManagementContracts\Exceptions\InvalidDomainException;

class Product extends Aggregate
{
    /** @var array<string, int> */
    public array $available = [];

    /** @var array<string, int> */
    public array $reserved = [];

    public function receive(
        string $branchId,
        int $quantity,
        string $actor
    ): self
    {
        $event = new ProductReceived(
            $this->uuid(),
            $branchId,
            $quantity,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function reserve(
        string $reservationId,
        string $branchId,
        int $quantity,
        string $actor
    ): self
    {
        $available = $this->available[$branchId] ?? 0;

        if($available < $quantity) throw new InvalidDomainException('Insufficient quantity on hand.', ['reserve' => 'Insufficient quantity on hand.']);

        $event = new ProductReserved(
            $this->uuid(),
            $reservationId,
            $branchId,
            $quantity,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function release(
        string $branchId,
        int $quantity,
        string $actor
    ): self
    {
        $available = $this->available[$branchId] ?? 0;

        if($available < $quantity) throw new InvalidDomainException('Insufficient quantity on hand.', ['reserve' => 'Insufficient quantity on hand.']);

        $event = new ProductReleased(
            $this->uuid(),
            $branchId,
            $quantity,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function applyProductReceived(ProductReceived $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity + $event->quantity;
    }

    public function applyProductReserved(ProductReserved $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity - $event->quantity;

        $oldQuantity = $this->reserved[$event->branchId] ?? 0;
        $this->reserved[$event->branchId] = $oldQuantity - $event->quantity;
    }

    public function applyProductReleased(ProductReleased $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity - $event->quantity;
    }
}
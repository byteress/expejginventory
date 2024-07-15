<?php

namespace StockManagement\Models\Product;

use StockManagement\Models\Aggregate;
use StockManagementContracts\Events\DamagedProductReceived;
use StockManagementContracts\Events\ProductReceived;
use StockManagementContracts\Events\ProductReleased;
use StockManagementContracts\Events\ProductReserved;
use StockManagementContracts\Events\ReservationCancelled;
use StockManagementContracts\Events\ProductSetAsDamaged;
use StockManagementContracts\Exceptions\InvalidDomainException;

class Product extends Aggregate
{
    /** @var array<string, int> */
    public array $available = [];

    /** @var array<string, int> */
    public array $reserved = [];

    /** @var array<string, int> */
    public array $damaged = [];

    /** @var array<string, ProductReserved> */
    public array $reservations = [];

    public function receive(
        string $branchId,
        int    $quantity,
        string $actor,
        ?string $batchId = null
    ): self
    {
        $event = new ProductReceived(
            $this->uuid(),
            $branchId,
            $quantity,
            $actor,
            $batchId
        );

        $this->recordThat($event);

        return $this;
    }

    public function receiveDamaged(
        string $branchId,
        int $quantity,
        string $actor
    ): self
    {
        $event = new DamagedProductReceived(
            $this->uuid(),
            $branchId,
            $quantity,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function setDamaged(
        string $branchId,
        int $quantity,
        string $actor
    ): self
    {
        $available = $this->available[$branchId] ?? 0;

        if($available < $quantity) throw new InvalidDomainException('Insufficient quantity on hand.', ['reserve' => 'Insufficient quantity on hand.']);

        $event = new ProductSetAsDamaged(
            $this->uuid(),
            $branchId,
            $quantity,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function reserve(
        string $reservationId,
        string $branchId,
        int $quantity,
        string $actor,
        bool $advanceOrder
    ): self
    {
        $available = $this->available[$branchId] ?? 0;

        if(!$advanceOrder && $available < $quantity) throw new InvalidDomainException('Insufficient quantity on hand.', ['reserve' => 'Insufficient quantity on hand.']);

        $event = new ProductReserved(
            $this->uuid(),
            $reservationId,
            $branchId,
            $quantity,
            $actor,
            $advanceOrder
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function cancelReservation(
        string $reservationId,
        string $actor
    ): self
    {
        if(!array_key_exists($reservationId, $this->reservations)) throw new InvalidDomainException('Reservation not found.', ['reserve' => 'Reservation not found.']);

        $reservation = $this->reservations[$reservationId];

        $event = new ReservationCancelled(
            $this->uuid(),
            $reservationId,
            $reservation->branchId,
            $reservation->quantity,
            $actor,
            $reservation->advancedOrder
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
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

    public function applyDamagedProductReceived(DamagedProductReceived $event): void
    {
        $oldQuantity = $this->damaged[$event->branchId] ?? 0;
        $this->damaged[$event->branchId] = $oldQuantity + $event->quantity;
    }

    public function applyProductSetAsDamaged(ProductSetAsDamaged $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity - $event->quantity;

        $oldQuantity = $this->damaged[$event->branchId] ?? 0;
        $this->damaged[$event->branchId] = $oldQuantity + $event->quantity;
    }

    public function applyProductReserved(ProductReserved $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity - $event->quantity;

        $oldQuantity = $this->reserved[$event->branchId] ?? 0;
        $this->reserved[$event->branchId] = $oldQuantity + $event->quantity;

        $this->reservations[$event->reservationId] = $event;
    }

    public function applyReservationCancelled(ReservationCancelled $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity + $event->quantity;

        $oldQuantity = $this->reserved[$event->branchId] ?? 0;
        $this->reserved[$event->branchId] = $oldQuantity - $event->quantity;

        unset($this->reservations[$event->reservationId]);
    }

    public function applyProductReleased(ProductReleased $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity - $event->quantity;
    }
}

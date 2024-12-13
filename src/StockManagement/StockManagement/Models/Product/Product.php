<?php

namespace StockManagement\Models\Product;

use StockManagement\Models\Aggregate;
use StockManagementContracts\Events\DamagedProductReceived;
use StockManagementContracts\Events\ProductAdjusted;
use StockManagementContracts\Events\ProductReceived;
use StockManagementContracts\Events\ProductReleased;
use StockManagementContracts\Events\ProductReserved;
use StockManagementContracts\Events\ProductReturned;
use StockManagementContracts\Events\ReservationCancelled;
use StockManagementContracts\Events\ProductSetAsDamaged;
use StockManagementContracts\Events\ReservationConfirmed;
use StockManagementContracts\Events\ReservationFulfilled;
use StockManagementContracts\Exceptions\ErrorCode;
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
    /** @var array<string, ProductReserved> */
    public array $expiringReservations = [];

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
        bool $advanceOrder,
        bool $hasExpiry
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
            $advanceOrder,
            $hasExpiry
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function confirmReservation(string $reservationId): self
    {
        if(!array_key_exists($reservationId, $this->expiringReservations))
            throw new InvalidDomainException('Reservation not found.', [
                'reserve' => 'Reservation not found.'
            ], ErrorCode::RESERVATION_NOT_FOUND->value);

        $reservation = $this->expiringReservations[$reservationId];

        $event = new ReservationConfirmed(
            $this->uuid(),
            $reservationId,
            $reservation->branchId
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @param string $reservationId
     * @param string|null $actor
     * @param bool $expired
     * @return Product
     * @throws InvalidDomainException
     */
    public function cancelReservation(
        string  $reservationId,
        ?string $actor,
        bool $expired = false
    ): self
    {
        if(!array_key_exists($reservationId, $this->reservations) && !array_key_exists($reservationId, $this->expiringReservations)) {
            if($expired) return $this;

            throw new InvalidDomainException('Reservation not found.', [
                'reserve' => 'Reservation not found.'
            ], ErrorCode::RESERVATION_NOT_FOUND->value);
        }

        if(array_key_exists($reservationId, $this->reservations) && !array_key_exists($reservationId, $this->expiringReservations) && $expired)
            return $this;

        $reservation = $this->reservations[$reservationId] ?? $this->expiringReservations[$reservationId];

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
    public function fulfillReservation(
        string $reservationId
    ): self
    {
        if(!array_key_exists($reservationId, $this->reservations)) throw new InvalidDomainException('Reservation not found.', ['reserve' => 'Reservation not found.']);

        $reservation = $this->reservations[$reservationId];

        $available = ($reservation->advancedOrder) ? $this->available[$reservation->branchId] ?? 0 : $this->reserved[$reservation->branchId] ?? 0;
        if($available < $reservation->quantity) throw new InvalidDomainException('Insufficient quantity on hand.', ['reserve' => 'Insufficient quantity on hand.']);

        $event = new ReservationFulfilled(
            $this->uuid(),
            $reservationId,
            $reservation->branchId,
            $reservation->quantity,
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

    public function return(
        string $branchId,
        int    $quantity,
        string $actor,
        string $reservationId
    ): self
    {
        if(array_key_exists($reservationId, $this->reservations)){
            $this->recordThat(new ReservationCancelled(
                $this->uuid(),
                $reservationId,
                $branchId,
                $quantity,
                $actor,
                $this->reservations[$reservationId]->advancedOrder
            ));

            return $this;
        }

        $event = new ProductReturned(
            $this->uuid(),
            $branchId,
            $quantity,
            $actor
        );

        $this->recordThat($event);
        return $this;
    }

    public function adjust(
        string $branchId,
        ?int    $available,
        ?int $damaged,
        string $actor
    ): self
    {
        $event = new ProductAdjusted(
            $this->uuid(),
            $branchId,
            $available,
            $damaged,
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
        $this->reservations[$event->reservationId] = $event;
        if($event->hasExpiry) $this->expiringReservations[$event->reservationId] = $event;
        if($event->advancedOrder) return;

        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity - $event->quantity;

        $oldQuantity = $this->reserved[$event->branchId] ?? 0;
        $this->reserved[$event->branchId] = $oldQuantity + $event->quantity;
    }

    public function applyReservationConfirmed(ReservationConfirmed $event): void
    {
        unset($this->expiringReservations[$event->reservationId]);
    }

    public function applyReservationCancelled(ReservationCancelled $event): void
    {
        unset($this->reservations[$event->reservationId]);
        if(array_key_exists($event->reservationId, $this->expiringReservations)) unset($this->expiringReservations[$event->reservationId]);
        if($event->advancedOrder) return;

        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity + $event->quantity;

        $oldQuantity = $this->reserved[$event->branchId] ?? 0;
        $this->reserved[$event->branchId] = $oldQuantity - $event->quantity;
    }

    public function applyReservationFulfilled(ReservationFulfilled $event): void
    {
        if($event->advancedOrder){
            $oldQuantity = $this->available[$event->branchId] ?? 0;
            $this->available[$event->branchId] = $oldQuantity - $event->quantity;
        }else{
            $oldQuantity = $this->reserved[$event->branchId] ?? 0;
            $this->reserved[$event->branchId] = $oldQuantity - $event->quantity;
        }

        unset($this->reservations[$event->reservationId]);
    }

    public function applyProductReleased(ProductReleased $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity - $event->quantity;
    }

    public function applyProductReturned(ProductReturned $event): void
    {
        $oldQuantity = $this->available[$event->branchId] ?? 0;
        $this->available[$event->branchId] = $oldQuantity + $event->quantity;
    }

    public function applyProductAdjusted(ProductAdjusted $event): void
    {
        if($event->available) $this->available[$event->branchId] = $event->available;
        if($event->damaged) $this->damaged[$event->branchId] = $event->damaged;
    }
}

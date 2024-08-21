<?php

namespace Delivery\Models\Order\Entities;

class Item
{
    private int $toShip = 0;
    private int $outForDelivery = 0;
    private int $delivered = 0;

    public function __construct(
        private readonly string $productId,
        private readonly string $title,
        private readonly int $quantity,
        private readonly string $reservationId,
    )
    {
    }

    public function setToShip(int $quantity): void
    {
        $this->toShip = $quantity;
    }

    public function getToShip(): int
    {
        return $this->toShip;
    }

    public function getOutForDelivery(): int
    {
        return $this->outForDelivery;
    }

    public function getDelivered(): int
    {
        return $this->delivered;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return array{'productId': string, 'title': string, 'quantity': int, 'reservationId': string}
     */
    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'title' => $this->title,
            'quantity' => $this->quantity,
            'reservationId' => $this->reservationId,
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getReservationId(): string
    {
        return $this->reservationId;
    }
}

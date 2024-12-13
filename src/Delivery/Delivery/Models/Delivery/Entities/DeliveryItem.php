<?php

namespace Delivery\Models\Delivery\Entities;

class DeliveryItem
{
    private int $delivered = 0;

    public function __construct(
        private readonly string $orderId,
        private readonly string $productId,
        private readonly int    $quantity,
    )
    {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
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
     * @return array{'orderId': string, 'productId': string, 'quantity': int}
     */
    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId,
            'productId' => $this->productId,
            'quantity' => $this->quantity
        ];
    }

    public function setDelivered(int $delivered): void
    {
        $this->delivered = $delivered;
    }

    public function getDelivered(): int
    {
        return $this->delivered;
    }
}

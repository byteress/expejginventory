<?php

namespace Delivery\Models\Delivery;

use Delivery\Models\Delivery\Entities\DeliveryItem;
use Delivery\Models\Delivery\Enums\DeliveryStatus;
use DeliveryContracts\Exceptions\InvalidDomainException;

class State
{
    /**
     * @var DeliveryItem[]
     */
    private array $items;
    private DeliveryStatus $status = DeliveryStatus::OUT_FOR_DELIVERY;

    public function __construct()
    {
    }

    /**
     * @return DeliveryItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param DeliveryItem[] $items
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getStatus(): DeliveryStatus
    {
        return $this->status;
    }

    public function setStatus(DeliveryStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @throws InvalidDomainException
     */
    public function getItem(string $orderId, string $productId): DeliveryItem
    {
        $return = null;
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId && $item->getOrderId() === $orderId) {
                $return = $item;
                break;
            }
        }

        if(!$return) throw new InvalidDomainException("Item $productId not found.", [
            'item' => "Item $productId not found."
        ]);

        return $return;
    }

    /**
     * @param DeliveryItem[] $items
     * @return bool
     * @throws InvalidDomainException
     */
    public function isComplete(array $items): bool
    {
        foreach ($items as $item){
            $i = $this->getItem($item->getOrderId(), $item->getProductId());
            if($i->getQuantity() !== $item->getQuantity())
                return false;
        }

        return true;
    }

    public function setDeliveredItems(DeliveryItem $item): self
    {
        foreach ($this->items as $i) {
            if ($i->getProductId() === $item->getProductId() && $i->getOrderId() === $item->getOrderId()) {
                $i->setDelivered($item->getQuantity());
                break;
            }
        }

        return $this;
    }
}

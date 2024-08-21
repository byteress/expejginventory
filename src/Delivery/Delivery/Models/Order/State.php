<?php

namespace Delivery\Models\Order;

use Delivery\Models\Order\Entities\Item;
use DeliveryContracts\Exceptions\InvalidDomainException;

class State
{
    /**
     * @var Item[]
     */
    private array $items = [];

    public function __construct()
    {
    }

    /**
     * @param Item[] $items
     * @return self
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function setItemToShip(string $productId, int $quantity): self
    {
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId) {
                $item->setToShip($quantity);
                break;
            }
        }

        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @throws InvalidDomainException
     */
    public function getItem(string $productId): Item
    {
        $return = null;
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId) {
                $return = $item;
                break;
            }
        }

        if(!$return) throw new InvalidDomainException("Item $productId not found.", [
            'item' => "Item $productId not found."
        ]);

        return $return;
    }
}

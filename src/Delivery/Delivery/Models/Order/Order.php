<?php

namespace Delivery\Models\Order;

use Delivery\Models\Aggregate;
use Delivery\Models\Order\Entities\Item;
use DeliveryContracts\Events\DeliveryOrderPlaced;
use DeliveryContracts\Events\ItemConfirmedForDelivery;
use DeliveryContracts\Events\ItemDelivered;
use DeliveryContracts\Events\ItemShipped;
use DeliveryContracts\Events\PickupOrderPlaced;
use DeliveryContracts\Exceptions\InvalidDomainException;

class Order extends Aggregate
{
    private State $state;

    public function __construct()
    {
        $this->state = new State();
    }

    /**
     * @param Item[] $items
     * @param string $branchId
     * @return self
     */
    public function placePickupOrder(array $items, string $branchId): self
    {
        $itemsArray = array_map(function ($item) {
            return $item->toArray();
        }, $items);

        $event = new PickupOrderPlaced($this->uuid(), $itemsArray, $branchId);

        $this->recordThat($event);
        return $this;
    }

    /**
     * @param Item[] $items
     * @param int $deliveryFee
     * @param string $address
     * @param string $branchId
     * @return self
     */
    public function placeDeliveryOrder(array $items, int $deliveryFee, string $address, string $branchId): self
    {
        $itemsArray = array_map(function ($item) {
            return $item->toArray();
        }, $items);

        $event = new DeliveryOrderPlaced($this->uuid(), $itemsArray, $deliveryFee, $address, $branchId);

        $this->recordThat($event);
        return $this;
    }

    public function confirmItemForDelivery(string $productId, string $reservationId, int $quantity): self
    {
        $event = new ItemConfirmedForDelivery($this->uuid(), $productId, $reservationId, $quantity);
        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function shipItem(string $productId, int $quantity): self
    {
        $item = $this->state->getItem($productId);
        if($item->getToShip() < $quantity){
            throw new InvalidDomainException('Quantity is more than the quantity to ship.', [
                'quantity' => 'Quantity is more than the quantity to ship.'
            ]);
        }

        $event = new ItemShipped($this->uuid(), $productId, $quantity);
        $this->recordThat($event);

        return $this;
    }

    public function deliverItem(string $productId, int $success, int $failure): self
    {
        $this->recordThat(new ItemDelivered($this->uuid(), $productId, $success, $failure));

        return $this;
    }

    public function state(): State
    {
        return $this->state;
    }

    /**
     * @param array{'productId': string, 'title': string, 'quantity': int, 'reservationId': string} $item
     * @return Item
     */
    public static function arrayToItem(array $item): Item
    {
        return new Item($item['productId'], $item['title'], $item['quantity'], $item['reservationId']);
    }

    /**
     * ----------------------------------------------------------------------------------------------------------------
     */

    public function applyPickupOrderPlaced(PickupOrderPlaced $event): void
    {
        $items = array_map(function ($item) { return $this->arrayToItem($item); }, $event->items);
        $this->state->setItems($items);
    }

    public function applyDeliveryOrderPlaced(DeliveryOrderPlaced $event): void
    {
        $items = array_map(function ($item) { return $this->arrayToItem($item); }, $event->items);
        $this->state->setItems($items);
    }

    public function applyItemConfirmedForDelivery(ItemConfirmedForDelivery $event): void
    {
        $this->state->setItemToShip($event->productId, $event->quantity);
    }

    public function applyItemShipped(ItemShipped $event): void
    {
        $this->state->setItemOutForDelivery($event->productId, $event->quantity);
    }

    public function applyItemDelivered(ItemDelivered $event): void
    {
        $this->state->setItemDelivered($event->productId, $event->success, $event->failure);
    }
}

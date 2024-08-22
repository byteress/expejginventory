<?php

namespace Delivery\Models\Delivery;

use Delivery\Models\Aggregate;
use Delivery\Models\Delivery\Entities\DeliveryItem;
use Delivery\Models\Delivery\Enums\DeliveryStatus;
use DeliveryContracts\Events\DeliveryAssigned;
use DeliveryContracts\Events\DeliveryCompleted;
use DeliveryContracts\Events\DeliveryItemDelivered;
use DeliveryContracts\Events\DeliveryPartiallyCompleted;
use DeliveryContracts\Exceptions\InvalidDomainException;

class Delivery extends Aggregate
{
    private State $state;

    public function __construct()
    {
        $this->state = new State();
    }

    /**
     * @param string $driver
     * @param string $truck
     * @param string $branchId
     * @param DeliveryItem[] $items
     * @param string|null $notes
     * @return self
     */
    public function assign(string $driver, string $truck, string $branchId, array $items, ?string $notes): self
    {
        $itemsArray = array_map(function ($item) {
            return $item->toArray();
        }, $items);

        $event = new DeliveryAssigned($this->uuid(), $driver, $truck, $branchId, $itemsArray, $notes);
        $this->recordThat($event);

        return $this;
    }

    /**
     * @param DeliveryItem[] $items
     * @param string $branch
     * @return self
     * @throws InvalidDomainException
     */
    public function markAsComplete(array $items, string $branch): self
    {
        if($this->state->getStatus() != DeliveryStatus::OUT_FOR_DELIVERY)
            throw new InvalidDomainException('Cannot mark as complete', [
                'status' => 'Cannot mark as complete'
            ]);

        foreach ($items as $item) {
            $i = $this->state->getItem($item->getOrderId(), $item->getProductId());
            $failure = $i->getQuantity() - $item->getQuantity();

            $this->recordThat(new DeliveryItemDelivered(
                $this->uuid(),
                $item->getOrderId(),
                $item->getProductId(),
                $item->getQuantity(),
                $failure
            ));
        }

        $isComplete = $this->state->isComplete($items);
        if ($isComplete) {
            $this->recordThat(new DeliveryCompleted($this->uuid(), $branch));
            $this->persist();

            return $this;
        }

        $this->recordThat(new DeliveryPartiallyCompleted($this->uuid(), $branch));
        $this->persist();

        return $this;
    }

    public function state(): State
    {
        return $this->state;
    }

    /**
     * @param array{'orderId': string, 'productId': string, 'quantity': int} $item
     * @return DeliveryItem
     */
    public static function arrayToItem(array $item): DeliveryItem
    {
        return new DeliveryItem($item['orderId'], $item['productId'], $item['quantity']);
    }

    /**
     * -----------------------------------------------------------------------------------------------------------------
     */

    public function applyDeliveryAssigned(DeliveryAssigned $event): void
    {
        $items = array_map(function ($item) { return $this->arrayToItem($item); }, $event->items);
        $this->state->setItems($items);
    }

    public function applyDeliveryItemDelivered(DeliveryItemDelivered $event): void
    {
        $this->state->setDeliveredItems(new DeliveryItem($event->orderId, $event->productId, $event->success));
    }

    public function applyDeliveryCompleted(DeliveryCompleted $event): void
    {
        $this->state->setStatus(DeliveryStatus::COMPLETED);
    }

    public function applyDeliveryPartiallyCompleted(DeliveryPartiallyCompleted $event): void
    {
        $this->state->setStatus(DeliveryStatus::PARTIALLY_COMPLETED);
    }
}

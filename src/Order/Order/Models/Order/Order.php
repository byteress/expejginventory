<?php

namespace Order\Models\Order;

use Order\Models\Aggregate;
use OrderContracts\Events\ItemAdded;
use OrderContracts\Events\ItemPriceUpdated;
use OrderContracts\Events\ItemQuantityUpdated;
use OrderContracts\Events\ItemRemoved;
use OrderContracts\Events\OrderAuthorized;
use OrderContracts\Events\OrderPlaced;

class Order extends Aggregate
{
    /** @var array<string> */
    private array $productNeedsAuthorization = [];
    private bool $authorizationRequired = false;

    /** @param array<array{
     *      'productId': string,
     *      'title': string,
     *      'quantity': int,
     *      'price': int,
     *      'reservationId': string
     * }> $items */
    public function place(array $items, string $customerId, string $assistantId, string $branchId, string $orderType): self
    {
        $event = new OrderPlaced(
            $this->uuid(),
            $customerId,
            $assistantId,
            $branchId,
            $items,
            $orderType
        );

        $this->recordThat($event);

        return $this;
    }

    public function addItem(
        string $productId,
        string $title,
        int $price,
        int $quantity,
        string $reservationId
    ): self
    {
        $event = new ItemAdded($this->uuid(), $productId, $title, $price, $quantity, $reservationId);
        $this->recordThat($event);

        return $this;
    }

    public function updateItemPrice(string $productId, int $newPrice, bool $authorizationRequired): self
    {
        $auth = false;
        if($authorizationRequired){
            $auth = true;
        }else{
            if(in_array($productId, $this->productNeedsAuthorization) && count($this->productNeedsAuthorization) == 1) $auth = false;
        }

        $event = new ItemPriceUpdated($this->uuid(), $productId, $newPrice, $authorizationRequired, $auth);
        $this->recordThat($event);

        return $this;
    }

    public function updateItemQuantity(string $productId, int $newQuantity, string $reservationId): self
    {
        $event = new ItemQuantityUpdated($this->uuid(), $productId, $newQuantity, $reservationId);
        $this->recordThat($event);

        return $this;
    }

    public function removeItem(string $productId): self
    {
        $authorizationRequired = $this->authorizationRequired;
        if(in_array($productId, $this->productNeedsAuthorization) && count($this->productNeedsAuthorization) == 1) $authorizationRequired = false;

        $event = new ItemRemoved($this->uuid(), $productId, $authorizationRequired);
        $this->recordThat($event);

        return $this;
    }

    public function authorize(string $actor): self
    {
        $event = new OrderAuthorized($this->uuid(), $actor);
        $this->recordThat($event);

        return $this;
    }

    public function applyItemPriceUpdated(ItemPriceUpdated $event): void
    {
        $this->authorizationRequired = $event->orderAuthRequired;
        if(!$event->itemAuthRequred){
            if (($key = array_search($event->productId, $this->productNeedsAuthorization)) !== false) {
                unset($this->productNeedsAuthorization[$key]);
            }

            return;
        }

        if(!in_array($event->productId, $this->productNeedsAuthorization)) $this->productNeedsAuthorization[] = $event->productId;

    }

    public function applyItemRemoved(ItemRemoved $event): void
    {
        if (($key = array_search($event->productId, $this->productNeedsAuthorization)) !== false) {
            unset($this->productNeedsAuthorization[$key]);
        }

        $this->authorizationRequired = $event->authorizationRequired;
    }

    public function applyOrderAuthorized(OrderAuthorized $event): void
    {
        $this->productNeedsAuthorization = [];
        $this->authorizationRequired = false;
    }
}

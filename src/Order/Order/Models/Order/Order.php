<?php

namespace Order\Models\Order;

use DateTime;
use Order\Models\Aggregate;
use OrderContracts\Events\ItemAdded;
use OrderContracts\Events\ItemPriceUpdated;
use OrderContracts\Events\ItemQuantityUpdated;
use OrderContracts\Events\ItemRemoved;
use OrderContracts\Events\OrderAuthorized;
use OrderContracts\Events\OrderCancelled;
use OrderContracts\Events\OrderDeleted;
use OrderContracts\Events\OrderDelivered;
use OrderContracts\Events\OrderPlaced;
use OrderContracts\Events\OrderRefunded;
use OrderContracts\Events\OrderSetAsPrevious;
use OrderContracts\Events\OrderShipped;
use OrderContracts\Exceptions\InvalidDomainException;

class Order extends Aggregate
{
    /** @var array<string> */
    private array $productNeedsAuthorization = [];
    private bool $authorizationRequired = false;
    /**
     * @var array<array{
     *       'productId': string,
     *       'title': string,
     *       'quantity': int,
     *       'price': int,
     *       'originalPrice': int,
     *       'reservationId': string
     *  }>
     */
    private array $lineItems = [];
    private bool $previous = false;
    private ?DateTime $installmentStartDate = null;
    private int $status = 0;
    public ?DateTime $cancellationDate = null;
    public ?string $cancelledOrder = null;

    /** @param array<array{
     *      'productId': string,
     *      'title': string,
     *      'quantity': int,
     *      'price': int,
     *      'originalPrice': int,
     *      'reservationId': string
     * }> $items */
    public function place(
        array $items,
        string $customerId,
        string $assistantId,
        string $branchId,
        string $orderType,
        ?string $cancelledOrder
    ): self
    {
        $event = new OrderPlaced(
            $this->uuid(),
            $customerId,
            $assistantId,
            $branchId,
            $items,
            $orderType,
            $cancelledOrder
        );

        $this->recordThat($event);

        return $this;
    }

    public function addItem(
        string $productId,
        string $title,
        int $price,
        int $originalPrice,
        int $quantity,
        string $reservationId,
        string $priceType
    ): self
    {
        $event = new ItemAdded($this->uuid(), $productId, $title, $price, $quantity, $reservationId, $priceType, $originalPrice);
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

    public function ship(string $shippingId, string $driver, string $truck, ?string $note = null): self
    {
        $event = new OrderShipped(
            $this->uuid(),
            $shippingId,
            $driver,
            $truck,
            $note
        );

        $this->recordThat($event);

        return $this;
    }

    public function markAsDelivered(): self
    {
        $event = new OrderDelivered(
            $this->uuid(),
        );

        $this->recordThat($event);

        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function cancel(string $actor, ?string $notes): self
    {
        if($this->status == 3) throw new InvalidDomainException('Order already canceled', [
            'order' => 'Order already canceled.'
        ]);

        if($this->status == 4) throw new InvalidDomainException('Order already canceled', [
            'order' => 'Cannot cancel order.'
        ]);

        $event = new OrderCancelled($this->uuid(), $actor, $notes);

        $this->recordThat($event);
        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function delete(string $actor, ?string $notes): self
    {
        if($this->status == 5) throw new InvalidDomainException('Order already deleted', [
            'order' => 'Order already deleted.'
        ]);

        if($this->status == 4 || $this->status == 3) throw new InvalidDomainException('Order already canceled', [
            'order' => 'Cannot cancel order.'
        ]);

        $event = new OrderDeleted($this->uuid(), $actor, $notes);

        $this->recordThat($event);
        return $this;
    }

    /**
     * @throws InvalidDomainException
     */
    public function refund(string $actor, ?string $notes): self
    {
        if($this->status == 4) throw new InvalidDomainException('Order already canceled', [
            'order' => 'Order already refunded.'
        ]);

        if($this->status == 3) throw new InvalidDomainException('Order already canceled', [
            'order' => 'Cannot refund order.'
        ]);

        $event = new OrderRefunded($this->uuid(), $actor, $notes);

        $this->recordThat($event);
        return $this;
    }

    public function setAsPrevious(?DateTime $installmentStartDate): self
    {
        $this->recordThat(new OrderSetAsPrevious($this->uuid(), $installmentStartDate));

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

    public function applyOrderPlaced(OrderPlaced $event): void
    {
        $this->status = 1;
        $this->cancelledOrder = $event->cancelledOrder;
    }

    public function applyOrderCancelled(OrderCancelled $event): void
    {
        $this->status = 3;
        $this->cancellationDate = $event->createdAt()?->toDateTime();
    }

    public function applyOrderRefunded(OrderRefunded $event): void
    {
        $this->status = 4;
    }

    public function applyOrderDeleted(OrderDeleted $event): void
    {
        $this->status = 5;
    }

    public function applyOrderSetAsPrevious(OrderSetAsPrevious $event): void
    {
        $this->previous = true;
        $this->installmentStartDate = $event->installmentStartDate;
    }

    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    public function isPrevious(): bool
    {
        return $this->previous;
    }

    public function getInstallmentStartDate(): ?DateTime
    {
        return $this->installmentStartDate;
    }
}

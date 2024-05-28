<?php

namespace Transfer\Models\Transfer;

use Transfer\Models\Aggregate;
use TransferContracts\Events\Contributed;
use TransferContracts\Events\TransferRequested;
use TransferContracts\Exceptions\InvalidDomainException;

class Transfer extends Aggregate
{
    /** @var array<string, int> */
    public array $products = [];

    /** @var array<string, array<string, int>> */
    private array $contributions = [];

    /**
     * @param array<string, int> $products
     */
    public function request(array $products, string $branchId, string $actor): self
    {
        $event = new TransferRequested($this->uuid(), $products, $branchId, $actor);

        $this->recordThat($event);

        return $this;
    }

    public function contribute(
        string $productId,
        int $quantity,
        string $reservationId,
        string $branchId,
        string $actor
    ): self
    {
        $requestedQuantity = $this->products[$productId];
        $contributedQuantity = $this->getContributedQuantity($productId);
        $toSatisfyQuantity = $requestedQuantity - $contributedQuantity;

        if($toSatisfyQuantity < $quantity) throw new InvalidDomainException('Specified quantity is more than the requested.', ['quantity' => 'Specified quantity is more than the requested.']);

        $event = new Contributed(
            $this->uuid(),
            $productId,
            $quantity,
            $reservationId,
            $branchId,
            $actor
        );

        $this->recordThat($event);

        return $this;
    }

    public function applyTransferRequested(TransferRequested $event): void
    {
        $this->products = $event->products;
    }

    public function applyContributed(Contributed $event): void
    {
        $curentQuantity = $this->contributions[$event->branchId][$event->productId] ?? 0;
        $this->contributions[$event->branchId][$event->productId] = $curentQuantity + $event->quantity;
    }

    private function getContributedQuantity(string $productId): int
    {
        $quantity = 0;
        foreach($this->contributions as $key => $value){
            $quantity = $quantity + ($value[$productId] ?? 0);
        }

        return $quantity;
    }
}
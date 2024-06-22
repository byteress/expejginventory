<?php

namespace Order\Models\Product;

use Order\Models\Aggregate;
use OrderContracts\Events\PriceSet;

class Product extends Aggregate
{
    private int $regular;
    private int $sale;

    public function setPrice(int $regular, int $sale): self
    {
        $event = new PriceSet(
            $this->uuid(),
            $regular,
            $sale
        );

        $this->recordThat($event);

        return $this;
    }

    public function applyPriceSet(PriceSet $event): void
    {
        $this->regular = $event->regularPrice;
        $this->sale = $event->salePrice;
    }

    public function regularPrice(): int
    {
        return $this->regular;
    }

    public function salePrice(): int
    {
        return $this->sale;
    }

    public function needsAuthorization(int $price): bool
    {
        $authorization = false;
        if ($this->sale >= $price) {
            $diff = $this->sale - $price;
            $rate = ($diff / $this->sale) * 100;
            if ($rate > 5) $authorization = true;
        }

        return $authorization;
    }
}

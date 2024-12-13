<?php

namespace Order\Models\Product;

use Illuminate\Support\Facades\DB;
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

    public function needsAuthorization(int $price, string $priceType = 'regular_price'): bool
    {
        $product = \ProductManagement\Models\Product::find($this->uuid());

        if(!$product) return false;

        $authorization = false;
        $basePrice = $product->$priceType;
        if ($basePrice >= $price) {
            $diff = $basePrice - $price;
            $rate = ($diff / $basePrice) * 100;
            if ($rate > 5) $authorization = true;
        }

        return $authorization;
    }
}

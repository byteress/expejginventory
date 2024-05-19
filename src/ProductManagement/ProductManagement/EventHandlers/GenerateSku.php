<?php

namespace ProductManagement\EventHandlers;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use ProductManagement\Models\Product;
use ProductManagement\Queries\LastSku\LastSkuQuery;
use ProductManagementContracts\Events\ProductCreated;

class GenerateSku implements ShouldQueue
{
    public function __construct(private LastSkuQuery $lastSkuQuery)
    {

    }

    public function handleProductCreated(ProductCreated $event): void
    {
        $lastSku = $this->lastSkuQuery->getData();

        if ($lastSku) {
            $skuNumber = (int) $lastSku->number;
            $skuNumber++;
        } else {
            $skuNumber = 1;
        }

        $skuNumber = str_pad((string) $skuNumber, 8, '0', STR_PAD_LEFT);
        $product = Product::find($event->productId);

        if(!$product) throw new Exception('Product not found');

        $product->sku_code = 'AJ';
        $product->sku_number = $skuNumber;

        $product->save();
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            ProductCreated::class,
            [GenerateSku::class, 'handleProductCreated']
        );
    }
}
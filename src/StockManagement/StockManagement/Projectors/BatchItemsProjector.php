<?php

namespace StockManagement\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use StockManagementContracts\Events\ProductReceived;

class BatchItemsProjector extends Projector
{
    public function onProductReceived(ProductReceived $event): void
    {
        if(!$event->batchId) return;

        DB::table('batch_items')
            ->insert([
                'batch_id' => $event->batchId,
                'product_id' => $event->productId,
                'quantity' => $event->quantity,
            ]);
    }
}

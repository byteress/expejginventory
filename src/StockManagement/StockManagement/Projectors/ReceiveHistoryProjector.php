<?php

namespace StockManagement\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\Enums\MetaData;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use StockManagementContracts\Events\ProductReceived;

class ReceiveHistoryProjector extends Projector
{
    public function onProductReceived(ProductReceived $event): void
    {
        DB::table('receive_history')
            ->insert([
                'product_id' => $event->productId,
                'branch_id' => $event->branchId,
                'quantity' => $event->quantity,
                'user_id' => $event->actor,
                'date' => date('Y-m-d H:i:s', strtotime($event->metaData()[MetaData::CREATED_AT]))
            ]);
    }
}
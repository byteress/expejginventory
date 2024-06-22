<?php

namespace Transfer\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use TransferContracts\Events\Contributed;
use TransferContracts\Events\ProductReceived;
use TransferContracts\Events\ProductTransferred;
use TransferContracts\Events\TransferRequested;

class TransferDetailsProjector extends Projector
{
    public function onProductTransferred(ProductTransferred $event): void
    {
        DB::table('transfer_items')
            ->insert([
                'transfer_id' => $event->transferId,
                'product_id' => $event->productId,
                'transferred' => $event->quantity,
            ]);
    }

    public function onProductReceived(ProductReceived  $event): void
    {
        DB::table('transfer_items')
            ->where('transfer_id', $event->transferId)
            ->where('product_id', $event->productId)
            ->update([
                'received' => $event->received,
                'damaged' => $event->damaged
            ]);
    }
}

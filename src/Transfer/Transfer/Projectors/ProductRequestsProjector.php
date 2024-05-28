<?php

namespace Transfer\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use TransferContracts\Events\ProductReceived;
use TransferContracts\Events\ProductRequested;
use TransferContracts\Events\ProductTransferred;

class ProductRequestsProjector extends Projector
{
    public function onProductRequested(ProductRequested $event): void
    {
        $row =  DB::table('product_requests')
            ->where('product_id', $event->productId)
            ->where('receiver', $event->receiver)
            ->first();

        if(!$row){
            DB::table('product_requests')
            ->insert([
                'product_id' => $event->productId,
                'receiver' => $event->receiver,
                'quantity' => $event->quantity,
                'date_requested' => $event->createdAt()?->toDateTimeString()
            ]);

            return;
        }

        if($row->quantity == 0){
            DB::table('product_requests')
            ->where('product_id', $event->productId)
            ->where('receiver', $event->receiver)
            ->update([
                'quantity' => $event->quantity
            ]);
        }

        
    }

    public function onProductTransferred(ProductTransferred $event): void
    {
        DB::table('product_requests')
            ->where('product_id', $event->productId)
            ->where('receiver', $event->receiver)
            ->increment('incoming', $event->quantity);
    }

    public function onProductReceived(ProductReceived $event): void
    {
        DB::table('product_requests')
            ->where('product_id', $event->productId)
            ->where('receiver', $event->receiver)
            ->update([
                'quantity' => DB::raw("GREATEST(quantity - {$event->quantity}, 0)"),
                'incoming' => DB::raw("GREATEST(incoming - {$event->quantity}, 0)")
            ]);
    }
}
<?php

namespace Transfer\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use TransferContracts\Events\Contributed;
use TransferContracts\Events\TransferRequested;

class TransferDetailsProjector extends Projector
{
    public function onTransferRequested(TransferRequested $event): void
    {
        $products = [];
        foreach($event->products as $key => $value){
            $products[$key] = [
                'quantity' => $value
            ];
        }

        DB::table('request_details')
            ->insert([
                'transfer_id' => $event->transferId,
                'data' => json_encode([
                    'products' => $products
                ])
            ]);
    }
    
    public function onContributed(Contributed $event): void
    {
        $row = DB::table('request_details')
            ->where('transfer_id', $event->transferId)
            ->first();

        if(!$row) return;

        $data = json_decode($row->data, true);

        $previous = $data['products'][$event->productId]['filled'] ?? 0;
        $data['products'][$event->productId]['filled'] = $previous + $event->quantity;

        $previous = $data['contributions'][$event->branchId][$event->productId] ?? 0;
        $data['contributions'][$event->branchId][$event->productId] = $previous + $event->quantity;

        DB::table('request_details')
            ->where('transfer_id', $event->transferId)
            ->update([
                'data' => json_encode($data)
            ]);
    }
}
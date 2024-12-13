<?php

namespace Transfer\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use TransferContracts\Events\TransferRequested;

class TransferRequestsProjector extends Projector
{
    public function onTransferRequested(TransferRequested $event): void
    {
        DB::table('transfer_requests')
            ->insert([
                'transfer_id' => $event->transferId,
                'data' => json_encode([
                    'requested_by' => $event->branchId,
                    'num_products' => count($event->products),
                    'date' => $event->createdAt()?->tz(config('app.timezone'))
                ])
            ]);
    }
}

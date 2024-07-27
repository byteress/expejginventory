<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use StockManagementContracts\IStockManagementService;

class CancelledOrderJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $orderId, private readonly string $actor)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(IStockManagementService $stockManagementService): void
    {
        $order = DB::table('orders')
            ->where('order_id', $this->orderId)
            ->first();

        if(!$order) return;

        $items = DB::table('line_items')
            ->where('order_id', $this->orderId)
            ->get();

        foreach ($items as $item){
            $result = $stockManagementService->return($item->product_id, $item->quantity, $order->branch_id, $this->actor);

            if($result->isFailure()) throw $result->getError();
        }
    }
}

<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use StockManagementContracts\IStockManagementService;

class ProcessAdvancedOrderJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public function __construct(private readonly string $productId)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(IStockManagementService $stockManagementService): void
    {
        $advancedOrder = DB::table('advanced_reservations')
            ->select(['advanced_reservations.*'])
            ->whereExists(function (Builder $query) {
                $query->select(DB::raw(1))
                    ->from('line_items')
                    ->join('orders', 'orders.order_id', '=', 'line_items.order_id')
                    ->whereRaw('line_items.reservation_id = advanced_reservations.reservation_id')
                    ->where('orders.status', '>', 0);
            })->where('advanced_reservations.product_id', $this->productId)
            ->first();

        if(!$advancedOrder) return;

        $result = $stockManagementService->fulfillReservation($this->productId, $advancedOrder->reservation_id);
        if($result->isFailure()) throw $result->getError();

        $this->batch()?->add([new ProcessAdvancedOrderJob($this->productId)]);
    }
}

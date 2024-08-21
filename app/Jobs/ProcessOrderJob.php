<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use StockManagementContracts\IStockManagementService;

class ProcessOrderJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $orderId)
    {
    }

    public function handle(IStockManagementService $stockManagementService): void
    {
        Log::info("Test log");
        $items = DB::table('line_items')
            ->where('order_id', $this->orderId)
            ->get();

        foreach ($items as $item) {
            $stockManagementService->fulfillReservation($item->product_id, $item->reservation_id);
        }
    }
}

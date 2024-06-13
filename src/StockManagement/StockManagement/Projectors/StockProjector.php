<?php

namespace StockManagement\Projectors;

use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use StockManagementContracts\Events\DamagedProductReceived;
use StockManagementContracts\Events\ProductReceived;
use StockManagementContracts\Events\ProductReleased;
use StockManagementContracts\Events\ProductReserved;
use StockManagementContracts\Events\ProductSetAsDamaged;
use StockManagementContracts\Events\ReservationCancelled;

class StockProjector extends Projector
{
    public function onProductReceived(ProductReceived $event): void
    {
        $row = DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->first();

        if ($row) {
            DB::table('stocks')
                ->where('product_id', $event->productId)
                ->where('branch_id', $event->branchId)
                ->increment('available', $event->quantity);

            return;
        }

        DB::table('stocks')
            ->insert([
                'product_id' => $event->productId,
                'branch_id' => $event->branchId,
                'available' => $event->quantity,
                'reserved' => 0
            ]);
    }

    public function onDamagedProductReceived(DamagedProductReceived $event): void
    {
        $row = DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->first();

        if ($row) {
            DB::table('stocks')
                ->where('product_id', $event->productId)
                ->where('branch_id', $event->branchId)
                ->increment('damaged', $event->quantity);

            return;
        }

        DB::table('stocks')
            ->insert([
                'product_id' => $event->productId,
                'branch_id' => $event->branchId,
                'damaged' => $event->quantity,
                'reserved' => 0,
                'available' => 0
            ]);
    }

    public function onProductSetAsDamaged(ProductSetAsDamaged $event): void
    {
        DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->decrement('available', $event->quantity);

        DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->increment('damaged', $event->quantity);
    }

    public function onProductReserved(ProductReserved $event): void
    {
        DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->decrement('available', $event->quantity);

        DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->increment('reserved', $event->quantity);
    }

    public function onReservationCancelled(ReservationCancelled $event): void
    {
        DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->increment('available', $event->quantity);

        DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->decrement('reserved', $event->quantity);
    }

    public function onProductReleased(ProductReleased $event): void
    {
        DB::table('stocks')
            ->where('product_id', $event->productId)
            ->where('branch_id', $event->branchId)
            ->decrement('available', $event->quantity);
    }
}

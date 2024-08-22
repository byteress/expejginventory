<?php

namespace Delivery\Projectors;

use Delivery\Models\Delivery\Enums\DeliveryStatus;
use DeliveryContracts\Events\DeliveryAssigned;
use DeliveryContracts\Events\DeliveryCompleted;
use DeliveryContracts\Events\DeliveryItemDelivered;
use DeliveryContracts\Events\DeliveryPartiallyCompleted;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class AttemptProjector extends Projector
{
    public function onDeliveryAssigned(DeliveryAssigned $event): void
    {
        DB::table('deliveries')
            ->insert([
                'delivery_id' => $event->deliveryId,
                'driver' => $event->driver,
                'truck' => $event->truck,
                'branch_id' => $event->branchId,
                'notes' => $event->notes,
                'status' => 0,
                'assigned_at' => $event->createdAt()?->toDateTime()->format('Y-m-d H:i:s'),
            ]);

        foreach ($event->items as $item){
            DB::table('attempt_items')
                ->insert([
                    'delivery_id' => $event->deliveryId,
                    'order_id' => $item['orderId'],
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                ]);
        }
    }

    public function onDeliveryItemDelivered(DeliveryItemDelivered $event): void
    {
        DB::table('attempt_items')
            ->where('delivery_id', $event->deliveryId)
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->update([
                'delivered' => $event->success
            ]);
    }

    public function onDeliveryCompleted(DeliveryCompleted $event): void
    {
        DB::table('deliveries')
            ->where('delivery_id', $event->deliveryId)
            ->update([
                'status' => DeliveryStatus::COMPLETED->value,
                'completed_at' => $event->createdAt()?->toDateTime()->format('Y-m-d H:i:s'),
            ]);
    }

    public function onDeliveryPartiallyCompleted(DeliveryPartiallyCompleted $event): void
    {
        DB::table('deliveries')
            ->where('delivery_id', $event->deliveryId)
            ->update([
                'status' => DeliveryStatus::PARTIALLY_COMPLETED->value,
                'completed_at' => $event->createdAt()?->toDateTime()->format('Y-m-d H:i:s'),
            ]);
    }
}

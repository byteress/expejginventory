<?php

namespace Order\Projectors;

use Illuminate\Support\Facades\DB;
use OrderContracts\Events\ItemAdded;
use OrderContracts\Events\ItemPriceUpdated;
use OrderContracts\Events\ItemQuantityUpdated;
use OrderContracts\Events\ItemRemoved;
use OrderContracts\Events\OrderAuthorized;
use OrderContracts\Events\OrderCancelled;
use OrderContracts\Events\OrderDelivered;
use OrderContracts\Events\OrderPlaced;
use OrderContracts\Events\OrderShipped;
use PaymentContracts\Events\InstallmentInitialized;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class OrderProjector extends Projector
{
    public function onOrderPlaced(OrderPlaced $event): void
    {
        $total = 0;
        foreach ($event->items as $item) {
            $total = $total + ($item['price'] * $item['quantity']);

            DB::table('line_items')
                ->insert([
                    'product_id' => $item['productId'],
                    'order_id' => $event->orderId,
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'reservation_id' => $item['reservationId'],
                ]);
        }

        DB::table('orders')
            ->insert([
                'order_id' => $event->orderId,
                'branch_id' => $event->branchId,
                'customer_id' => $event->customerId,
                'assistant_id' => $event->assistantId,
                'total' => $total,
                'placed_at' => $event->createdAt()?->toDateTime()->format('Y-m-d H:i:s') ?? '',
                'order_type' => $event->orderType
            ]);
    }

    public function onItemAdded(ItemAdded $event): void
    {
        DB::table('line_items')
            ->insert([
                'product_id' => $event->productId,
                'order_id' => $event->orderId,
                'title' => $event->title,
                'price' => $event->price,
                'quantity' => $event->quantity,
                'reservation_id' => $event->reservationId,
            ]);

        $this->updateTotal($event->orderId);
    }

    public function onItemPriceUpdated(ItemPriceUpdated $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'requires_authorization' => $event->orderAuthRequired
            ]);

        DB::table('line_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->update([
                'price' => $event->newPrice
            ]);

        $this->updateTotal($event->orderId);
    }

    public function onItemQuantityUpdated(ItemQuantityUpdated $event): void
    {
        DB::table('line_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->update([
                'quantity' => $event->newQuantity,
                'reservation_id' => $event->reservationId
            ]);

        $this->updateTotal($event->orderId);
    }

    public function onItemRemoved(ItemRemoved $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'requires_authorization' => $event->authorizationRequired
            ]);

        DB::table('line_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->delete();

        $this->updateTotal($event->orderId);
    }

    public function onOrderAuthorized(OrderAuthorized $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'requires_authorization' => false
            ]);
    }

    public function onOrderShipped(OrderShipped $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'shipping_status' => 1
            ]);
    }

    public function onOrderDelivered(OrderDelivered $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'shipping_status' => 2
            ]);
    }

    public function onOrderCancelled(OrderCancelled $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'status' => 3,
            ]);
    }

    private function updateTotal(string $orderId): void
    {
        $items = DB::table('line_items')
            ->where('order_id', $orderId)
            ->get();

        $total = 0;
        foreach ($items as $item) {
            $total = $total + ($item->price * $item->quantity);
        }

        DB::table('orders')
            ->where('order_id', $orderId)
            ->update([
                'total' => $total
            ]);
    }
}

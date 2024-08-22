<?php

namespace Delivery\Projectors;

use DeliveryContracts\Events\DeliveryOrderPlaced;
use DeliveryContracts\Events\ItemConfirmedForDelivery;
use DeliveryContracts\Events\ItemDelivered;
use DeliveryContracts\Events\ItemShipped;
use DeliveryContracts\Events\PickupOrderPlaced;
use Illuminate\Support\Facades\DB;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class DeliveryProjector extends Projector
{
    public function onPickupOrderPlaced(PickupOrderPlaced $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'delivery_type' => 'pickup'
            ]);

        $this->insertItems($event->orderId, $event->items);
    }

    public function onDeliveryOrderPlaced(DeliveryOrderPlaced $event): void
    {
        DB::table('orders')
            ->where('order_id', $event->orderId)
            ->update([
                'delivery_type' => 'deliver',
                'delivery_fee' => $event->deliveryFee,
                'delivery_address' => $event->address
            ]);

        $this->insertItems($event->orderId, $event->items);
    }

    public function onItemConfirmedForDelivery(ItemConfirmedForDelivery $event): void
    {
        DB::table('delivery_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->where('reservation_id', $event->reservationId)
            ->increment('to_ship', $event->quantity);
    }

    public function onItemShipped(ItemShipped $event): void
    {
        DB::table('delivery_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->decrement('to_ship', $event->quantity);

        DB::table('delivery_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->increment('out_for_delivery', $event->quantity);
    }

    public function onItemDelivered(ItemDelivered $event): void
    {
        DB::table('delivery_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->increment('to_ship', $event->failure);

        DB::table('delivery_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->decrement('out_for_delivery', ($event->success + $event->failure));

        DB::table('delivery_items')
            ->where('order_id', $event->orderId)
            ->where('product_id', $event->productId)
            ->increment('delivered', $event->success);
    }

    /**
     * @param string $orderId
     * @param array<array{'productId': string, 'title': string, 'quantity': int, 'reservationId': string}> $items
     * @return void
     */
    private function insertItems(string $orderId, array $items): void
    {
        foreach ($items as $item) {
            DB::table('delivery_items')
                ->insert([
                    'order_id' => $orderId,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'title' => $item['title'],
                    'reservation_id' => $item['reservationId']
                ]);
        }
    }
}

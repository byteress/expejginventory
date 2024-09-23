<?php

namespace Payment\EventHandlers;

use DeliveryContracts\Events\ItemDelivered;
use Illuminate\Support\Facades\DB;
use Order\Models\Order\Order;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class ItemDeliveredEventHandler extends Reactor
{
    public function onItemDelivered(ItemDelivered $event): void
    {
        $order = DB::table('orders')->where('order_id', $event->orderId)->first();
        if(!$order) return;

        $orderAggregate = Order::retrieve($event->orderId);

        app()->call('PaymentContracts\IPaymentService@startInstallment', [
            'orderId' => $event->orderId,
            'customerId' => $order->customer_id,
            'installmentStartDate' => $orderAggregate->getInstallmentStartDate()
        ]);
    }
}

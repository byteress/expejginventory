<?php

namespace Delivery;

use Delivery\Models\Delivery\Delivery;
use Delivery\Models\Order\Order;
use DeliveryContracts\Exceptions\InvalidDomainException;
use DeliveryContracts\IDeliveryService;
use DeliveryContracts\Utils\Result;
use Exception;

class DeliveryService implements IDeliveryService
{

    /**
     * @inheritDoc
     */
    public function placeOrder(
        string $orderId,
        array $items,
        string $type,
        string $branchId,
        int $deliveryFee = 0,
        ?string $address = null): Result
    {
        try {
            $order  = Order::retrieve($orderId);
            $convertedItems = array_map(function ($item) {
                return Order::arrayToItem($item);
            }, $items);

            if($type == 'pickup'){
                $order->placePickupOrder($convertedItems, branchId: $branchId);
            }else{
                if(!$address) throw new InvalidDomainException('Address is required.', [
                    'address' => 'Address is required.'
                ]);

                $order->placeDeliveryOrder($convertedItems, $deliveryFee, $address, $branchId);
            }

            $order->persist();
            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function confirmItemForDelivery(
        string $orderId,
        string $productId,
        string $reservationId,
        int $quantity
    ): Result
    {
        try{
            $order = Order::retrieve($orderId);
            $order->confirmItemForDelivery($productId, $reservationId, $quantity);

            $order->persist();
            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function shipItems(
        string $deliveryId,
        string $driver,
        string $truck,
        string $branch,
        array  $items,
        ?string $notes
    ): Result
    {
       try{
            foreach($items as $item)
            {
                $order = Order::retrieve($item['orderId']);
                $order->shipItem($item['productId'], $item['quantity']);
                $order->persist();
            }

           $convertedItems = array_map(function ($item) {
               return Delivery::arrayToItem($item);
           }, $items);

            $delivery = Delivery::retrieve($deliveryId);
            $delivery->assign($driver, $truck, $branch, $convertedItems, $notes);
            $delivery->persist();

            return Result::success(null);
       } catch (Exception $e) {
           report($e);
           return Result::failure($e);
       }
    }

    public function setItemsAsDelivered(string $deliveryId, string $branch, array $items): Result
    {
        try{
            $convertedItems = array_map(function ($item) {
                return Delivery::arrayToItem($item);
            }, $items);

            $delivery = Delivery::retrieve($deliveryId);
            $delivery->markAsComplete($convertedItems, $branch);
            $delivery->persist();

            foreach ($items as $item){
                $order = Order::retrieve($item['orderId']);

                $deliveryItem = $delivery->state()->getItem($item['orderId'], $item['productId']);
                $success = $deliveryItem->getDelivered();
                $failure = $deliveryItem->getQuantity() - $deliveryItem->getDelivered();

                $order->deliverItem($item['productId'], $success, $failure);
                $order->persist();
            }


            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function updateNotes(string $deliveryId, string $notes): Result
    {
        try{
            $delivery = Delivery::retrieve($deliveryId);
            $delivery->updateNotes($notes);
            $delivery->persist();

            return Result::success(null);
        }catch (Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}

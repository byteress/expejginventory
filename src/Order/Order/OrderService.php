<?php

namespace Order;

use Exception;
use Illuminate\Support\Facades\Crypt;
use Order\Models\Order\Order;
use Order\Models\Product\Product;
use OrderContracts\Exceptions\InvalidDomainException;
use OrderContracts\IOrderService;
use OrderContracts\Utils\Result;

class OrderService implements IOrderService
{
    public function setPrice(string $productId, int $regularPrice, int $salePrice): Result
    {
        try {
            $product = Product::retrieve($productId);
            $product->setPrice($regularPrice, $salePrice);
            $product->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    /** @param array<array{
     *      'productId': string,
     *      'title': string,
     *      'quantity': int,
     *      'price': int,
     *      'reservationId': string
     * }> $items */
    public function placeOrder(string $orderId, string $customerId, string $assistantId, string $branchId, array $items, string $orderType, ?string $authorization): Result
    {
        try {
            foreach ($items as $item) {
                $product = Product::retrieve($item['productId']);
                //check if specified price and the regular price has a difference of 5% of the regular price
                if ($product->needsAuthorization($item['price'])) {
                    $this->validateAuthorization($authorization, $items);
                }
            }

            $order = Order::retrieve($orderId);
            $order->place($items, $customerId, $assistantId, $branchId, $orderType);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function addItem(
        string $orderId,
        string $productId,
        string $title,
        int $price,
        int $quantity,
        string $reservationId
    ): Result
    {
        try {
            $order = Order::retrieve($orderId);
            $order->addItem($productId, $title, $price, $quantity, $reservationId);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function updateItemPrice(string $orderId, string $productId, int $newPrice): Result
    {
        try {
            $product = Product::retrieve($productId);

            $order = Order::retrieve($orderId);
            $order->updateItemPrice($productId, $newPrice, $product->needsAuthorization($newPrice));
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function updateItemQuantity(string $orderId, string $productId, int $newQuantity, string $reservationId): Result
    {
        try {
            $order = Order::retrieve($orderId);
            $order->updateItemQuantity($productId, $newQuantity, $reservationId);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function removeItem(string $orderId, string $productId): Result
    {
        try {
            $order = Order::retrieve($orderId);
            $order->removeItem($productId);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function confirmOrder(string $orderId, string $actor, string $authorization): Result
    {
        try{
            $decrypted = Crypt::decryptString($authorization);
            if ($decrypted != $orderId) {
                throw new InvalidDomainException('Authorization from admin is invalid', ['authorization' => 'Authorization from admin is invalid'], 1002);
            }

            $order = Order::retrieve($orderId);
            $order->authorize($actor);

            $order->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    /** @param array<array{
     *      'productId': string,
     *      'title': string,
     *      'quantity': int,
     *      'price': int,
     *      'reservationId': string
     * }> $items */
    private function validateAuthorization(?string $authorization, array $items): void
    {
        if ($authorization === null) throw new InvalidDomainException('Authorization from admin is required', ['authorization' => 'Authorization from admin is required'], 1001);

        $decrypted = Crypt::decryptString($authorization);
        if ($decrypted != serialize($items)) {
            throw new InvalidDomainException('Authorization from admin is invalid', ['authorization' => 'Authorization from admin is invalid'], 1002);
        }
    }
}

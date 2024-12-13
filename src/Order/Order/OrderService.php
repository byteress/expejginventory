<?php

namespace Order;

use DateTime;
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

    /**
     * @inheritDoc
     */
    public function placeOrder(
        string $orderId,
        string $customerId,
        string $assistantId,
        string $branchId,
        array $items,
        string $orderType,
        ?string $authorization,
        ?string $cancelledOrder = null
    ): Result
    {
        try {
            foreach ($items as $item) {
                $product = Product::retrieve($item['productId']);
                //check if specified price and the regular price has a difference of 5% of the regular price
                if ($product->needsAuthorization($item['price'], $item['priceType'])) {
                    $this->validateAuthorization($authorization, serialize($items));
                }
            }

            $order = Order::retrieve($orderId);
            $order->place($items, $customerId, $assistantId, $branchId, $orderType, $cancelledOrder);
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
        int $originalPrice,
        int $quantity,
        string $reservationId,
        string $priceType
    ): Result
    {
        try {
            $order = Order::retrieve($orderId);
            $order->addItem($productId, $title, $price, $originalPrice, $quantity, $reservationId, $priceType);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function updateItemPrice(string $orderId, string $productId, int $newPrice, string $priceType): Result
    {
        try {
            $product = Product::retrieve($productId);

            $order = Order::retrieve($orderId);
            $order->updateItemPrice($productId, $newPrice, $product->needsAuthorization($newPrice, $priceType));
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

    public function cancel(string $orderId, string $actor, string $authorization, ?string $notes): Result
    {
        try {
            $this->validateAuthorization($authorization, "cancel-$orderId");

            $order = Order::retrieve($orderId);
            $order->cancel($actor, $notes);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function delete(string $orderId, string $actor, string $authorization, ?string $notes): Result
    {
        try {
            $this->validateAuthorization($authorization, "delete-$orderId");

            $order = Order::retrieve($orderId);
            $order->delete($actor, $notes);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function refund(string $orderId, string $actor, string $authorization, ?string $notes): Result
    {
        try {
            $this->validateAuthorization($authorization, "refund-$orderId");

            $order = Order::retrieve($orderId);
            $order->refund($actor, $notes);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function setPreviousOrder(string $orderId, ?DateTime $installmentStartDate): Result
    {
        try {
            $order = Order::retrieve($orderId);
            $order->setAsPrevious($installmentStartDate);
            $order->persist();

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    /**
     * @throws InvalidDomainException
     */
    private function validateAuthorization(?string $authorization, string $key): void
    {
        if ($authorization === null) throw new InvalidDomainException('Authorization from admin is required', ['authorization' => 'Authorization from admin is required'], 1001);

        $decrypted = Crypt::decryptString($authorization);
        if ($decrypted != $key) {
            throw new InvalidDomainException('Authorization from admin is invalid', ['authorization' => 'Authorization from admin is invalid'], 1002);
        }
    }
}

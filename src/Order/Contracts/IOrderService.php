<?php

namespace OrderContracts;

use OrderContracts\Utils\Result;

interface IOrderService
{
    public function setPrice(string $productId, int $regularPrice, int $salePrice): Result;
    /** @param array<array{
     *      'productId': string,
     *      'title': string,
     *      'quantity': int,
     *      'price': int,
     *      'reservationId': string
     * }> $items */
    public function placeOrder(
        string $orderId,
        string $customerId,
        string $assistantId,
        string $branchId,
        array $items,
        string $orderType,
        ?string $authorization,
        ?string $cancelledOrder = null
    ): Result;
    public function addItem(
        string $orderId,
        string $productId,
        string $title,
        int $price,
        int $quantity,
        string $reservationId
    ): Result;
    public function updateItemPrice(string $orderId, string $productId, int $newPrice): Result;
    public function updateItemQuantity(string $orderId, string $productId, int $newQuantity, string $reservationId): Result;
    public function removeItem(string $orderId, string $productId): Result;
    public function confirmOrder(string $orderId, string $actor, string $authorization): Result;

    /**
     * @param string $shippingId
     * @param string $driver
     * @param string $truck
     * @param string $branch
     * @param array<string> $orders
     * @param string|null $notes
     * @return Result
     */
    public function shipOrders(string $shippingId, string $driver, string $truck, string $branch, array $orders, ?string $notes = null): Result;
    public function markAsDelivered(string $orderId): Result;
    public function cancel(string $orderId, string $actor, string $authorization, ?string $notes): Result;
    public function refund(string $orderId, string $actor, string $authorization, ?string $notes): Result;
}

<?php

namespace OrderContracts;

use DateTime;
use OrderContracts\Utils\Result;

interface IOrderService
{
    public function setPrice(string $productId, int $regularPrice, int $salePrice): Result;
    /** @param array<array{
     *      'productId': string,
     *      'title': string,
     *      'quantity': int,
     *      'price': int,
     *      'reservationId': string,
     *     'priceType': string
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
        string $reservationId,
        string $priceType
    ): Result;
    public function updateItemPrice(string $orderId, string $productId, int $newPrice, string $priceType): Result;
    public function updateItemQuantity(string $orderId, string $productId, int $newQuantity, string $reservationId): Result;
    public function removeItem(string $orderId, string $productId): Result;
    public function confirmOrder(string $orderId, string $actor, string $authorization): Result;
    public function cancel(string $orderId, string $actor, string $authorization, ?string $notes): Result;
    public function refund(string $orderId, string $actor, string $authorization, ?string $notes): Result;
    public function setPreviousOrder(string $orderId, ?DateTime $installmentStartDate): Result;
}

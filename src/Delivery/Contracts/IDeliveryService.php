<?php

namespace DeliveryContracts;

use DeliveryContracts\Utils\Result;

interface IDeliveryService
{
    /**
     * @param string $orderId
     * @param array<array{'productId': string, 'title': string, 'quantity': int, 'reservationId': string}> $items
     * @param string $type
     * @param string $branchId
     * @param string|null $address
     * @return Result
     */
    public function placeOrder(
        string $orderId,
        array $items,
        string $type,
        string $branchId,
        int $deliveryFee = 0,
        ?string $address = null): Result;
    public function confirmItemForDelivery(string $orderId, string $productId, string $reservationId, int $quantity): Result;

    /**
     * @param string $deliveryId
     * @param string $driver
     * @param string $truck
     * @param string $branch
     * @param array<array{'orderId': string, 'productId': string, 'quantity': int}> $items
     * @param string|null $notes
     * @return Result
     */
    public function shipItems(
        string $deliveryId,
        string $driver,
        string $truck,
        string $branch,
        array  $items,
        ?string $notes
    ): Result;

    /**
     * @param string $deliveryId
     * @param string $branch
     * @param array<array{'orderId': string, 'productId': string, 'quantity': int}> $items
     * @return Result
     */
    public function setItemsAsDelivered(
        string $deliveryId,
        string $branch,
        array  $items
    ): Result;

    public function updateNotes(string $deliveryId, string $notes): Result;
}

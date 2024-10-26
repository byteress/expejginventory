<?php

namespace StockManagementContracts;

use StockManagementContracts\Utils\Result;

interface IStockManagementService
{
    public function receive(string $productId, int $quantity, string $branchId, string $actor): Result;

    /**
     * @param string $batchId
     * @param array<string, int> $products
     * @param string $branchId
     * @param string $actor
     * @param string $requestedBy
     * @param string $notes
     * @return Result
     */
    public function batchReceive(
        string $batchId,
        array $products,
        string $branchId,
        string $actor,
        string $requestedBy,
        string $notes
    ): Result;
    public function receiveDamaged(string $productId, int $quantity, string $branchId, string $actor): Result;
    public function setDamaged(string $productId, int $quantity, string $branchId, string $actor): Result;
    public function reserve(
        string $productId,
        string $reservationId,
        int $quantity,
        string $branchId,
        string $actor,
        bool $advanceOrder = false,
        bool $hasExpiry = false
    ): Result;
    public function confirmReservation(string $productId, string $reservationId): Result;
    public function cancelReservation(
        string  $productId,
        string  $reservationId,
        ?string $actor,
        bool $expired = false
    ): Result;
    public function fulfillReservation(string $productId, string $reservationId): Result;
    public function release(string $productId, int $quantity, string $branchId, string $actor): Result;
    public function return(string $productId, int $quantity, string $branchId, string $actor, string $reservationId): Result;
    public function adjust(string $productId, ?int $available, ?int $damaged, string $branchId, string $actor): Result;
}

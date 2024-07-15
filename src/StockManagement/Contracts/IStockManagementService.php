<?php

namespace StockManagementContracts;

use StockManagementContracts\Utils\Result;

interface IStockManagementService
{
    public function receive(string $productId, int $quantity, string $branchId, string $actor): Result;

    /**
     * @param array<string, int> $products
     * @param string $branchId
     * @param string $actor
     * @param string $requestedBy
     * @param string $notes
     * @return Result
     */
    public function batchReceive(string $batchId, array $products, string $branchId, string $actor, string $requestedBy, string $notes): Result;
    public function receiveDamaged(string $productId, int $quantity, string $branchId, string $actor): Result;
    public function setDamaged(string $productId, int $quantity, string $branchId, string $actor): Result;
    public function reserve(string $productId, string $reservationId, int $quantity, string $branchId, string $actor, bool $advanceOrder = false): Result;
    public function cancelReservation(string $productId, string $reservationId, string $actor): Result;
    public function release(string $productId, int $quantity, string $branchId, string $actor): Result;
}

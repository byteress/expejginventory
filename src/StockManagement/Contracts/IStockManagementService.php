<?php

namespace StockManagementContracts;

use StockManagementContracts\Utils\Result;

interface IStockManagementService
{
    public function receive(string $productId, int $quantity, string $branchId, string $actor): Result;
    public function reserve(string $productId, string $reservationId, int $quantity, string $branchId, string $actor): Result;
    public function release(string $productId, int $quantity, string $branchId, string $actor): Result;
}
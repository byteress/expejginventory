<?php

namespace StockManagement;

use Exception;
use StockManagement\Models\Product\Product;
use StockManagementContracts\IStockManagementService;
use StockManagementContracts\Utils\Result;

class StockManagementService implements IStockManagementService
{
    public function receive(string $productId, int $quantity, string $branchId, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->receive($branchId, $quantity, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function receiveDamaged(string $productId, int $quantity, string $branchId, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->receiveDamaged($branchId, $quantity, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function setDamaged(string $productId, int $quantity, string $branchId, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->setDamaged($branchId, $quantity, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function reserve(string $productId, string $reservationId, int $quantity, string $branchId, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->reserve($reservationId, $branchId, $quantity, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function cancelReservation(string $productId, string $reservationId, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->cancelReservation($reservationId, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function release(string $productId, int $quantity, string $branchId, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->release($branchId, $quantity, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}

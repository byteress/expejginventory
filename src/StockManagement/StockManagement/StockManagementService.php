<?php

namespace StockManagement;

use Exception;
use StockManagement\Models\Batch\Batch;
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

    public function reserve(
        string $productId,
        string $reservationId,
        int $quantity,
        string $branchId,
        string $actor,
        bool $advanceOrder = false,
        bool $hasExpiry = false
    ): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->reserve($reservationId, $branchId, $quantity, $actor, $advanceOrder, $hasExpiry);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function cancelReservation(string $productId, string $reservationId, ?string $actor, bool $expired = false): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->cancelReservation($reservationId, $actor, false);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function fulfillReservation(string $productId, string $reservationId): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->fulfillReservation($reservationId);
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

    public function batchReceive(
        string $batchId,
        array $products,
        string $branchId,
        string $actor,
        string $requestedBy,
        string $notes
    ): Result
    {
        try {
            $batch = new Batch();

            $batch->id = $batchId;
            $batch->branch_id = $branchId;
            $batch->requested_by = $requestedBy;
            $batch->notes = $notes;
            $batch->approved_by = $actor;
            $batch->batch_number = Batch::generateBatchNumber();
            $batch->save();

            foreach ($products as $productId => $quantity) {
                $receive = Product::retrieve($productId);
                $receive->receive($branchId, $quantity, $actor, $batchId);
                $receive->persist();
            }

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function return(string $productId, int $quantity, string $branchId, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->return($branchId, $quantity, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function confirmReservation(string $productId, string $reservationId): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->confirmReservation($reservationId);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}

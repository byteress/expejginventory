<?php

namespace Transfer;

use Exception;
use Transfer\Models\Product\Product;
use TransferContracts\ITransferService;
use TransferContracts\Utils\Result;

class TransferService implements ITransferService
{
    public function request(string $productId, string $receiver, int $quantity, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->request($quantity, $receiver, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function transfer(string $productId, string $receiver, int $quantity, string $sender, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->transfer($quantity, $receiver, $sender, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function receive(string $productId, string $receiver, int $quantity, string $actor): Result
    {
        try{
            $product = Product::retrieve($productId);
            $product->receive($quantity, $receiver, $actor);
            $product->persist();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}
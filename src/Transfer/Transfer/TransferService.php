<?php

namespace Transfer;

use Exception;
use Transfer\Models\Product\Product;
use Transfer\Models\Transfer\Transfer;
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

    /**
     * @inheritDoc
     */
    public function transfer(string $transferId, array $products, string $receiver, string $sender, string $driver, string $truck, string $actor, ?string $notes = null): Result
    {
        try{
            $transfer = new Transfer();
            $transfer->id = $transferId;
            $transfer->transfer_number = Transfer::generateTransferNumber();
            $transfer->receiver_branch = $receiver;
            $transfer->sender_branch = $sender;
            $transfer->driver = $driver;
            $transfer->truck = $truck;
            $transfer->notes = $notes;
            $transfer->requested = $actor;
            $transfer->status = 0;
            $transfer->save();

            foreach ($products as $id => $quantity) {
                $product = Product::retrieve($id);
                $product->transfer($quantity, $receiver, $sender, $actor, $transferId);
                $product->persist();
            }

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function receive(string $transferId, array $products, string $actor): Result
    {
        try{
            $transfer = Transfer::find($transferId);
            if(!$transfer) return Result::failure(new Exception("Transfer $transferId not found"));

            $transfer->status = 1;
            $transfer->save();

            foreach ($products as $id => $quantities) {
                $product = Product::retrieve($id);
                $product->receive($transferId, $quantities['received'], $quantities['damaged'], $quantities['lacking'], $actor);
                $product->persist();
            }

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}

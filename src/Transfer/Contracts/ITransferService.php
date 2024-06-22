<?php

namespace TransferContracts;

use TransferContracts\Utils\Result;

interface ITransferService
{
    public function request(string $productId, string $receiver, int $quantity, string $actor): Result;

    /**
     * @param string $transferId
     * @param array<string, int> $products
     * @param string $receiver
     * @param string $sender
     * @param string $actor
     * @return Result
     */
    public function transfer(string $transferId, array $products, string $receiver, string $sender, string $driver, string $truck, string $actor, ?string $notes = null): Result;

    /**
     * @param string $transferId
     * @param array<string, array{'received': int, 'damaged': int}> $products
     * @param string $actor
     * @return Result
     */
    public function receive(string $transferId, array $products, string $actor): Result;
}

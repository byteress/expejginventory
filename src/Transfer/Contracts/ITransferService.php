<?php

namespace TransferContracts;

use TransferContracts\Utils\Result;

interface ITransferService
{
    public function request(string $productId, string $receiver, int $quantity, string $actor): Result;
    public function transfer(string $productId, string $receiver, int $quantity, string $sender, string $actor): Result;
    public function receive(string $productId, string $receiver, int $quantity, string $actor): Result;
}
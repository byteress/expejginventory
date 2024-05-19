<?php

namespace BranchManagementContracts;

use BranchManagementContracts\Utils\Result;

interface IBranchManagementService
{
    public function create(string $branchId, string $name, ?string $address, ?string $phone, ?string $description): Result;
    public function update(string $branchId, string $name, ?string $address, ?string $phone, ?string $description): Result;
    public function delete(string $branchId): Result;
}
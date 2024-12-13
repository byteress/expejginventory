<?php

namespace SupplierManagementContracts;

use SupplierManagementContracts\Utils\Result;

interface ISupplierManagementService
{
    public function create(string $supplierId, string $code, string $name, ?string $phone): Result;
    public function update(string $supplierId, string $code, string $name, ?string $phone): Result;
    public function delete(string $supplierId): Result;
}
<?php

namespace CustomerManagementContracts;

use CustomerManagementContracts\Utils\Result;
use DateTime;

interface ICustomerManagementService
{
    public function createCustomer(
        string $customerId,
        string $firstName,
        string $lastName,
        string $phone,
        string $address,
        string $branchId,
        ?string $email = null,
        ?DateTime $dob = null
    ): Result;

    public function updateCustomer(
        string $customerId,
        string $firstName,
        string $lastName,
        string $phone,
        string $address,
        string $branchId,
        ?string $email = null,
        ?DateTime $dob = null
    ): Result;
}

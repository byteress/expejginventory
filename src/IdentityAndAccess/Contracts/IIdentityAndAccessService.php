<?php

namespace IdentityAndAccessContracts;

use IdentityAndAccessContracts\Utils\Result;

interface IIdentityAndAccessService
{
    public function create(string $userId, string $firstName, string $lastName, string $email, string $password, ?string $phone, ?string $address, string $role, ?string $branch): Result;
    public function update(
        string $userId, 
        string $firstName, 
        string $lastName, 
        string $email, 
        ?string $phone, 
        ?string $address, 
        string $password,  // ✅ Add this missing parameter
        string $role, 
        ?string $branch
    ): Result;
    public function delete(string $userId): Result;
    public function authorize(string $email, string $password, string $key): Result;
}
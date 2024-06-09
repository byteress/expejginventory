<?php

namespace CustomerManagement;

use CustomerManagement\Models\Customer;
use CustomerManagementContracts\Exceptions\InvalidDomainException;
use CustomerManagementContracts\ICustomerManagementService;
use CustomerManagementContracts\Utils\Result;
use Exception;

class CustomerManagementService implements ICustomerManagementService
{
    public function createCustomer(
        string $customerId,
        string $firstName,
        string $lastName,
        string $phone,
        string $address,
        string $branchId,
        ?string $email = null
    ): Result
    {
        try{
            $customer = new Customer();
            $customer->id = $customerId;
            $customer->first_name = $firstName;
            $customer->last_name = $lastName;
            $customer->email = $email;
            $customer->phone = $phone;
            $customer->address = $address;
            $customer->branch_id = $branchId;
            $customer->save();

            return Result::success(null);
        }catch(Exception $e){
            return Result::failure($e);
        }
    }

    public function updateCustomer(
        string $customerId,
        string $firstName,
        string $lastName,
        string $phone,
        string $address,
        string $branchId,
        ?string $email = null
    ): Result
    {
        try{
            $customer = Customer::find($customerId);

            if(!$customer) throw new InvalidDomainException('Customer not found', ['customer_id' => 'Customer not found']);

            $customer->first_name = $firstName;
            $customer->last_name = $lastName;
            $customer->email = $email;
            $customer->phone = $phone;
            $customer->address = $address;
            $customer->branch_id = $branchId;
            $customer->save();

            return Result::success(null);
        }catch(Exception $e){
            return Result::failure($e);
        }
    }
}
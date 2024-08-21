<?php

namespace App\Exceptions;

use Exception;
use PaymentContracts\Exceptions\InvalidDomainException;

class ErrorHandler
{
    public static function getErrorMessage(Exception $exception): string
    {
        switch (true) {
            case $exception instanceof \IdentityAndAccessContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \BranchManagementContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \SupplierManagementContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \StockManagementContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \OrderContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \TransferContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \ProductManagementContracts\Exceptions\InvalidDomainException:
            case $exception instanceof InvalidDomainException:
            case $exception instanceof \DeliveryContracts\Exceptions\InvalidDomainException:
                return $exception->getMessage();
            default:
                return 'Something went wrong. Please try again later.';
        }
    }
}

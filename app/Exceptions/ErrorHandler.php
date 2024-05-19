<?php

namespace App\Exceptions;

use Exception;

class ErrorHandler
{
    public static function getErrorMessage(Exception $exception): string
    {
        switch (true) {
            case $exception instanceof \IdentityAndAccessContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \BranchManagementContracts\Exceptions\InvalidDomainException:
            case $exception instanceof \SupplierManagementContracts\Exceptions\InvalidDomainException:
                return $exception->getMessage();
            default:
                return 'Something went wrong. Please try again later.';
        }
    }
}

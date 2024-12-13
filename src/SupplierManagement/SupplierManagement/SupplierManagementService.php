<?php

namespace SupplierManagement;

use Exception;
use SupplierManagement\Models\Supplier;
use SupplierManagementContracts\ISupplierManagementService;
use SupplierManagementContracts\Utils\Result;

class SupplierManagementService implements ISupplierManagementService
{
    public function create(string $supplierId, string $code, string $name, ?string $phone): Result{
        try{
            $supplier = new Supplier();

            $supplier->id = $supplierId;
            $supplier->code = $code;
            $supplier->name = $name;
            $supplier->phone = $phone;

            $supplier->save();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function update(string $supplierId, string $code, string $name, ?string $phone): Result{
        try{
            Supplier::whereId($supplierId)
                ->update([
                    'name' => $name,
                    'code' => $code,
                    'phone' => $phone,
                ]);

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function delete(string $supplierId): Result{
        try{
            $supplier = Supplier::whereId($supplierId);
            $supplier->delete();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}
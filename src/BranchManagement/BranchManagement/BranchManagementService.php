<?php

namespace BranchManagement;

use BranchManagement\Models\Branch;
use BranchManagementContracts\IBranchManagementService;
use BranchManagementContracts\Utils\Result;
use Exception;

class BranchManagementService implements IBranchManagementService
{
    public function create(string $branchId, string $name, ?string $address, ?string $phone, ?string $description): Result{
        try{

            $branch = new Branch();

            $branch->id = $branchId;
            $branch->name = $name;
            $branch->address = $address;
            $branch->phone = $phone;
            $branch->description = $description;

            $branch->save();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function update(string $branchId, string $name, ?string $address, ?string $phone, ?string $description): Result{
        try{
            Branch::whereId($branchId)
                ->update([
                    'name' => $name,
                    'address' => $address,
                    'phone' => $phone,
                    'description' => $description
                ]);

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function delete(string $branchId): Result{
        try{
            $branch = Branch::whereId($branchId);
            $branch->delete();

            return Result::success(null);
        }catch(Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}
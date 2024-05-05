<?php

namespace IdentityAndAccess;

use App\Models\User;
use Exception;
use IdentityAndAccessContracts\Enums\Role;
use IdentityAndAccessContracts\Exceptions\InvalidDomainException;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use IdentityAndAccessContracts\Utils\Result;
use Illuminate\Support\Facades\Hash;

class IdentityAndAccessService implements IIdentityAndAccessService
{
    public function create(string $userId, string $firstName, string $lastName, string $email, string $password, ?string $phone, ?string $address, string $role, ?string $branch): Result
    {
        try {
            if($role != Role::Admin->value && is_null($branch)) throw new InvalidDomainException('Branch is required.', ['branch' => 'Branch is required.']);
            
            if (User::whereId($userId)->exists()) throw new InvalidDomainException('User ID already exists.', ['userId' => 'User ID already exists.']);
            if (User::whereEmail($email)->exists()) throw new InvalidDomainException('Email address already exists.', ['email' => 'Email address already exists.']);

            $user = new User();
            $user->id = $userId;
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->phone = $phone;
            $user->address = $address;
            $user->branch_id = $branch;
            $user->password = Hash::make($password);

            $user->save();

            $user->assignRole($role);

            return Result::success(null);
        } catch (Exception $e) {
            report($e);
            return Result::failure($e);
        }
    }

    public function update(string $userId, string $firstName, string $lastName, string $email, ?string $phone, ?string $address,  string $role, ?string $branch): Result
    {
        try {
            if (User::whereEmail($email)->where('email', '!=', $email)->exists()) throw new InvalidDomainException('Email address already exists.', ['email' => 'Email address already exists.']);

            User::whereId($userId)->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'branch' => $branch,
            ]);

            return Result::success(null);
        } catch (Exception $e) {
            return Result::failure($e);
        }
    }

    public function delete(string $userId): Result
    {
        try {
            $user = User::find($userId);

            $user->delete();

            return Result::success(null);
        } catch (Exception $e) {
            return Result::failure($e);
        }
    }
}

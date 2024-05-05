<?php

namespace App\Livewire\Admin;

use App\Exceptions\ErrorHandler;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Str;

#[Title('Create User')]
class CreateUser extends Component
{
    #[Validate('required|string|max:255')]
    public string $firstName = '';

    #[Validate('required|string|max:255')]
    public string $lastName = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|max:255')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $address = '';

    #[Validate('required')]
    public string $role = '';

    public ?string $branch = null;

    #[Validate('required|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function submit(IIdentityAndAccessService $identityAndAccessService)
    {
        $this->validate();

        $result = $identityAndAccessService->create(
            Str::uuid()->toString(),
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->password,
            $this->phone,
            $this->address,
            $this->role,
            $this->branch
        );

        if($result->isFailure()){
            session()->flash('status', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $this->reset();
        session()->flash('success', 'User created successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.create-user');
    }
}

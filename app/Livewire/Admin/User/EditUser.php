<?php

namespace App\Livewire\Admin\User;

use App\Exceptions\ErrorHandler;
use App\Models\User;
use BranchManagement\Models\Branch;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Edit User')]
class EditUser extends Component
{
    public User $user;

    #[Validate('required|string|max:255')]
    public string $firstName = '';

    #[Validate('required|string|max:255')]
    public string $lastName = '';

    public string $email = '';
    public ?string $phone = null;
    public ?string $address = null;

    #[Validate('required')]
    public string $role = '';

    public ?string $branch = null;

    public function rules() 
    {
        return [
            'email' => 'required|email|unique:users,email,'. $this->user->id,
        ];
    }

    public function mount(User $user)
    {
        $this->user = $user;

        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->role = $user->roles()->first()->name;
        $this->branch = $user->branch_id;
    }

    public function submit(IIdentityAndAccessService $identityAndAccessService)
    {
        $this->validate();

        $result = $identityAndAccessService->update(
            $this->user->id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->address,
            $this->role,
            $this->branch
        );

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        session()->flash('success', 'User updated successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.user.edit-user', [
            'branches' => Branch::all()
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Branch;

use App\Exceptions\ErrorHandler;
use BranchManagementContracts\IBranchManagementService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Str;

#[Title('Create Branch')]
class CreateBranch extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    public ?string $address = null; 
    public ?string $phone = null; 
    public ?string $description = null;

    public function submit(IBranchManagementService $branchManagementService)
    {
        $this->validate();

        $result = $branchManagementService->create(
            Str::uuid()->toString(),
            $this->name,
            $this->address,
            $this->phone,
            $this->description
        );

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $this->reset();
        session()->flash('success', 'Branch created successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.branch.create-branch');
    }
}

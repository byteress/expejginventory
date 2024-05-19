<?php

namespace App\Livewire\Admin\Branch;

use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use BranchManagementContracts\IBranchManagementService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Edit Branch')]
class EditBranch extends Component
{
    public Branch $branch;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public ?string $address = null; 
    public ?string $phone = null; 
    public ?string $description = null;

    public function mount(Branch $branch)
    {
        $this->branch = $branch;
        $this->name = $branch->name;
        $this->address = $branch->address;
        $this->phone = $branch->phone;
        $this->description = $branch->description;
    }

    public function submit(IBranchManagementService $branchManagementService)
    {
        $this->validate();

        $result = $branchManagementService->update(
            $this->branch->id,
            $this->name,
            $this->address,
            $this->phone,
            $this->description
        );

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        session()->flash('success', 'Branch updated successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.branch.edit-branch', [
            'title' => "Edit {$this->name}",
        ]);
    }
}

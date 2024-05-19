<?php

namespace App\Livewire\Admin\Branch;

use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use BranchManagementContracts\IBranchManagementService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Branches')]
class Branches extends Component
{
    use WithPagination;

    public function delete(IBranchManagementService $branchManagementService, $branchId)
    {
        $result = $branchManagementService->delete($branchId);

        $this->dispatch('close-modal');

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }
        
        session()->flash('success', 'Branch deleted successfully.');

    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.branch.branches', [
            'branches' => Branch::paginate(10),
        ]);
    }
}

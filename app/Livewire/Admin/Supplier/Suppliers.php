<?php

namespace App\Livewire\Admin\Supplier;

use App\Exceptions\ErrorHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SupplierManagement\Models\Supplier;
use SupplierManagementContracts\ISupplierManagementService;

#[Title('Suppliers')]
class Suppliers extends Component
{
    use WithPagination;

    public function delete(ISupplierManagementService $supplierManagementService, $supplierId)
    {
        $result = $supplierManagementService->delete($supplierId);

        $this->dispatch('close-modal');

        if ($result->isFailure()) {
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        session()->flash('success', 'Supplier deleted successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.supplier.suppliers', [
            'suppliers' => Supplier::paginate(10),
        ]);
    }
}

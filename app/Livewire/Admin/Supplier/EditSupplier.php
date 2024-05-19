<?php

namespace App\Livewire\Admin\Supplier;

use App\Exceptions\ErrorHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use SupplierManagement\Models\Supplier;
use SupplierManagementContracts\ISupplierManagementService;


#[Title('Edit Supplier')]
class EditSupplier extends Component
{
    public Supplier $supplier;

    #[Validate('required|string|max:255')]
    public string $code = '';

    #[Validate('required|string|max:255')]
    public string $name = '';

    public ?string $phone = null; 

    public function mount(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->code = $supplier->code;
        $this->name = $supplier->name;
        $this->phone = $supplier->phone;
    }

    public function submit(ISupplierManagementService $supplierManagementService)
    {
        $this->validate();

        $result = $supplierManagementService->update(
            $this->supplier->id,
            $this->code,
            $this->name,
            $this->phone
        );

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        session()->flash('success', 'Supplier updated successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.supplier.edit-supplier', [
            'title' => "Edit {$this->name}",
        ]);
    }
}

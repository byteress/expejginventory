<?php

namespace App\Livewire\Admin\Supplier;

use App\Exceptions\ErrorHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Str;
use SupplierManagementContracts\ISupplierManagementService;

#[Title('Create Supplier')]
class CreateSupplier extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $code = '';

    public ?string $phone = null;

    public function submit(ISupplierManagementService $supplierManagementService)
    {
        $this->validate();

        $result = $supplierManagementService->create(
            Str::uuid()->toString(),
            $this->code,
            $this->name,
            $this->phone
        );

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $this->reset();
        session()->flash('success', 'Supplier created successfully.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.supplier.create-supplier');
    }
}

<?php

namespace App\Livewire\Admin\Product;

use App\Exceptions\ErrorHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use ProductManagement\Models\Product;
use ProductManagementContracts\IProductManagementService;

#[Title('Products')]
class Products extends Component
{
    use WithPagination;

    public $search;

    public function delete(IProductManagementService $productManagementService, $productId)
    {
        $result = $productManagementService->delete($productId);

        $this->dispatch('close-modal');

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }
        
        session()->flash('success', 'Product deleted successfully.');

    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.products', [
            'products' => Product::where('model', 'LIKE', '%'.$this->search.'%')->orWhere('sku_number', 'LIKE', '%'.$this->search.'%')->paginate(10),
        ]);
    }
}

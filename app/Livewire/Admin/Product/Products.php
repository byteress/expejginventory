<?php

namespace App\Livewire\Admin\Product;

use App\Exceptions\ErrorHandler;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use ProductManagement\Models\Product;
use ProductManagementContracts\IProductManagementService;
use SupplierManagement\Models\Supplier;

#[Title('Products')]
class Products extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;

    #[Url]
    public $supplier = null;

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

    private function getProducts()
    {
        $query = Product::where(function($q){
            $q->where('model', 'LIKE', '%'.$this->search.'%')
                ->orWhere('sku_number', 'LIKE', '%'.$this->search.'%')
                ->orWhere('description', 'LIKE', '%'.$this->search.'%');
        });

        if(isset($this->supplier)){
            $query = $query->where('supplier_id', $this->supplier);
        }

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.products', [
            'products' => $this->getProducts(),
            'suppliers' => Supplier::all()
        ]);
    }
}

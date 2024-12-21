<?php

namespace App\Livewire\Admin\Product;

use BranchManagement\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use ProductManagement\Models\Product;
use SupplierManagement\Models\Supplier;

#[Title('Products with Zero Quantity')]
class ProductsZeroQuantity extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;

    #[Session]
    public ?string $branch = null;


    #[Url]
    public $supplier = null;
    public $date = null;

    public function mount(): void
    {
        $this->branch = $this->branch ?: auth()->user()->branch_id;
        $this->date = now();
    }

    /**
     * Handles updates to the branch and redirects with updated parameters.
     * @param string $branch
     * @return void
     */
    public function updatedBranch(string $branch): void
    {
        $this->branch = $branch;

        $this->redirect(route('admin.product.zero-quantity', [

        ]), true);
    }

    private function getProducts(): LengthAwarePaginator
    {

        $query = DB::table('products')
    
            ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->join('branches', 'branches.id', '=', 'stocks.branch_id')
        ->where('stocks.available', '=', 0)
        ->select(
            'branches.name as branch_name',
            'suppliers.name as supplier_name',
            'products.id as product_id',
            'products.description as product_name',
            'products.model as product_model',
            'stocks.available as quantity'
        );
  
        if ($this->branch) {
            $query->where('stocks.branch_id', $this->branch);
        }

        return $query->paginate(10);
    }

    private function getAllProducts()
    {
       
        $query = DB::table('products')

            ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->join('branches', 'branches.id', '=', 'stocks.branch_id')
          
        ->where('stocks.available', '=', 0);
        if ($this->branch) {
           // $query->where('stocks.branch_id', $this->branch);
           
        }

        return $query->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.products-zero-quantity',
        [
            'suppliers' => Supplier::all(),
            'products' => $this->getProducts(),
            'allProducts' => $this->getAllProducts(),
            'branches' => Branch::all(),
            'branch_name' => $this->branch ? Branch::find($this->branch)?->name : null,
        ]);
    }
}

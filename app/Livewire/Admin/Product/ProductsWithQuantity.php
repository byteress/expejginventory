<?php

namespace App\Livewire\Admin\Product;

use BranchManagement\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use SupplierManagement\Models\Supplier;

#[Title('Products with Quantity')]

class ProductsWithQuantity extends Component
{

    use WithPagination;

    #[Url(nullable:true)]
    public $search;


    public ?string $branch = null;

    #[Url]
    public $supplier = null;

    public function mount(): void
    {
        if(auth()->user()) $this->branch = auth()->user()->branch_id;
    }

    private function getProducts(): LengthAwarePaginator
    {

        $query = DB::table('products')
            ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->join('branches', 'branches.id', '=', 'stocks.branch_id')
            ->where('stocks.available', '>', 0);
        if ($this->branch) {
            $query->where('stocks.branch_id', $this->branch);
        }

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.products-with-quantity',
            [
                'suppliers' => Supplier::all(),
                'products' => $this->getProducts(),
                'branches' => Branch::all(),
            ]);
    }
}

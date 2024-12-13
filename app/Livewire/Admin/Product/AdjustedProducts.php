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

#[Title('Adjusted Products')]

class AdjustedProducts extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;

    #[Session]
    public ?string $branch = null;

    public $date;


    public function mount()
    {
        $this->branch = $this->branch ?: auth()->user()->branch_id; // Default branch to authenticated user's branch
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

        $this->redirect(route('admin.product.adjusted-products', [

        ]), true);
    }


    private function getHistory(): LengthAwarePaginator
    {
        $query = DB::table('stock_history')
            ->join('products', 'stock_history.product_id', '=', 'products.id')
            ->join('branches', 'stock_history.branch_id', '=', 'branches.id')
            ->leftJoin('users', 'stock_history.user_id', '=', 'users.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select('stock_history.*', 'products.*', 'suppliers.code', 'branches.name', 'users.first_name', 'users.last_name')
            ->where(function($q){
                $q->where('products.model', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('products.sku_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('products.description', 'LIKE', '%'.$this->search.'%');
            })->orderByDesc('date')
            ->where('stock_history.action', '=', 'Adjusted')
            ->orderByDesc('version');

        if($this->branch) $query = $query->where('stock_history.branch_id', $this->branch);

        return $query->paginate(10);
    }

    public function getAllAdjustedProducts()
    {
        $query = DB::table('stock_history')
            ->join('products', 'stock_history.product_id', '=', 'products.id')
            ->join('branches', 'stock_history.branch_id', '=', 'branches.id')
            ->leftJoin('users', 'stock_history.user_id', '=', 'users.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select('stock_history.*', 'products.*', 'suppliers.code', 'branches.name', 'users.first_name', 'users.last_name')
            ->where('stock_history.action', '=', 'Adjusted')
            ->orderByDesc('version');

        if($this->branch) $query = $query->where('stock_history.branch_id', $this->branch);

        return $query->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.adjusted-products',
        [
            'histories' => $this->getHistory(),
            'products' => $this->getAllAdjustedProducts(),
            'branches' => Branch::all(),
            'branch_name' => $this->branch ? Branch::find($this->branch)?->name : null,
        ]);
    }
}

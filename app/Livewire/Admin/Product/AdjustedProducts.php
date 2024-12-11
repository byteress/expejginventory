<?php

namespace App\Livewire\Admin\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Adjusted Products')]

class AdjustedProducts extends Component
{
    #[Url(nullable:true)]
    public $search;

    public ?string $branch = null;

    public $date;

    private function getHistory(): LengthAwarePaginator
    {
        $branchId = auth()->user()?->branch_id;
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

        if($branchId) $query = $query->where('stock_history.branch_id', $branchId);

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.adjusted-products');
    }
}

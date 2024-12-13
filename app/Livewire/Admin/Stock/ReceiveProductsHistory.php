<?php

namespace App\Livewire\Admin\Stock;

use Illuminate\Contracts\Foundation\Application as ApplicationAlias;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View as ViewAlias;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Title('Stock History')]
class ReceiveProductsHistory extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public ?string $search = null;

    /**
     * @return LengthAwarePaginator<object>
     */
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
            ->orderByDesc('version');

        if($branchId) $query = $query->where('stock_history.branch_id', $branchId);

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|ViewAlias|ApplicationAlias
    {
        return view('livewire.admin.stock.receive-products-history', [
            'histories' => $this->getHistory()
        ]);
    }
}

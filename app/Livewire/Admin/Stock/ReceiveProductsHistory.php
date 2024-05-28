<?php

namespace App\Livewire\Admin\Stock;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Title('ReceiveProductsHistory')]
class ReceiveProductsHistory extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;
    
    private function getHistory()
    {
        $branchId = auth()->user()->branch_id;
        $query = DB::table('receive_history')
            ->join('products', 'receive_history.product_id', '=', 'products.id')
            ->join('branches', 'receive_history.branch_id', '=', 'branches.id')
            ->join('users', 'receive_history.user_id', '=', 'users.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select('receive_history.*', 'products.*', 'suppliers.code', 'branches.name', 'users.first_name', 'users.last_name')
            ->where(function($q){
                $q->where('products.model', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('products.sku_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('products.description', 'LIKE', '%'.$this->search.'%');
            });
        
        if($branchId) $query = $query->where('receive_history.branch_id', $branchId);

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.receive-products-history', [
            'histories' => $this->getHistory()
        ]);
    }
}

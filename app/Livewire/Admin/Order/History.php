<?php

namespace App\Livewire\Admin\Order;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Order History')]
class History extends Component
{
    #[Url]
    public $search;

    public $branch;

    public function mount()
    {
        $this->branch = auth()->user()->branch_id;
    }

    public function getOrders()
    {
        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
            ->where(function($q){
                $q->where('branches.name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('customers.first_name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('customers.last_name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('users.first_name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('users.last_name', 'LIKE', '%'.$this->search.'%');
            })
            ->whereNotNull('orders.completed_at');

        if($this->branch) $query = $query->where('branches.id', $this->branch);

        return $query->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.history', [
            'orders' => $this->getOrders()
        ]);
    }
}

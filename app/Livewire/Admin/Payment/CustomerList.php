<?php

namespace App\Livewire\Admin\Payment;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Customer List')]
class CustomerList extends Component
{
    use WithPagination;

    #[Url]
    public $search;
    public function getCustomers()
    {
        $query = DB::table('customers')
            ->leftJoin('customer_balances', 'customers.id', '=', 'customer_balances.customer_id')
            ->whereNull('customers.deleted_at')
            ->where(function($q){
                $q->orWhere('customers.first_name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('customers.last_name', 'LIKE', '%'.$this->search.'%');
            });

        if(auth()->user()->branch_id) $query->where('branch_id', auth()->user()->branch_id);

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.payment.customer-list', [
            'customers' => $this->getCustomers()
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Payment;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Customer List')]
class CustomerList extends Component
{
    public function getCustomers()
    {
        return DB::table('customers')
            ->join('customer_balances', 'customers.id', '=', 'customer_balances.customer_id')
            ->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.payment.customer-list', [
            'customers' => $this->getCustomers()
        ]);
    }
}

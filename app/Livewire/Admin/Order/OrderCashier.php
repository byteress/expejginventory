<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Order Cashier')]
class OrderCashier extends Component
{
    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.order-cashier');
    }
}

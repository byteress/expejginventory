<?php

namespace App\Livewire\Admin\Installment;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Order History')]
class InstallmentDetails extends Component
{
    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.installment.installment-details');
    }
}

<?php

namespace App\Livewire\Admin\Stock;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Suppliers')]
class Stocks extends Component
{
    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.stocks');
    }
}

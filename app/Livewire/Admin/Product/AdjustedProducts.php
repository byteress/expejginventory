<?php

namespace App\Livewire\Admin\Product;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Adjusted Products')]

class AdjustedProducts extends Component
{
    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.adjusted-products');
    }
}

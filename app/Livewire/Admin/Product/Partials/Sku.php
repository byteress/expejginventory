<?php

namespace App\Livewire\Admin\Product\Partials;

use Livewire\Component;
use ProductManagement\Models\Product;

class Sku extends Component
{
    public $sku = null;
    public $productId;

    public function mount($productId, $sku = null)
    {
        $this->productId = $productId;
        $this->sku = $sku;
    }

    public function checkSku()
    {
        $product = Product::find($this->productId);
        if($product->sku_number) {
            $this->sku  = $product->sku_number;
        }
    }

    public function render()
    {
        return view('livewire.admin.product.partials.sku');
    }
}

<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Order Products')]
class OrderProduct extends Component
{
    public $products = [];

    public function mount()
    {
        $this->products = [
            ['id' => 1, 'title' => 'Product 1', 'description' => 'Description for Product 1', 'image' => asset('assets/img/prod1.JPEG')],
            ['id' => 2, 'title' => 'Product 2', 'description' => 'Description for Product 2', 'image' => asset('assets/img/prod2.JPEG')],
            ['id' => 2, 'title' => 'Product 3', 'description' => 'Description for Product 3', 'image' => asset('assets/img/prod3.JPEG')],
            ['id' => 2, 'title' => 'Product 4', 'description' => 'Description for Product 4', 'image' => asset('assets/img/prod4.JPEG')],
            ['id' => 2, 'title' => 'Product 5', 'description' => 'Description for Product 5', 'image' => asset('assets/img/prod5.JPEG')],
            ['id' => 2, 'title' => 'Product 6', 'description' => 'Description for Product 6', 'image' => asset('assets/img/prod6.JPEG')],
        ];

    }

    public function addToCart($productId)
    {
        session()->flash('message', "Product $productId added to cart.");
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.order-product');
    }
}

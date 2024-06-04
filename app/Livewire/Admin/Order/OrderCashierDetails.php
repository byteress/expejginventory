<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Order Cashier Details')]
class OrderCashierDetails extends Component
{public $cartItems = [];
    public $totalPrice = 0;

    public function mount()
    {
        // Sample data for demonstration purposes
        $this->cartItems = [
            ['id' => 1, 'title' => 'Product 1', 'description' => 'Description for Product 1', 'quantity' => 1, 'price' => 215000, 'image' => asset('assets/img/prod1.JPEG')],
            ['id' => 2, 'title' => 'Product 2', 'description' => 'Description for Product 2', 'image' => 'path/to/image2.jpg', 'quantity' => 2, 'price' => 150000, 'image' => asset('assets/img/prod1.JPEG')],
            // Add more items as needed
        ];

        $this->updateTotalPrice();
    }

    public function incrementQuantity($productId)
    {
        foreach ($this->cartItems as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity']++;
                break;
            }
        }
        $this->updateTotalPrice();
    }

    public function decrementQuantity($productId)
    {
        foreach ($this->cartItems as &$item) {
            if ($item['id'] == $productId && $item['quantity'] > 1) {
                $item['quantity']--;
                break;
            }
        }
        $this->updateTotalPrice();
    }

    public function removeItem($productId)
    {
        $this->cartItems = array_filter($this->cartItems, function ($item) use ($productId) {
            return $item['id'] != $productId;
        });
        $this->updateTotalPrice();
    }

    private function updateTotalPrice()
    {
        $this->totalPrice = array_reduce($this->cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.order-cashier-details');
    }
}

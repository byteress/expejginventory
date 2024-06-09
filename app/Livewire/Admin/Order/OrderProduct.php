<?php

namespace App\Livewire\Admin\Order;

use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Jackiedo\Cart\Facades\Cart;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use ProductManagement\Models\Product;
use StockManagementContracts\IStockManagementService;
use Str;

#[Title('Order Products')]
class OrderProduct extends Component
{
    use WithPagination;

    #[Url(nullable: true)]
    public $search;

    #[Validate('required')]
    #[Session]
    public ?string $branch = null;

    public function mount()
    {
        if(auth()->user()->branch_id){
            $this->branch = auth()->user()->branch_id;
        }

        session()->put('branch', $this->branch);
    }

    private function getProducts()
    {
        $branchId = auth()->user()->branch_id;
        $query = Product::join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->leftJoin('product_requests', function ($join) use ($branchId) {
                $join->on('products.id', '=', 'product_requests.product_id')
                    ->where('product_requests.receiver', '=', $this->branch);
            })
            ->leftJoin('stocks', function ($join) use ($branchId) {
                $join->on('products.id', '=', 'stocks.product_id')
                    ->where('stocks.branch_id', '=', $this->branch);
            })->where(function ($q) {
                $q->where('model', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('sku_number', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $this->search . '%');
            })->select('products.*', 'suppliers.code', 'product_requests.quantity as requested_quantity', DB::raw('COALESCE(stocks.available, 0) as quantity'))
            ->whereNull('products.deleted_at')
            // ->where('product_requests.receiver', $branchId)
            ->orderBy('quantity');

        return $query->paginate(9);
    }

    public function addToCart(IStockManagementService $stockManagementService, $productId)
    {
        $this->validateOnly('branch');

        session()->put('branch', $this->branch);

        if (Cart::getItems(['id' => $productId])) {
            session()->flash('alert', 'Product already added to cart.');
            return;
        }

        $reservationId = Str::uuid()->toString();
        $hasStock = $stockManagementService->reserve(
            $productId,
            $reservationId,
            1,
            $this->branch,
            auth()->user()->id
        );

        if ($hasStock->isFailure()) {
            session()->flash('alert', 'Product is out of stock.');
            return;
        }

        $product = Product::find($productId);

        Cart::addItem([
            'id' => $productId,
            'title' => "{$product->model} {$product->description}",
            'price' => $product->regular_price / 100,
            'extra_info' => [
                'reservation_id' => $reservationId
            ]
        ]);

        session()->flash('success', 'Product successfully added to cart.');
        return;
    }

    public function removeFromCart(IStockManagementService $stockManagementService,$productId)
    {
        $items = Cart::getItems(['id' => $productId]);
        foreach ($items as $hash => $item) {
            $stockManagementService->cancelReservation(
                $productId,
                $item->getExtraInfo()['reservation_id'],
                auth()->user()->id
            );

            Cart::removeItem($hash);
        }
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.order-product', [
            'products' => $this->getProducts(),
            'branches' => Branch::all()
        ]);
    }
}

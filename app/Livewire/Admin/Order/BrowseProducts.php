<?php

namespace App\Livewire\Admin\Order;

use App\Exceptions\ErrorHandler;
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

#[Title('Browse Products')]
class BrowseProducts extends Component
{
    use WithPagination;

    #[Url(nullable: true)]
    public ?string $search = null;

    #[Validate('required')]
    #[Session]
    public ?string $branch = null;

    /**
     * @var array<string, string>
     */
    public array $priceType = [];

    public function mount(): void
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

    /**
     * @throws \Exception
     */
    public function addToCart(IStockManagementService $stockManagementService, string $productId, string $type = 'regular'): void
    {
        $this->resetErrorBag();
        $cartType = Cart::getExtraInfo('cart_type');
        if($cartType && $type != $cartType){
            session()->flash('alert', "Can only add items for $cartType order.");
            return;
        }

        Cart::setExtraInfo('cart_type', $type);

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
            auth()->user()->id,
            $type != 'regular',
            true
        );

        if ($hasStock->isFailure()) {
            session()->flash('alert', ErrorHandler::getErrorMessage($hasStock->getError()));
            return;
        }

        $product = Product::find($productId);
        $priceType = $this->priceType[$productId] ?? 'regular_price';

        Cart::addItem([
            'id' => $productId,
            'title' => "{$product->model} {$product->description}",
            'price' => $product->$priceType / 100,
            'extra_info' => [
                'reservation_id' => $reservationId,
                'price_type' => $priceType,
                'original_price' => $product->$priceType / 100,
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
                auth()->user()->id, false
            );

            Cart::removeItem($hash);
        }

        if(Cart::hasNoItems()){
            Cart::destroy();
        }
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.browse-products', [
            'products' => $this->getProducts(),
            'branches' => Branch::all()
        ]);
    }
}

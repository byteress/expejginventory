<?php

namespace App\Livewire\Admin\Order;

use App\Exceptions\ErrorHandler;
use Exception;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use OrderContracts\IOrderService;
use ProductManagement\Models\Product;
use StockManagementContracts\IStockManagementService;
use Str;

#[Title('Order Cashier Details')]
class OrderCashierDetails extends Component
{
    use WithPagination;

    public $orderId;

    #[Url(nullable: true)]
    public $search;
    public $branch;
    public $prices = [];
    public $quantities = [];

    /**
     * @var array<string, float>
     */
    public array $discounts = [];

    public $email = '';
    public $password = '';

    public $paymentMethods = ['Cash'];
    public $referenceNumbers = [''];
    public $amounts = [null];
    public $receiptNumber = '';

    public $completed = false;

    public function mount($order_id)
    {
        $this->orderId = $order_id;

        $order = $this->getOrder();
        $this->completed = $order->completed_at;
        $this->branch = $order->branch_id;
        $this->receiptNumber = $order->receipt_number;

        $paymentMethods = $this->getPaymentMethods();
        if($paymentMethods->isNotEmpty()){
            $this->paymentMethods = [];
            $this->referenceNumbers = [];
            $this->amounts = [];

            foreach($paymentMethods as $methods){
                $this->referenceNumbers[] = $methods->reference;
                $this->paymentMethods[] = $methods->method;
                $this->amounts[] = $methods->amount;
            }
        }

        $items = $this->getItems();
        foreach ($items as $item) {
            $this->calculateDiscount($item->product_id, $item->price);
        }
    }

    public function calculateDiscount(string $productId, int $price): void
    {
        $product = $this->getProduct($productId);

        $discount = 0;
        if($product->regular_price > $price)
            $discount = ($product->regular_price - $price) / $product->regular_price;

        $this->discounts[$productId] = round($discount * 100, 2);
    }

    public function updatedPrices(IOrderService $orderService, $price, $productId): void
    {
        $result = $orderService->updateItemPrice($this->orderId, $productId, $price * 100);
        $this->calculateDiscount($productId, $price * 100);
    }

    public function updatedDiscounts(IOrderService $orderService, $percentage, $productId): void
    {
        $product = $this->getProduct($productId);

        $rate = $percentage / 100;
        $discount = $product->regular_price * $rate;
        $discountedPrice = $product->regular_price - $discount;

        $orderService->updateItemPrice($this->orderId, $productId, $discountedPrice);
    }

    public function incrementQuantity(IStockManagementService $stockManagementService, IOrderService $orderService, $productId)
    {
        $quantity = $this->quantities[$productId];
        $quantity = $quantity + 1;

        $item = $this->getItem($productId);
        $reservationId = $item->reservation_id;

        DB::beginTransaction();

        $cancelResult = $stockManagementService->cancelReservation(
            $productId,
            $reservationId,
            auth()->user()->id
        );

        if ($cancelResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($cancelResult->getError()));
        }

        $newReservationId = Str::uuid()->toString();
        $reserveResult = $stockManagementService->reserve(
            $productId,
            $newReservationId,
            $quantity,
            $this->branch,
            auth()->user()->id
        );

        if ($reserveResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($reserveResult->getError()));
        }

        $updateItemResult = $orderService->updateItemQuantity($this->orderId, $productId, $quantity, $newReservationId);
        if ($updateItemResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($updateItemResult->getError()));
        }

        DB::commit();
    }

    public function decrementQuantity(IStockManagementService $stockManagementService, IOrderService $orderService, $productId)
    {
        $quantity = $this->quantities[$productId];

        if ($quantity == 1) return;

        $quantity = $quantity - 1;

        $item = $this->getItem($productId);
        $reservationId = $item->reservation_id;

        DB::beginTransaction();

        $cancelResult = $stockManagementService->cancelReservation(
            $productId,
            $reservationId,
            auth()->user()->id
        );

        if ($cancelResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($cancelResult->getError()));
        }

        $newReservationId = Str::uuid()->toString();
        $reserveResult = $stockManagementService->reserve(
            $productId,
            $newReservationId,
            $quantity,
            $this->branch,
            auth()->user()->id
        );

        if ($reserveResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($reserveResult->getError()));
        }

        $updateItemResult = $orderService->updateItemQuantity($this->orderId, $productId, $quantity, $newReservationId);
        if ($updateItemResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($updateItemResult->getError()));
        }

        DB::commit();
    }

    public function addItem(IStockManagementService $stockManagementService, IOrderService $orderService, $productId)
    {
        $product = $this->getProduct($productId);

        DB::beginTransaction();

        $newReservationId = Str::uuid()->toString();
        $reserveResult = $stockManagementService->reserve(
            $productId,
            $newReservationId,
            1,
            $this->branch,
            auth()->user()->id
        );

        if ($reserveResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($reserveResult->getError()));
        }

        $addResult = $orderService->addItem($this->orderId, $productId, "{$product->model} {$product->description}", $product->regular_price, 1, $newReservationId);
        if ($addResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($addResult->getError()));
        }

        DB::commit();
    }

    public function removeItem(IStockManagementService $stockManagementService, IOrderService $orderService, $productId)
    {
        $item = $this->getItem($productId);
        $reservationId = $item->reservation_id;

        DB::beginTransaction();

        $cancelResult = $stockManagementService->cancelReservation(
            $productId,
            $reservationId,
            auth()->user()->id
        );

        if ($cancelResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($cancelResult->getError()));
        }

        $removeResult = $orderService->removeItem($this->orderId, $productId);
        if ($removeResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($removeResult->getError()));
        }

        DB::commit();
    }

    public function confirmOrder(IIdentityAndAccessService $identityAndAccessService, IOrderService $orderService)
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        DB::beginTransaction();

        $authResult = $identityAndAccessService->authorize($this->email, $this->password, $this->orderId);
        if($authResult->isFailure()){
            DB::rollBack();
            return session()->flash('alert-auth', ErrorHandler::getErrorMessage($authResult->getError()));
        }

        $encrypted = $authResult->getValue();
        $confirmResult = $orderService->confirmOrder($this->orderId, auth()->user()->id, $encrypted);
        if($confirmResult->isFailure()){
            DB::rollBack();
            return session()->flash('alert-auth', ErrorHandler::getErrorMessage($confirmResult->getError()));
        }

        DB::commit();

        $this->dispatch('order-confirmed');
    }

    private function getOrder()
    {
        return DB::table('orders')->where('order_id', $this->orderId)->first();
    }

    private function getPaymentMethods()
    {
        return DB::table('payment_methods')
            ->where('order_id', $this->orderId)
            ->get();;
    }

    private function getItems()
    {
        $items =  DB::table('line_items')
            ->where('order_id', $this->orderId)
            ->get();

        $this->reset('prices', 'quantities');
        foreach($items as $item){
            $this->prices[$item->product_id] = $item->price / 100;
            $this->quantities[$item->product_id] = $item->quantity;
        }

        return $items;
    }

    private function getItem($productId)
    {
        return DB::table('line_items')
            ->where('order_id', $this->orderId)
            ->where('product_id', $productId)
            ->first();
    }

    public function getCustomer()
    {
        $order = $this->getOrder();

        return DB::table('customers')
            ->where('id', $order->customer_id)
            ->first();
    }

    public function getAssistant()
    {
        $order = $this->getOrder();

        return DB::table('users')
            ->where('id', $order->assistant_id)
            ->first();
    }

    public function getCashier()
    {
        $order = $this->getOrder();

        return DB::table('users')
            ->where('id', $order->cashier)
            ->first();
    }

    public function getProduct($productId)
    {
        return Product::find($productId);
    }

    private function getProducts()
    {
        $items = $this->getItems();

        $ids = [];
        foreach($items as $item){
            $ids[] = $item->product_id;
        }

        $branchId = $this->branch;
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
            ->whereNotIn('products.id', $ids)
            ->orderBy('quantity');

        return $query->paginate(3);
    }

    public function newPaymentMethod(): void
    {
        $this->paymentMethods[] = 'Cash';
        $this->referenceNumbers[] = '';
        $this->amounts[] = null;
    }

    public function removePaymentMethod(int $index): void
    {
        unset($this->paymentMethods[$index]);
        unset($this->referenceNumbers[$index]);
        unset($this->amounts[$index]);

        $this->paymentMethods = array_values($this->paymentMethods);
        $this->referenceNumbers = array_values($this->referenceNumbers);
        $this->amounts = array_values($this->amounts);
    }

    public function submitPayment()
    {
        $this->validate([
            'receiptNumber' => 'required',
            'amounts.*' => 'required|numeric',
            'referenceNumbers.*' => 'required',
        ], [
            'amounts.*' => 'Amount is required',
            'referenceNumbers.*' => 'Reference Number is required',
        ]);

        $order = $this->getOrder();

        $total = array_sum($this->amounts) * 100;

        if($order->total != $total)
        {
            $this->addError('total', 'Payment total should be equal to the order total');
            return;
        }

        DB::beginTransaction();
        try{
            for($i = 0; $i < count($this->amounts); $i++){
                DB::table('payment_methods')
                    ->insert([
                        'method' => $this->paymentMethods[$i],
                        'reference' => $this->referenceNumbers[$i],
                        'amount' => $this->amounts[$i],
                        'order_id' => $this->orderId,
                        'user_id' => auth()->user()->id
                    ]);
            }

            DB::table('orders')
                ->where('order_id', $this->orderId)
                ->update([
                    'completed_at' => now(),
                    'receipt_number' => $this->receiptNumber,
                    'cashier' => auth()->user()->id
                ]);
        }catch(Exception $e){
            DB::rollBack();
            report($e);
            return session()->flash('alert', ErrorHandler::getErrorMessage($e));
        }

        DB::commit();
        $this->redirect(route('admin.order.details', ['order_id' => $this->orderId]), true);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.order-cashier-details', [
            'order' => $this->getOrder(),
            'products' => $this->getProducts(),
            'cartItems' => $this->getItems(),
            'customer' => $this->getCustomer(),
            'assistant' => $this->getAssistant(),
            'cashier' => $this->getCashier(),
        ]);
    }
}

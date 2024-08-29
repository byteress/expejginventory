<?php

namespace App\Livewire\Admin\Order;

use Akaunting\Money\Money;
use App\Exceptions\ErrorHandler;
use DeliveryContracts\IDeliveryService;
use DeliveryContracts\Utils\Result;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use OrderContracts\IOrderService;
use PaymentContracts\IPaymentService;
use ProductManagement\Models\Product;
use StockManagementContracts\IStockManagementService;
use Str;

#[Title('Order Details')]
class OrderDetails extends Component
{
    use WithPagination;

    public $orderId;
    public $order2;
    public $cartItems2 = [];
    public $customer;

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
    public $credit = [false];

    public $completed = false;
    public string $paymentType = 'full';
    public int $months = 5;
    public $rate = 0;

    public $paymentMethodsCod = ['Cash'];
    public $referenceNumbersCod = [''];
    public $amountsCod = [null];
    public $receiptNumberCod = '';

    public $completedCod = false;
    public string $orderType = '';

    public string $deliveryType = 'pickup';
    #[Validate('required_if:deliveryType,deliver')]
    public float $deliveryFee = 0.0;
    public ?string $deliveryAddress = null;
    public bool $sameAddress = true;

    public function mount(string $order_id): void
    {
        $this->orderId = $order_id;

        $order = $this->getOrder();
        $this->order2 = $order;
        $this->completed = $order->status;
        $this->completedCod = $order->status == 2;
        $this->branch = $order->branch_id;
        $this->receiptNumber = $order->receipt_number;
        $this->paymentType = $order->payment_type ?? 'full';
        $this->months = $order->months ?? 5;
        $this->rate = $order->rate ?? 0;
        $this->orderType = $order->order_type;
        $this->deliveryType = $order->delivery_type;
        $this->deliveryFee = $order->delivery_fee / 100;
        $this->deliveryAddress = $order->delivery_address;

//        if($order->order_type != 'regular'){
//            $this->paymentType = 'cod';
//        }

        $this->getPaymentMethods();

        if($this->completedCod){


            $paymentMethods = $this->getCodPaymentMethods();
            if($paymentMethods->isNotEmpty()){
                $this->paymentMethodsCod = [];
                $this->referenceNumbersCod = [];
                $this->amountsCod = [];

                foreach($paymentMethods as $methods){
                    $this->referenceNumbersCod[] = $methods->reference;
                    $this->paymentMethodsCod[] = $methods->method;
                    $this->amountsCod[] = $methods->amount / 100;
                }
            }
        }

        $items = $this->getItems();
        foreach ($items as $item) {
            $this->calculateDiscount($item->product_id, $item->price);
        }

        $this->cartItems2 = $this->getItems();
        $this->customer = $this->getCustomer();

        $this->sameAddress = $order->delivery_address == $this->customer->address;
    }

    public function calculateDiscount(string $productId, int $price): void
    {
        $product = $this->getProduct($productId);

        $discount = 0;
        if($product->sale_price > $price)
            $discount = ($product->sale_price - $price) / $product->sale_price;

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
        $discount = $product->sale_price * $rate;
        $discountedPrice = $product->sale_price - $discount;

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
            auth()->user()->id, false
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
            auth()->user()->id,
            $this->orderType != 'regular'
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
            auth()->user()->id, false
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
            auth()->user()->id,
            $this->orderType != 'regular'
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

    public function addItem(IStockManagementService $stockManagementService, IOrderService $orderService, $productId, $type = 'regular')
    {
        $this->resetErrorBag();
        if($type != $this->orderType){
            session()->flash('alert', "Can only add items for $this->orderType order.");
            return;
        }

        $product = $this->getProduct($productId);

        DB::beginTransaction();

        $newReservationId = Str::uuid()->toString();
        $reserveResult = $stockManagementService->reserve(
            $productId,
            $newReservationId,
            1,
            $this->branch,
            auth()->user()->id,
            $type != 'regular'
        );

        if ($reserveResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($reserveResult->getError()));
        }

        $addResult = $orderService->addItem($this->orderId, $productId, "{$product->model} {$product->description}", $product->sale_price, 1, $newReservationId);
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
            auth()->user()->id, false
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

    private function getCancelledOrder()
    {
        $order = $this->order2;
        return DB::table('orders')->where('order_id', $order->cancelled_order_id)->first();
    }

    private function getPaymentMethods()
    {
        $paymentMethods = [];
        $order = $this->order2;
        $transaction = DB::table('transactions')->where('order_id', $this->orderId)->first();
        $fromCancelledOrder = false;

        if ($transaction){
            $query = DB::table('payment_methods')
                ->where('order_id', $this->orderId)
                ->where('transaction_id', $transaction->id);

            $paymentMethods = $query->get();
        }else if($order->cancelled_order_id){
            $transaction = DB::table('transactions')->where('order_id', $order->cancelled_order_id)->first();

            if($transaction){
                $query = DB::table('payment_methods')
                    ->where('order_id', $order->cancelled_order_id)
                    ->where('transaction_id', $transaction->id);

                $paymentMethods = $query->get();
                $fromCancelledOrder = true;
            }
        }

        if(!empty($paymentMethods)){
            $this->paymentMethods = [];
            $this->referenceNumbers = [];
            $this->amounts = [];
            $this->credit = [];

            foreach($paymentMethods as $methods){
                $this->referenceNumbers[] = $methods->reference;
                $this->paymentMethods[] = $methods->method;
                $this->amounts[] = $methods->amount / 100;
                if($fromCancelledOrder){
                    $this->credit[] = true;
                }else{
                    $this->credit[] = $methods->credit;
                }
            }
        }
    }

    private function getCodPaymentMethods()
    {
        $transaction = DB::table('transactions')->where('order_id', $this->orderId)->latest()->first();
        $this->receiptNumberCod = $transaction->or_number;

        return DB::table('payment_methods')
            ->where('order_id', $this->orderId)
            ->where('transaction_id', $transaction->id)
            ->get();
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
        $order = $this->order2;

        return DB::table('customers')
            ->where('id', $order->customer_id)
            ->first();
    }

    public function getAssistant()
    {
        $order = $this->order2;

        return DB::table('users')
            ->where('id', $order->assistant_id)
            ->first();
    }

    public function getCashier()
    {
        $order = $this->order2;

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
        $this->credit[] = false;
    }

    public function newPaymentMethodCod(): void
    {
        $this->paymentMethodsCod[] = 'Cash';
        $this->referenceNumbersCod[] = '';
        $this->amountsCod[] = null;
    }


    public function removePaymentMethod(int $index): void
    {
        unset($this->paymentMethods[$index]);
        unset($this->referenceNumbers[$index]);
        unset($this->amounts[$index]);
        unset($this->credit[$index]);

        $this->paymentMethods = array_values($this->paymentMethods);
        $this->referenceNumbers = array_values($this->referenceNumbers);
        $this->amounts = array_values($this->amounts);
        $this->credit = array_values($this->credit);
    }

    public function removePaymentMethodCod(int $index): void
    {
        unset($this->paymentMethodsCod[$index]);
        unset($this->referenceNumbersCod[$index]);
        unset($this->amountsCod[$index]);

        $this->paymentMethodsCod = array_values($this->paymentMethodsCod);
        $this->referenceNumbersCod = array_values($this->referenceNumbersCod);
        $this->amountsCod = array_values($this->amountsCod);
    }

    /**
     * @throws \Throwable
     */
    public function submitPayment(IPaymentService $paymentService, IDeliveryService $deliveryService): void
    {
        $this->resetErrorBag();
        $this->validate([
            'deliveryAddress' => [
                Rule::requiredIf($this->deliveryType == 'deliver' && !$this->sameAddress)
            ]
        ]);

        $order = $this->order2;
        $cancelledOrder = $this->getCancelledOrder();

        if($cancelledOrder && $order->total < $cancelledOrder->total){
            $this->addError('total', 'Order total should be equal or greater than the previous amount of ' . Money::PHP($cancelledOrder->total));
            return;
        }

        if($this->paymentType == 'full'){
            $this->fullPayment($paymentService, $deliveryService);
        }else if($this->paymentType == 'installment'){
            $this->installmentPayment($paymentService, $deliveryService);
        }else if($this->paymentType == 'cod'){
            $this->codPayment($paymentService);
        }
    }

    private function placeDeliveryOrder(IDeliveryService $deliveryService): Result
    {
        $items = [];
        foreach ($this->cartItems2 as $item) {
            $items[] = [
                'productId' => $item->product_id,
                'title' => $item->title,
                'quantity' => $item->quantity,
                'reservationId' => $item->reservation_id,
            ];
        }

        $address = $this->customer->address;
        if(!$this->sameAddress) $address = $this->deliveryAddress;

        $fee = (float) $this->deliveryType;
        
        return $deliveryService->placeOrder($this->orderId, $items, $fee * 100, $this->branch, $this->deliveryFee, $address);
    }

    /**
     * @throws \Throwable
     */
    public function codPayment(IPaymentService $paymentService): void
    {
        DB::beginTransaction();

        $order = $this->order2;

        $downPayment = [];
        for($i = 0; $i < count($this->amounts); $i++){
            if($this->amounts[$i] > 0) {
                $downPayment[] = [
                    'amount' => $this->amounts[$i] * 100,
                    'reference' => $this->referenceNumbers[$i],
                    'method' => $this->paymentMethods[$i],
                ];
            }
        }

        $result = $paymentService->requestCod(
            $order->customer_id,
            $order->total,
            $order->order_id,
            $downPayment,
            auth()->user()?->id,
            Str::uuid()->toString(),
            $this->receiptNumber,
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        $this->redirect(route('admin.order.details', ['order_id' => $this->orderId]), true);
    }

    /**
     * @throws \Throwable
     */
    public function installmentPayment(IPaymentService $paymentService, IDeliveryService $deliveryService): void
    {
        DB::beginTransaction();

        $order = $this->order2;

        $deliveryResult = $this->placeDeliveryOrder($deliveryService);
        if($deliveryResult->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($deliveryResult->getError()));
            return;
        }

        $downPayment = [];
        for($i = 0; $i < count($this->amounts); $i++){
            if($this->amounts[$i] > 0) {
                $downPayment[] = [
                    'amount' => $this->amounts[$i] * 100,
                    'reference' => $this->referenceNumbers[$i],
                    'method' => $this->paymentMethods[$i],
                    'credit' => $this->credit[$i],
                ];
            }
        }

        $result = $paymentService->initializeInstallment(
            $order->customer_id,
            Str::uuid()->toString(),
            $order->total,
            $this->months,
            $this->rate,
            $order->order_id,
            $downPayment,
            auth()->user()?->id,
            Str::uuid()->toString(),
            $this->receiptNumber,
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        $this->redirect(route('admin.order.details', ['order_id' => $this->orderId]), true);
    }

    /**
     * @throws \Throwable
     */
    public function fullPayment(IPaymentService $paymentService, IDeliveryService $deliveryService): void
    {
        $this->validate([
            'receiptNumber' => 'required',
            'amounts.*' => 'required|numeric',
            'referenceNumbers.*' => 'required',
        ], [
            'amounts.*' => 'Amount is required',
            'referenceNumbers.*' => 'Reference Number is required',
        ]);

        $order = $this->order2;

        $total = array_sum($this->amounts) * 100;

        if(($order->total + $this->deliveryFee * 100) != $total)
        {
            $this->addError('total', 'Payment total should be equal to the order total');
            return;
        }

        DB::beginTransaction();

        $deliveryResult = $this->placeDeliveryOrder($deliveryService);
        if($deliveryResult->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($deliveryResult->getError()));
            return;
        }

        $fullPayment = [];
        for($i = 0; $i < count($this->amounts); $i++){
            if($this->amounts[$i] > 0) {
                $fullPayment[] = [
                    'amount' => $this->amounts[$i] * 100,
                    'reference' => $this->referenceNumbers[$i],
                    'method' => $this->paymentMethods[$i],
                    'credit' => $this->credit[$i],
                ];
            }
        }

        $result = $paymentService->pay(
            $order->customer_id,
            $fullPayment,
            auth()->user()?->id,
            Str::uuid()->toString(),
            $this->receiptNumber,
            $order->order_id
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        $this->redirect(route('admin.order.details', ['order_id' => $this->orderId]), true);
    }

    /**
     * @throws \Throwable
     */
    public function submitCodPayment(IPaymentService $paymentService): void
    {
        $this->validate([
            'receiptNumberCod' => 'required',
            'amountsCod.*' => 'required|numeric',
            'referenceNumbersCod.*' => 'required',
        ], [
            'amountsCod.*' => 'Amount is required',
            'referenceNumbersCod.*' => 'Reference Number is required',
        ]);

        $order = $this->order2;

        $total = $order->total - (array_sum($this->amounts) * 100);
        $codTotal = array_sum($this->amountsCod) * 100;

        if($codTotal != $total)
        {
            $this->addError('totalCod', 'Full payment should be equal to the order balance');
            return;
        }

        DB::beginTransaction();

        $downPayment = [];
        for($i = 0; $i < count($this->amountsCod); $i++){
            if($this->amountsCod[$i] > 0) {
                $downPayment[] = [
                    'amount' => $this->amountsCod[$i] * 100,
                    'reference' => $this->referenceNumbersCod[$i],
                    'method' => $this->paymentMethodsCod[$i],
                ];
            }
        }

        $result = $paymentService->payCod(
            $order->customer_id,
            $downPayment,
            auth()->user()?->id,
            Str::uuid()->toString(),
            $this->receiptNumberCod,
            $order->order_id
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        $this->redirect(route('admin.order.details', ['order_id' => $this->orderId]), true);
    }

    public function getTransaction()
    {
        return DB::table('transactions')
            ->where('order_id', $this->orderId)
            ->first();
    }

    public function generateString(string $string1, float $amount, int $totalLength = 40): string
    {
        $string2 = Money::PHP($amount);
        $spaceCount = $totalLength - strlen($string1) - strlen($string2);
        if ($spaceCount < 0) {
            $spaceCount = 0; // If strings are too long, no space is added
        }

        return $string1 . str_repeat(' ', $spaceCount) . $string2;
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.order-details', [
            'order' => $this->getOrder(),
            'cartItems' => $this->getItems(),
            'cancelled' => $this->getCancelledOrder(),
            'products' => $this->getProducts(),
            'assistant' => $this->getAssistant(),
            'cashier' => $this->getCashier(),
            'transaction' => $this->getTransaction(),
        ]);
    }
}

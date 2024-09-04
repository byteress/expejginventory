<?php

namespace App\Livewire\Admin\Order;

use App\Exceptions\ErrorHandler;
use ExpenseManagementContracts\Enums\Expense;
use ExpenseManagementContracts\IExpenseManagementService;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use OrderContracts\IOrderService;
use StockManagementContracts\IStockManagementService;
use Throwable;

#[Title('Orders')]
class Orders extends Component
{
    use WithPagination;

    #[Url]
    public $search;

    public $branch;
    public $type;
    public $status;
    public $displayStatus;

    #[Validate('required|email')]
    public string $email;
    #[Validate('required')]
    public string $password;
    public ?string $notes = null;

    public function mount(string $type, string $status): void
    {
        $this->branch = auth()->user()->branch_id;
        $this->type = $type;
        $this->displayStatus = $status;

        switch ($status){
            case 'pending':
                $this->status = [0];
                break;
            case 'processed':
                $this->status = [1, 2];
                break;
            case 'cancelled':
                $this->status = [3];
                break;
            case 'refunded':
                $this->status = [4];
                break;
        }
    }

    /**
     * @throws Throwable
     */
    public function cancelOrder(
        IOrderService $orderService,
        IIdentityAndAccessService $identityAndAccessService,
        IStockManagementService $stockManagementService,
        string $orderId
    ): void
    {
        $this->validate();

        DB::beginTransaction();

        $authorization = $identityAndAccessService->authorize($this->email, $this->password, "cancel-$orderId");
        if($authorization->isFailure()){
            DB::rollBack();
            session()->flash('alert-auth', ErrorHandler::getErrorMessage($authorization->getError()));
            return;
        }

        $result = $orderService->cancel($orderId, auth()->user()->id, $authorization->getValue(), $this->notes);

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert-auth', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $order = $this->getOrder($orderId);
        $lineItems = $this->getItems($orderId);
        $items = [];
        foreach ($lineItems as $item){
            $reservationId = Str::uuid()->toString();
            $reservationResult = $stockManagementService->reserve($item->product_id, $reservationId, $item->quantity, $order->branch_id, auth()->user()->id);
            if($reservationResult->isFailure()){
                DB::rollBack();
                session()->flash('alert-auth', ErrorHandler::getErrorMessage($reservationResult->getError()));
                return;
            }

            $items[] = [
                'productId' => $item->product_id,
                'quantity' => $item->quantity,
                'title' => $item->title,
                'price' => $item->price,
                'reservationId' => $reservationId,
                'priceType' => $item->price_type,
            ];
        }

        $authorizationResult = $identityAndAccessService->authorize($this->email, $this->password, serialize($items));
        if($authorizationResult->isFailure()){
            DB::rollBack();
            $this->reset('username', 'password');
            session()->flash('alert-authorization', ErrorHandler::getErrorMessage($authorizationResult->getError()));
            return;
        }

        $newOrderId = Str::uuid()->toString();
        $placeOrderResult = $orderService->placeOrder(
            $newOrderId,
            $order->customer_id,
            $order->assistant_id,
            $order->branch_id,
            $items,
            $order->order_type,
            $authorizationResult->getValue(),
            $order->order_id
        );

        if($placeOrderResult->isFailure()){
            DB::rollBack();
            session()->flash('alert-auth', ErrorHandler::getErrorMessage($placeOrderResult->getError()));
            return;
        }

        DB::commit();

        $this->redirect(route('admin.order.details', ['order_id' => $newOrderId]));
    }

    /**
     * @throws Throwable
     */
    public function refundOrder(
        IOrderService $orderService,
        IIdentityAndAccessService $identityAndAccessService,
        IExpenseManagementService $expenseManagementService,
        string $orderId
    ): void
    {
        $this->validate();

        DB::beginTransaction();

        $authorization = $identityAndAccessService->authorize($this->email, $this->password, "refund-$orderId");
        if ($authorization->isFailure()) {
            DB::rollBack();
            session()->flash('alert-auth', ErrorHandler::getErrorMessage($authorization->getError()));
            return;
        }

        $result = $orderService->refund($orderId, auth()->user()->id, $authorization->getValue(), $this->notes);

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert-auth', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $order = $this->getOrder($orderId);

        $amount = $order->total + $order->delivery_fee;
        $orNumber = str_pad((string) $order->id, 12, '0', STR_PAD_LEFT);
        $expenseResult = $expenseManagementService->create(
            Str::uuid()->toString(),
            date('Y-m-d'),
            Expense::REFUND,
            $amount,
            "Refunded Order# $orNumber",
            auth()->user()->id,
            $order->branch_id
        );

        if($expenseResult->isFailure()){
            DB::rollBack();
            session()->flash('alert-auth', ErrorHandler::getErrorMessage($expenseResult->getError()));
            return;
        }

        DB::commit();

        $this->dispatch('close-modal');
        session()->flash('success', 'Order refunded successfully.');
    }

    #[On('modal-hidden')]
    public function modelHidden()
    {
        $this->reset('email', 'password', 'notes');
        $this->resetErrorBag();
    }

    public function getOrders()
    {
        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
            ->where(function($q){
                $q->where('branches.name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('customers.first_name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('customers.last_name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('users.first_name', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('users.last_name', 'LIKE', '%'.$this->search.'%');
            })
            ->where('orders.order_type', $this->type)
            ->whereIn('orders.status', $this->status)
            ->orderByDesc('orders.placed_at');

        if($this->branch) $query = $query->where('branches.id', $this->branch);

        return $query->paginate(10);
    }

    private function getItems(string $orderId)
    {
        return DB::table('line_items')
            ->where('order_id', $orderId)
            ->get();
    }

    private function getOrder(string $orderId)
    {
        return DB::table('orders')
            ->where('order_id', $orderId)
            ->first();
    }

    public function getPaymentStatus(string $orderId): string
    {
        $order = DB::table('orders')->where('order_id', $orderId)->first();
        if(!$order) return 'Pending';

        if($order->payment_type == 'full'){
            if($order->status > 0) return 'Fully Paid';
        }

        if($order->payment_type == 'cod'){
            if($order->status == 2) return 'Fully Paid';
            if($order->status == 1) return 'Partially Paid';
        }

        if($order->payment_type == 'installment'){
            $exists = DB::table('installment_bills')
                ->where('order_id', $orderId)
                ->where('balance', '>', 0)
                ->exists();

            if(!$exists) return 'Fully Paid';

            return 'Partially Paid';
        }

        return 'Pending';
    }

    public function getDeliveryStatus(string $orderId): string
    {
        $order  = $this->getOrder($orderId);
        if(!$order) return 'Pending';

        if($order->delivery_type == 'pickup') return 'For Pickup';

        $delivered = DB::table('delivery_items')
            ->where('order_id', $orderId)
            ->sum('delivered');

        $outForDelivery = DB::table('delivery_items')
            ->where('order_id', $orderId)
            ->sum('out_for_delivery');

        $toShip = DB::table('delivery_items')
            ->where('order_id', $orderId)
            ->sum('to_ship');

        if($delivered > 0 && $outForDelivery == 0 && $toShip == 0) return 'Delivered';
        if($delivered > 0 && ($outForDelivery > 0 || $toShip > 0)) return 'Partially Delivered';
        if($delivered == 0 && $outForDelivery > 0) return 'Out For Delivery';

        return 'To Ship';
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.orders', [
            'orders' => $this->getOrders()
        ]);
    }
}

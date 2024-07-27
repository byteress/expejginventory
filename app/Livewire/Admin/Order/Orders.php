<?php

namespace App\Livewire\Admin\Order;

use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Order\Models\Order\Order;
use OrderContracts\IOrderService;
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
        }
    }

    /**
     * @throws Throwable
     */
    public function cancelOrder(IOrderService $orderService, string $orderId): void
    {
        DB::beginTransaction();

        $result = $orderService->cancel($orderId, auth()->user()->id);

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash('success', __('Order has been canceled'));
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

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.orders', [
            'orders' => $this->getOrders()
        ]);
    }
}

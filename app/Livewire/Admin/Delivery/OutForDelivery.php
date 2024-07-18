<?php

namespace App\Livewire\Admin\Delivery;

use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use OrderContracts\IOrderService;

#[Title('Out for Delivery')]
class OutForDelivery extends Component
{

    /**
     * @throws \Throwable
     */
    public function markAsDelivered(IOrderService $orderService, string $orderId): void
    {
        DB::beginTransaction();

        $result = $orderService->markAsDelivered($orderId);

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash('success', 'Order marked as delivered.');
    }

    public function getOrders()
    {
        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'customers.address as customer_address', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
            ->where('shipping_status', 1);

        if(auth()->user()->branch_id){
            $query = $query->where('orders.branch_id', auth()->user()->branch_id);
        }

        return $query->get();
    }

    public function getItems($id)
    {
        return DB::table('line_items')
            ->where('order_id', $id)
            ->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.delivery.out-for-delivery', [
            'orders' => $this->getOrders()
        ]);
    }
}

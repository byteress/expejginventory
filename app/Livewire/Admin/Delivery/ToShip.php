<?php

namespace App\Livewire\Admin\Delivery;

use App\Exceptions\ErrorHandler;
use App\Models\User;
use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use OrderContracts\IOrderService;

#[Title('To Ship')]
class ToShip extends Component
{
    #[Validate('required')]
    public $branch = null;

    #[Validate('required')]
    public $driver = null;

    #[Validate('required')]
    public $truck = null;
    public string $notes;

    #[Validate('required')]
    public $toShip = [];

    public function mount()
    {
        $this->branch = auth()->user()->branch_id;
    }

    /**
     * @throws \Throwable
     */
    public function shipOrders(IOrderService $orderService): void
    {
        $this->resetErrorBag();
        $this->validate();

        $user = auth()->user();
        if(!$user) return;

        $orders = [];
        foreach ($this->toShip as $key => $value) {
            if($value) $orders[] = $key;
        }

        if(empty($orders)){
            $this->addError('toShip', 'You must add at least one order');
        }

        DB::beginTransaction();
        $result = $orderService->shipOrders(
            \Str::uuid()->toString(),
            $this->driver,
            $this->truck,
            $this->branch,
            $orders,
            $this->notes
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash('success', 'Orders shipped.');
    }

    public function getOrders()
    {
        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'customers.address as customer_address', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('line_items')
                    ->whereRaw('orders.order_id = line_items.order_id')
                    ->where('line_items.confirmed', 0);
            })->where('shipping_status', 0);

        if($this->branch){
            $query = $query->where('orders.branch_id', $this->branch);
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
        return view('livewire.admin.delivery.to-ship', [
            'orders' => $this->getOrders(),
            'branches' => Branch::all(),
            'drivers' => User::role('delivery')->where('branch_id', $this->branch)->get()
        ]);
    }
}

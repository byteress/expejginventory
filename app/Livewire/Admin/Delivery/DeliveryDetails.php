<?php

namespace App\Livewire\Admin\Delivery;

use Akaunting\Money\Money;
use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use Delivery\Models\Delivery\Enums\DeliveryStatus;
use DeliveryContracts\IDeliveryService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use ProductManagement\Models\Product;
use Throwable;

#[Title('Delivery Details')]
class DeliveryDetails extends Component
{
    public string $deliveryId;

    public $quantities = [];
    public object $delivery;
    public string $notes = '';

    public function mount(string $delivery_id): void
    {
        $this->deliveryId = $delivery_id;
        $this->delivery = $this->getDelivery($delivery_id);
        $this->notes = $this->delivery->notes ?? '';

        $orders = $this->getOrders();
        foreach ($orders as $order) {
            foreach($this->getItems($order->order_id) as $item){
                $this->quantities[$order->order_id][$item->product_id] = $item->quantity;
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function setAsDelivered(IDeliveryService $deliveryService): void
    {
        $this->validate([
            'quantities.*.*' => 'required',
        ], [
            'quantities.*.*.required' => 'The quantity field is required.',
        ]);

        $items = [];
        foreach ($this->quantities as $orderId => $quantities) {
            foreach($quantities as $productId => $quantity) {
                    $items[] = [
                        'orderId' => $orderId,
                        'productId' => $productId,
                        'quantity' => $quantity,
                    ];
            }
        }

        DB::beginTransaction();

        $result = $deliveryService->setItemsAsDelivered($this->deliveryId, $this->delivery->branch_id, $items);
        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash('success', 'Delivery marked as complete');
        $this->redirect(route('admin.delivery.details', ['delivery_id' => $this->deliveryId]), true);
    }

    public function updateNotes(IDeliveryService $deliveryService): void
    {
        $this->validate([
            'notes' => 'required'
        ]);

        $deliveryService->updateNotes($this->deliveryId, $this->notes);
    }

    public function getStatus(int $status): string
    {
        return DeliveryStatus::from($status)->displayName();
    }

    /**
     * @return Collection<int, object>
     */
    public function getOrders(): Collection
    {
         return DB::table('attempt_items')
             ->join('orders', 'orders.order_id', '=', 'attempt_items.order_id')
             ->join('customers', 'orders.customer_id', '=', 'customers.id')
             ->join('branches', 'orders.branch_id', '=', 'branches.id')
             ->join('users', 'orders.assistant_id', '=', 'users.id')
             ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'customers.phone as customer_phone', 'orders.delivery_address as customer_address', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
             ->where('attempt_items.delivery_id', $this->deliveryId)
             ->distinct()
             ->get();
    }

    /**
     * @param string $id
     * @return Collection<int, object>
     */
    public function getItems(string $id): Collection
    {
        return DB::table('attempt_items')
            ->where('order_id', $id)
            ->where('delivery_id', $this->deliveryId)
            ->get();
    }

    public function getDelivery(string $deliveryId): object|null
    {
        return DB::table('deliveries')
            ->join('branches', 'deliveries.branch_id', '=', 'branches.id')
            ->join('users', 'deliveries.driver', '=', 'users.id')
            ->select(['deliveries.*', 'branches.name as branch_name', 'users.first_name as driver_first_name', 'users.last_name as driver_last_name'])
            ->where('delivery_id', $deliveryId)
            ->first();
    }

    public function getProductTitle(string $orderId, string $productId): string
    {
        $result = DB::table('delivery_items')
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->first();

        if(!$result) return '';

        return $result->title;
    }

    public function getProductSupplierCode(string $productId): string
    {
        $product = Product::find($productId);
        if(!$product) return '';

        return $product->supplier->code;
    }

    public function getCodAmount(string $orderId): string
    {
        $result = DB::table('cod_balances')
            ->where('order_id', $orderId)
            ->first();

        if(!$result) return '';
        if($result->balance == 0) return '';

        return Money::PHP($result->balance);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.delivery.delivery-details', [
            'orders' => $this->getOrders(),
            'delivery' => $this->getDelivery($this->deliveryId)
        ]);
    }
}

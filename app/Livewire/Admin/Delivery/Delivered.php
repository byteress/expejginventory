<?php

namespace App\Livewire\Admin\Delivery;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Delivered')]
class Delivered extends Component
{
    use WithPagination;

    public $branch = null;

    public function mount(): void
    {
        $this->branch = auth()->user()->branch_id;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function getOrders(): LengthAwarePaginator
    {
        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'customers.address as customer_address', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('delivery_items')
                    ->whereRaw('orders.order_id = delivery_items.order_id')
                    ->where('delivery_items.delivered', '>', 0);
            });

        if($this->branch){
            $query = $query->where('orders.branch_id', $this->branch);
        }

        return $query->paginate(10);
    }

    /**
     * @param string $id
     * @return Collection<int, object>
     */
    public function getItems(string $id): Collection
    {
        return DB::table('delivery_items')
            ->where('order_id', $id)
            ->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.delivery.delivered', [
            'orders' => $this->getOrders()
        ]);
    }
}

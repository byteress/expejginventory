<?php

namespace App\Livewire\Admin;

use BranchManagement\Models\Branch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{

    public function getIncome(string $branchId): int
    {
        $totalAmount = 0;
        foreach ($this->getTransactions($branchId) as $transaction) {
            if($transaction->status == 1 || (!$this->isSameDayCancelled($transaction->order_id) && !$this->isSameDayRefunded($transaction->order_id))){
                $totalAmount = $totalAmount + $transaction->total + $transaction->delivery_fee;
            }
        }

        return $totalAmount;
    }

    /**
     * @return Collection<int, object>
     */
    public function getTransactions(string $branchId): Collection
    {
        $date = now()->format('Y-m-d');

        $query = DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(['transactions.id as transaction_id', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*'])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['full', 'down'])
            ->where('orders.previous', 0)
            ->where('orders.branch_id', $branchId);

        return $query->get();
    }

    public function isSameDayCancelled(string $orderId): bool
    {
        $order = DB::table('orders')
            ->where('order_id', $orderId)
            ->first();

        if(!$order || !$order->completed_at) return false;

        $newOrder = DB::table('orders')
            ->where('cancelled_order_id', $orderId)
            ->first();

        if(!$newOrder || !$newOrder->completed_at) return false;

        $orderDate = date('Y-m-d', strtotime($order->completed_at));
        $newOrderDate = date('Y-m-d', strtotime($newOrder->completed_at));

        if($orderDate != $newOrderDate) return false;

        return true;
    }

    public function isSameDayRefunded(string $orderId): bool
    {
        $order = DB::table('orders')
            ->where('order_id', $orderId)
            ->first();

        if(!$order || !$order->completed_at) return false;

        $orderNumber = str_pad((string) $order->id, 12, '0', STR_PAD_LEFT);
        $expense = DB::table('expenses')
            ->where('description', "Refunded Order# $orderNumber")
            ->first();

        if(!$expense || !$expense->created_at) return false;

        $orderDate = date('Y-m-d', strtotime($order->completed_at));
        $newOrderDate = date('Y-m-d', strtotime($expense->created_at));

        if($orderDate != $newOrderDate) return false;

        return true;
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'branches' => Branch::all(),
        ]);
    }
}

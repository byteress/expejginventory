<?php

namespace App\Livewire\Admin\Reports;

use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Reports')]
class Reports extends Component
{
    #[Url]
    public ?string $date = null;

    /**
     * @throws Exception
     */
    public function changeDate(string $action): void
    {
        $date = $this->date ?? now()->format('Y-m-d');

        if($action == 'increment'){
            $this->date = (new DateTime($date))->modify('+1 day')->format('Y-m-d');
        }else{
            $this->date = (new DateTime($date))->modify('-1 day')->format('Y-m-d');
        }

        $this->redirect(route('admin.reports.daily', [
            'date' => $this->date
        ]), true);
    }

    #[On('date-set')]
    public function setDate(string $date): void
    {
        $this->redirect(route('admin.reports.daily', [
            'date' => $date
        ]), true);
    }

    /**
     * @return Collection<int, object>
     */
    public function getTransactions(): Collection
    {
        $date = $this->date ?? now()->format('Y-m-d');

        return DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(['transactions.id as transaction_id', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*'])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['full', 'down'])
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getCollections(): Collection
    {
        $date = $this->date ?? now()->format('Y-m-d');

        return DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(['transactions.id as transaction_id', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*'])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['installment', 'cod'])
            ->get();
    }

    /**
     * @param string $orderId
     * @return Collection<int, object>
     */
    public function getItems(string $orderId): Collection
    {
        return DB::table('line_items')
            ->where('order_id', $orderId)
            ->get();
    }

    public function getReceiptType($order): string
    {
        if($order->payment_type == 'installment') return 'CI';
        if($order->order_type != 'regular') return 'CI';

        $orderTotal = $order->total + $order->delivery_fee;
        if($orderTotal >= 1000000) return 'DR';

        return 'SI';
    }

    public function getDiscount($orderId)
    {
        $price = 0;
        $originalPrice = 0;
        foreach ($this->getItems($orderId) as $item) {
            $price = $price + ($item->price * $item->quantity);
            $originalPrice = $originalPrice + ($item->original_price * $item->quantity);
        }

        return $originalPrice - $price;
    }

    public function getPaymentAmount(string $transactionId, string $type): int
    {
        return DB::table('payment_methods')
            ->where('transaction_id', $transactionId)
            ->where('method', $type)
            ->where('credit', 0)
            ->sum('amount');
    }

    public function getPaymentAmountTotal(string $type): int
    {
        $date = $this->date ?? now()->format('Y-m-d');

        return DB::table('payment_methods')
            ->join('transactions', 'payment_methods.transaction_id', '=', 'transactions.id')
            ->whereDate('transactions.created_at', $date)
            ->where('payment_methods.method', $type)
            ->where('payment_methods.credit', 0)
            ->sum('payment_methods.amount');
    }

    /**
     * @return Collection<int, object>
     */
    public function getExpenses(): Collection
    {
        $date = $this->date ?? now()->format('Y-m-d');

        return DB::table('expenses')
            ->join('users', 'expenses.actor', '=', 'users.id')
            ->whereDate('expenses.date', $date)
            ->get();
    }

    public function getExpensesTotal(): int
    {
        $date = $this->date ?? now()->format('Y-m-d');

        return DB::table('expenses')
            ->whereDate('date', $date)
            ->sum('amount');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.reports.reports', [
            'transactions' => $this->getTransactions(),
            'collections' => $this->getCollections(),
            'expenses' => $this->getExpenses()
        ]);
    }
}

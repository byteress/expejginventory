<?php

namespace App\Livewire\Admin\Reports;

use BranchManagement\Models\Branch;
use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use ProductManagement\Models\Product;
use function Livewire\wrap;

#[Title('Reports')]
class Reports extends Component
{
    #[Url]
    public ?string $date = null;

    #[Session]
    public ?string $branch = null;

    public function mount(): void
    {
        $this->date = $this->date ?? now()->format('Y-m-d');
        $this->branch = $this->branch ?: auth()->user()->branch_id;
    }

    public function updatedBranch(string $branch): void
    {
        $this->branch = $branch;

        $this->redirect(route('admin.reports.daily', [
            'date' => $this->date
        ]), true);
    }

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

        $query = DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['transactions.id as transaction_id', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*', 'users.first_name as fname', 'users.last_name as lname'])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['full', 'down'])
            ->where('orders.previous', 0);

        if($this->branch) $query->where('orders.branch_id', $this->branch);

        return $query->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getCollections(): Collection
    {
        $date = $this->date ?? now()->format('Y-m-d');

        $query =  DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(['transactions.id as transaction_id', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*'])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['installment', 'cod']);

        if($this->branch) $query->where('orders.branch_id', $this->branch);

        return $query->get();
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
        $explode = explode('#', $order->receipt_number);

        return $explode[0] ?? '';
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
        $query = DB::table('payment_methods')
            ->join('orders', 'payment_methods.order_id', '=', 'orders.order_id')
            ->where('payment_methods.transaction_id', $transactionId)
            ->where('payment_methods.method', $type)
            ->where('payment_methods.credit', 0);

        if($this->branch) $query->where('orders.branch_id', $this->branch);

        return $query->sum('payment_methods.amount');
    }

    public function getCancelledPaymentAmount(string $orderId, string $cancelledOrderId, string $type): array
    {
        $cancelledOrder = DB::table('orders')
            ->where('order_id', $cancelledOrderId)
            ->first();

        $query = DB::table('payment_methods')
            ->join('orders', 'payment_methods.order_id', '=', 'orders.order_id')
            ->where('orders.order_id', $orderId)
            ->where('payment_methods.method', $type)
            ->where('payment_methods.credit', 1);

        $query2 = DB::table('payment_methods')
            ->join('orders', 'payment_methods.order_id', '=', 'orders.order_id')
            ->where('orders.order_id', $orderId)
            ->where('payment_methods.method', $type)
            ->where('payment_methods.credit', 0);

        return [
            'creditedAmount' => $query->sum('payment_methods.amount'),
            'amount' => $query2->sum('payment_methods.amount'),
            'receipt' => $cancelledOrder->receipt_number
        ];
    }

    public function getPaymentAmountTotal(string $type): int
    {
        $date = $this->date ?? now()->format('Y-m-d');

        $query = DB::table('payment_methods')
            ->join('transactions', 'payment_methods.transaction_id', '=', 'transactions.id')
            ->join('orders', 'payment_methods.order_id', '=', 'orders.order_id')
            ->whereDate('transactions.created_at', $date)
            ->where('payment_methods.method', $type)
            ->where('payment_methods.credit', 0)
            ->where('transactions.type', '!=', 'void')
            ->where('transactions.is_same_day_cancelled', 0);

        if($this->branch) $query->where('orders.branch_id', $this->branch);

        return $query->sum('payment_methods.amount');
    }

    /**
     * @return Collection<int, object>
     */
    public function getExpenses(): Collection
    {
        $date = $this->date ?? now()->format('Y-m-d');

        $query = DB::table('expenses')
            ->join('users', 'expenses.actor', '=', 'users.id')
            ->whereDate('expenses.date', $date);

        if($this->branch) $query->where('expenses.branch_id', $this->branch);

        return $query->orderBy('expenses.created_at')->get();
    }

    public function getExpensesTotal(): int
    {
        $date = $this->date ?? now()->format('Y-m-d');

        $query = DB::table('expenses')
            ->whereDate('date', $date);

        if($this->branch) $query->where('branch_id', $this->branch);

        return $query->sum('amount');
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

    public function getProductSupplierCode(string $productId): string
    {
        $product = Product::find($productId);
        if(!$product) return '';

        return $product->supplier->code;
    }

    public function isVoid($transaction): bool
    {
        return ($transaction->status == 3 || $transaction->status == 4) && $this->isSameDayCancelled($transaction->order_id);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.reports.reports', [
            'transactions' => $this->getTransactions(),
            'collections' => $this->getCollections(),
            'expenses' => $this->getExpenses(),
            'branches' => Branch::all(),
            'branch_name' => $this->branch ? Branch::find($this->branch)?->name : null,
        ]);
    }

    
}

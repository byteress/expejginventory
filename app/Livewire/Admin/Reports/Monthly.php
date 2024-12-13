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

#[Title('Monthly Report')]
class Monthly extends Component
{
    #[Url]
    public ?string $date = null; // Holds the date for the report, defaulting to the current month

    #[Session]
    public ?string $branch = null; // Holds the branch ID from session

    public $checkPayments = [];
    public $bankPayments = [];
    public $cardPayments = [];
    public $cashPayments = [];
    public $gcashPayments = [];


    /**
     * Initialization of the component.
     * Sets the default date and branch ID if not provided.
     */
    public function mount(): void
    {
        $this->date = $this->date ?? now()->format('Y-m'); // Default to the current year and month
        $this->branch = $this->branch ?: auth()->user()->branch_id; // Default branch to authenticated user's branch
        $this->fetchPayments();
    }

    public function fetchPayments(): void
    {
        $date = $this->date ?? now()->format('Y-m');

        $this->checkPayments = $this->getPaymentsByType('check');
        $this->bankPayments = $this->getPaymentsByType('bank');
        $this->cardPayments = $this->getPaymentsByType('card');
        $this->cashPayments = $this->getPaymentsByType('cash');
        $this->gcashPayments = $this->getPaymentsByType('gcash');
    }

    /**
     * Retrieves payments by type.
     *
     * @param string $type
     * @return Collection
     */
    private function getPaymentsByType(string $type): Collection
    {
        $query = DB::table('payment_methods')
        ->join('transactions', 'payment_methods.transaction_id', '=', 'transactions.id')
        ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
        ->join('customers', 'orders.customer_id', '=', 'customers.id')
        ->where('payment_methods.method', $type)
        ->where('transactions.created_at', 'like', "{$this->date}-%")
        ->select([
            'transactions.created_at as date',
            DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"),
            'payment_methods.reference',
            'payment_methods.amount',
        ]);

        if ($this->branch) {
            $query->where('orders.branch_id', $this->branch);
        }

    return $query->get();

    }


    /**
     * Handles updates to the branch and redirects with updated parameters.
     * @param string $branch
     * @return void
     */
    public function updatedBranch(string $branch): void
    {
        $this->branch = $branch;

        $this->redirect(route('admin.reports.monthly', [
            'date' => $this->date
        ]), true);
    }

    /**
     * Changes the report date by incrementing or decrementing the month.
     * @param string $action
     * @return  void
     * @throws \DateMalformedStringException
     */
    public function changeDate(string $action): void
    {
        $date = $this->date ?? now()->format('Y-m'); // Default to current month and year

        if ($action == 'increment') {
            // Increment the month
            $this->date = (new DateTime($date))->modify('+1 month')->format('Y-m');
        } else {
            // Decrement the month
            $this->date = (new DateTime($date))->modify('-1 month')->format('Y-m');
        }

        $this->redirect(route('admin.reports.monthly', [
            'date' => $this->date
        ]), true);
    }

    /**
     * Event listener to update the date.
     * @param string $date
     * @return void
     */
    #[On('date-set')]
    public function setDate(string $date): void
    {
        $this->redirect(route('admin.reports.monthly', [
            'date' => $date
        ]), true);
    }

    /**
     * Retrieves all transactions for the specified date and branch.
     * @return Collection
     */
    public function getTransactions(): Collection
    {
        $date = $this->date ?? now()->format('Y-m');

        $query = DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select([
                'transactions.id as transaction_id',
                'orders.id as order_number',
                'transactions.*',
                'orders.*',
                'customers.*'
            ])
            ->whereDate('transactions.created_at', 'like', "{$date}-%")
            ->whereIn('transactions.type', ['full', 'down']) // Filters for specific transaction types
            ->where('orders.previous', 0); // Exclude previous orders

        if ($this->branch) {
            $query->where('orders.branch_id', $this->branch);
        }

        return $query->get();
    }

    /**
     * Retrieves collections (e.g., payments made via installments or COD).
     * @return Collection
     */
    public function getCollections(): Collection
    {
        $date = $this->date ?? now()->format('Y-m');

        $query = DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select([
                'transactions.id as transaction_id',
                'orders.id as order_number',
                'transactions.*',
                'orders.*',
                'customers.*'
            ])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['installment', 'cod']); // Filters for installment and COD payments

        if ($this->branch) {
            $query->where('orders.branch_id', $this->branch);
        }

        return $query->get();
    }

    /**
     * Retrieves line items for a specific order.
     */
    public function getItems(string $orderId): Collection
    {
        return DB::table('line_items')
            ->where('order_id', $orderId)
            ->get();
    }

    /**
     * Retrieves daily expenses for the month, grouped by day.
     */
    public function getDailyExpenses(): Collection
    {
        $date = $this->date ?? now()->format('Y-m');

        $query = DB::table('expenses')
            ->where('date', 'like', "{$date}-%") // Matches the specified month
            ->selectRaw('DAY(date) as day, SUM(amount) as total_expenses') // Groups by day
            ->groupByRaw('DAY(date)');

        if ($this->branch) {
            $query->where('branch_id', $this->branch); // Filters by branch
        }

        return $query->get();
    }

    /**
     * Retrieves daily payment totals for the month, grouped by day.
     */
    public function getDailyPaymentAmountTotal(): Collection
    {
        $date = $this->date ?? now()->format('Y-m');

        $query = DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['transactions.id as transaction_id', 'transactions.created_at as creation_date', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*', 'users.first_name as fname', 'users.last_name as lname'])
            ->where('transactions.created_at', 'like', "{$date}-%")
            ->whereIn('transactions.type', ['installment', 'cod']);

        if ($this->branch) {
            $query->where('orders.branch_id', $this->branch);
        }

        return $query->get();
    }

    /**
     * Retrieves total payment amount for a specific transaction and payment type.
     */
    public function getPaymentAmount(string $transactionId, string $type): int
    {
        $query = DB::table('payment_methods')
            ->join('orders', 'payment_methods.order_id', '=', 'orders.order_id')
            ->where('payment_methods.transaction_id', $transactionId)
            ->where('payment_methods.method', $type)
            ->where('payment_methods.credit', 0); // Excludes credit transactions

        if ($this->branch) {
            $query->where('orders.branch_id', $this->branch);
        }

        return $query->sum('payment_methods.amount');
    }

    /**
     * Retrieves total payment amount for a specific type across the month.
     */
    public function getPaymentAmountTotal(string $type): int
    {
        $date = $this->date ?? now()->format('Y-m');

        $query = DB::table('payment_methods')
            ->join('transactions', 'payment_methods.transaction_id', '=', 'transactions.id')
            ->join('orders', 'payment_methods.order_id', '=', 'orders.order_id')
            ->whereDate('transactions.created_at', $date)
            ->where('payment_methods.method', $type)
            ->where('payment_methods.credit', 0) // Excludes credit transactions
            ->where('transactions.type', '!=', 'void'); // Excludes void transactions

        if ($this->branch) {
            $query->where('orders.branch_id', $this->branch);
        }

        return $query->sum('payment_methods.amount');
    }

    /**
     * Retrieves all expenses for the month.
     */
    public function getExpenses(): Collection
    {
        $date = $this->date ?? now()->format('Y-m');

        $query = DB::table('expenses')
            ->join('users', 'expenses.actor', '=', 'users.id')
            ->whereDate('expenses.date', $date);

        if ($this->branch) {
            $query->where('expenses.branch_id', $this->branch);
        }

        return $query->orderBy('expenses.created_at')->get();
    }

    /**
     * Retrieves the total expenses for the month.
     */
    public function getExpensesTotal(): int
    {
        $date = $this->date ?? now()->format('Y-m');

        $query = DB::table('expenses')
            ->whereDate('date', $date);

        if ($this->branch) {
            $query->where('branch_id', $this->branch);
        }

        return $query->sum('amount');
    }

    /**
     * Retrieves the supplier code for a specific product.
     * @param string $productId ID of the product
     * @return string Returns the supplier code
     */
    public function getProductSupplierCode(string $productId): string
    {
        $product = Product::find($productId);
        if (!$product) {
            return '';
        }

        return $product->supplier->code;
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

    /**
     * Renders the component view.
     */
    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.reports.monthly', [
            'transactions' => $this->getDailyPaymentAmountTotal(),
            'collections' => $this->getCollections(),
            'expenses' => $this->getExpenses(),
            'branches' => Branch::all(),
            'branch_name' => $this->branch ? Branch::find($this->branch)?->name : null,
        ]);
    }
}

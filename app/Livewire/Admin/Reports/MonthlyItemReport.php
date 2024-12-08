<?php

namespace App\Livewire\Admin\Reports;

use App\Livewire\DateTime;
use App\Livewire\Exception;
use BranchManagement\Models\Branch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use ProductManagement\Models\Product;

#[Title('Monthly Item Report')]
class MonthlyItemReport extends Component
{
    #[Url]
    public ?string $date = null; // Holds the date for the report, defaulting to the current month

    #[Session]
    public ?string $branch = null; // Holds the branch ID from session
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->date = $this->date ?? now()->format('Y-m'); // Default to the current year and month
        $this->branch = $this->branch ?: auth()->user()->branch_id; // Default branch to authenticated user's branch
    }

    public function updatedBranch(string $branch): void
    {
        $this->branch = $branch;

        $this->redirect(route('admin.reports.monthly.items', [
            'date' => $this->date,
            'product' => $this->product->id
        ]), true);
    }

    /**
     * @throws Exception
     */
    public function changeDate(string $action): void
    {
        $date = $this->date ?? now()->format('Y-m');

        if($action == 'increment'){
            $this->date = (new DateTime($date))->modify('+1 day')->format('Y-m');
        }else{
            $this->date = (new DateTime($date))->modify('-1 day')->format('Y-m');
        }

        $this->redirect(route('admin.reports.monthly.items', [
            'date' => $this->date,
            'product' => $this->product->id
        ]), true);
    }

    #[On('date-set')]
    public function setDate(string $date): void
    {
        $this->redirect(route('admin.reports.monthly.items', [
            'date' => $date,
            'product' => $this->product->id
        ]), true);
    }

    public function getOpeningQuantity(): int
    {
        $result = DB::table('stock_history')
            ->whereDate('date', '<', $this->date . '-1')
            ->where('branch_id', $this->branch)
            ->where('product_id', $this->product->id)
            ->latest('date')
            ->first();

        if(!$result) return 0;

        return $result->running_available + $result->running_reserved;
    }

    public function getClosingQuantity(): int|null
    {
        $result = DB::table('stock_history')
            ->whereMonth('date', date('m', strtotime($this->date)))
            ->whereYear('date', date('Y', strtotime($this->date)))
            ->where('branch_id', $this->branch)
            ->where('product_id', $this->product->id)
            ->latest('date')
            ->first();

        if(!$result) return null;

        return $result->running_available + $result->running_reserved;
    }

    public function getOrderHistory()
    {
        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->join('line_items', 'orders.order_id', '=', 'line_items.order_id')
            ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name', 'line_items.quantity'])
            ->where('orders.branch_id', $this->branch)
            ->where('orders.status', 1)
            ->where('line_items.product_id', $this->product->id)
            ->whereMonth('orders.completed_at', date('m', strtotime($this->date)))
            ->whereYear('orders.completed_at', date('Y', strtotime($this->date)))
            ->orderByDesc('orders.placed_at');

        return $query->get();
    }

    public function getReceives()
    {
        return DB::table('stock_history')
            ->whereMonth('date', $this->date)
            ->where('branch_id', $this->branch)
            ->where('product_id', $this->product->id)
            ->where('action', 'Received')
            ->latest('date')
            ->get();
    }

    public function getReference(int $quantity, $date)
    {
        $batch = DB::table('batches')
            ->join('batch_items', 'batches.id', '=', 'batch_items.batch_id')
            ->where('batch_items.product_id', $this->product->id)
            ->where('batch_items.quantity', $quantity)
            ->where('batches.branch_id', $this->branch)
            ->where('batches.created_at', $date)
            ->first();

        if($batch) return [
            'from' => 'Direct Supplier',
            'link' => route('admin.receive.history.details', ['batch' => $batch->id]),
            'number' => "RSR#$batch->batch_number",
        ];

        $transfer = DB::table('transfers')
            ->join('transfer_items', 'transfers.id', '=', 'transfer_items.transfer_id')
            ->join('branches', 'transfers.sender_branch', '=', 'branches.id')
            ->where('transfer_items.product_id', $this->product->id)
            ->where('transfer_items.received', $quantity)
            ->where('transfers.receiver_branch', $this->branch)
            ->where('transfers.updated_at', $date)
            ->first();

        if($transfer) return [
            'from' => $transfer->name,
            'link' => route('admin.transfer.history.details', ['transfer' => $transfer->transfer_id]),
            'number' => "SDR#$transfer->transfer_number",
        ];

        return [
            'from' => 'Direct Supplier',
            'link' => null,
            'number' => null
        ];
    }

    public function getTransfers()
    {
        return DB::table('transfers')
            ->join('transfer_items', 'transfers.id', '=', 'transfer_items.transfer_id')
            ->join('branches', 'transfers.receiver_branch', '=', 'branches.id')
            ->select(['transfers.*', 'branches.name', 'transfer_items.transferred'])
            ->where('transfer_items.product_id', $this->product->id)
            ->where('transfers.sender_branch', $this->branch)
            ->whereMonth('transfers.created_at', date('m', strtotime($this->date)))
            ->whereYear('transfers.created_at', date('Y', strtotime($this->date)))
            ->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.monthly-item-report', [
            'branches' => Branch::all(),
            'opening_quantity' => $this->getOpeningQuantity(),
            'closing_quantity' => $this->getClosingQuantity(),
            'orders' => $this->getOrderHistory(),
            'receives' => $this->getReceives(),
            'transfers' => $this->getTransfers(),
        ]);
    }
}

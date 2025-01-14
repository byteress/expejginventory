<?php

namespace App\Livewire\Admin\Product;

use BranchManagement\Models\Branch;
use DateTime;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sold Out Products')]

class ProductsSoldOut extends Component
{
    use WithPagination;

    #[Session]
    public ?string $branch = null;

    #[Url]
    public ?string $date = null;

    public function mount()
    {
        $this->date = $this->date ?? now()->format('Y-m-d');
        $this->branch = $this->branch ?: auth()->user()->branch_id;
    }

    /**
     * Handles updates to the branch and redirects with updated parameters.
     * @param string $branch
     * @return void
     */
    public function updatedBranch(string $branch): void
    {
        $this->branch = $branch;

        $this->redirect(route('admin.product.sold-out', [
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
        $date = $this->date ?? now()->format('Y-m-d'); // Default to current month and year

        if ($action == 'increment') {
            // Increment the month
            $this->date = (new DateTime($date))->modify('+1 day')->format('Y-m-d');
        } else {
            // Decrement the month
            $this->date = (new DateTime($date))->modify('-1 day')->format('Y-m-d');
        }

        $this->redirect(route('admin.product.sold-out', [
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
        $this->redirect(route('admin.product.sold-out', [
            'date' => $date
        ]), true);
    }


    public function getSoldOutItems(): LengthAwarePaginator
    {
        $date = $this->date;

        // Subquery to get the latest stock history record for each product and branch
        $latestRecords = DB::table('stock_history')
            ->select('product_id', 'branch_id', DB::raw('MAX(date) as latest_date'))
            ->whereDate('date', '=', $date)
            ->groupBy('product_id', 'branch_id');

        if($this->branch) $latestRecords->where('stock_history.branch_id', $this->branch);

// Subquery to filter stock history with the latest record and `running_available = 0`
        $filteredStockHistory = DB::table('stock_history as sh')
            ->joinSub($latestRecords, 'latest_records', function ($join) {
                $join->on('sh.product_id', '=', 'latest_records.product_id')
                    ->on('sh.branch_id', '=', 'latest_records.branch_id')
                    ->on('sh.date', '=', 'latest_records.latest_date');
            })
            ->where('sh.running_available', '=', 0)
            ->select('sh.product_id', 'sh.branch_id', 'sh.date', 'sh.running_available');

        $query = DB::table('products')
            ->leftJoinSub($filteredStockHistory, 'filtered_stock_history', function ($join) {
                $join->on('products.id', '=', 'filtered_stock_history.product_id');
            })
            ->join('branches', 'filtered_stock_history.branch_id', '=', 'branches.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select(
                'products.*', 'branches.name', 'suppliers.code'
            );


        return $query->paginate(10);
    }
    public function getAllSoldOutItems()
    {
        $date = $this->date;
        $query = DB::table('products')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            ->leftJoin('stock_history', function($join) use ($date) {
                $join->on('stock_history.product_id', '=', 'products.id')
                    ->whereDate('stock_history.date', $date)
                    ->where('stock_history.action', 'Sold');
            })
            ->join('branches', 'stocks.branch_id', '=', 'branches.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select(
                'products.*', 'branches.name', 'suppliers.code','stocks.available',
                DB::raw('stocks.available - COALESCE(SUM(CASE WHEN stock_history.action = "Sold" THEN stock_history.quantity ELSE 0 END), 0) AS remaining_stock')
            )
            ->where('stocks.available', '>', 0)
            ->groupBy('products.id', 'products.model', 'products.description', 'stocks.available')
            ->havingRaw('remaining_stock <= 0');
        if($this->branch) $query->where('stocks.branch_id', $this->branch);


        return $query->get();
    }

    public function getSoldItemCount($productId): int
    {
        $sold = 0;

        $result = DB::table('stock_history')
            ->whereDate('date', '<', $this->date)
            ->where('branch_id', $this->branch)
            ->where('product_id', $productId)
            ->latest('date')
            ->first();

        if($result) $sold = $result->running_sold;

        $result = DB::table('stock_history')
            ->whereDate('date', $this->date)
            ->where('branch_id', $this->branch)
            ->where('product_id', $productId)
            ->latest('date')
            ->first();

        $sold2 = $sold;

        if($result) $sold2 = $result->running_sold;

        return $sold2 - $sold;
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.products-sold-out',
        [
            'products' => $this->getSoldOutItems(),
            'allProducts' => $this->getAllSoldOutItems(),
            'branches' => Branch::all(),
            'branch_name' => $this->branch ? Branch::find($this->branch)?->name : null,
        ]);
    }
}

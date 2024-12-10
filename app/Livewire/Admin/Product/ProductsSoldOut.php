<?php

namespace App\Livewire\Admin\Product;

use BranchManagement\Models\Branch;
use DateTime;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sold Out Products')]

class ProductsSoldOut extends Component
{
    use WithPagination;

    public ?string $branch = null;

    #[Url]
    public ?string $date = null;

    public function mount()
    {
        $this->date = $this->date ?? now()->format('Y-m-d');
        if(auth()->user()) $this->branch = auth()->user()->branch_id;

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
                'products.*', 'branches.name', 'suppliers.code',
                DB::raw('stocks.available - COALESCE(SUM(CASE WHEN stock_history.action = "Sold" THEN stock_history.quantity ELSE 0 END), 0) AS remaining_stock')
            )
            ->where('stocks.available', '>', 0)
            ->groupBy('products.id', 'products.model', 'products.description', 'stocks.available')
            ->havingRaw('remaining_stock <= 0');
        if($this->branch) $query->where('stocks.branch_id', $this->branch);


        return $query->paginate(10);
    }


    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.product.products-sold-out',
        [
            'products' => $this->getSoldOutItems(),
            'branches' => Branch::all(),
        ]);
    }
}

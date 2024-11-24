<?php

namespace App\Livewire\Admin\Reports;
use DateTime;
use BranchManagement\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Title('Daily Item Report')]
class DailyItemReport extends Component
{

    #[Url]
    public ?string $date = null;

    #[Session]
    public ?string $branch = null;


    use WithPagination;

    #[Url(nullable:true)]
    public ?string $search = null;

    /**
     * @return LengthAwarePaginator<object>
     */
    private function getHistory(): LengthAwarePaginator
    {
        $date = $this->date ?? now()->format('Y-m-d');

        $query = DB::table('stock_history')
            ->join('products', 'stock_history.product_id', '=', 'products.id')
            ->join('branches', 'stock_history.branch_id', '=', 'branches.id')
            ->leftJoin('users', 'stock_history.user_id', '=', 'users.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select(
                'stock_history.*',
                'products.*',
                'suppliers.code',
                'branches.name',
                'users.first_name',
                'users.last_name'
            )
            ->whereDate('stock_history.date', $date)
            ->groupBy('products.id', 'branches.id')
            ->orderByDesc('date')
            ->orderByDesc('version');

        if ($this->branch) {
            $query->where('stock_history.branch_id', $this->branch);
        }

        return $query->paginate(10);
    }


    /**
     * Initialization of the component.
     * Sets the default date and branch ID if not provided.
     */
    public function mount(): void
    {
        $this->date = $this->date ?? now()->format('Y-m-d'); // Default to the current year and month
        $this->branch = $this->branch ?: auth()->user()->branch_id; // Default branch to authenticated user's branch
    }

    /**
     * Handles updates to the branch and redirects with updated parameters.
     * @param string $branch
     * @return void
     */
    public function updatedBranch(string $branch): void
    {
        $this->branch = $branch;

        $this->redirect(route('admin.reports.daily.items', [
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
        $date = $this->date ?? now()->format('Y-m-d');

        if($action == 'increment'){
            $this->date = (new DateTime($date))->modify('+1 day')->format('Y-m-d');
        }else{
            $this->date = (new DateTime($date))->modify('-1 day')->format('Y-m-d');
        }

        $this->redirect(route('admin.reports.daily.items', [
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
        $this->redirect(route('admin.reports.daily.items', [
            'date' => $date
        ]), true);
    }


    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.reports.daily-item-report', [
            'history' => $this->getHistory(),
            'branches' => Branch::all(),
            'branch_name' => $this->branch ? Branch::find($this->branch)?->name : null,
        ]);
    }
}

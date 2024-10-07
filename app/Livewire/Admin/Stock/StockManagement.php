<?php

namespace App\Livewire\Admin\Stock;

use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use Illuminate\Contracts\Foundation\Application as ApplicationAlias;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View as ViewAlias;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use StockManagementContracts\IStockManagementService;
use Throwable;

#[Title('Stock Management')]
class StockManagement extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public ?string $search = null;

    #[Validate('required')]
    public ?string $branch = null;

    #[Validate('required')]
    public int $quantity;

    public function mount(): void
    {
        if(auth()->user()) $this->branch = auth()->user()->branch_id;
    }

    /**
     * @throws Throwable
     */
    public function receive(IStockManagementService $stockManagementService, string $productId): void
    {
            DB::beginTransaction();

            $this->validate();

            if(!$this->branch) return;
            if(!auth()->user()) return;

            $result = $stockManagementService->receive($productId, $this->quantity, $this->branch, auth()->user()->id);
            if($result->isFailure()){
                DB::rollBack();
                session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
                return;
            }

            $this->reset('quantity');

            DB::commit();
            session()->flash('success', 'Product received successfully');
            $this->dispatch('close-modal');
    }

    /**
     * @throws Throwable
     */
    public function receiveDamaged(IStockManagementService $stockManagementService, string $productId): void
    {
        DB::beginTransaction();

        $this->validate();

        if(!$this->branch) return;
        if(!auth()->user()) return;

        $result = $stockManagementService->receiveDamaged($productId, $this->quantity, $this->branch, auth()->user()->id);
        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert_damaged', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $this->reset('quantity');

        DB::commit();
        session()->flash('success', 'Damaged product received successfully');
        $this->dispatch('close-modal');
    }

    /**
     * @throws Throwable
     */
    public function setDamaged(IStockManagementService $stockManagementService, string $productId): void
    {
        DB::beginTransaction();

        $this->validate();

        if(!$this->branch) return;
        if(!auth()->user()) return;

        $result = $stockManagementService->setDamaged($productId, $this->quantity, $this->branch, auth()->user()->id);
        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert_set_damaged', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $this->reset('quantity');

        DB::commit();
        session()->flash('success', 'Product set as damaged successfully');
        $this->dispatch('close-modal');
    }

    public function cancel(): void
    {
        $this->reset('quantity');
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    private function getProducts(): LengthAwarePaginator
    {
        $branchId = $this->branch;
        $query = DB::table('products')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->leftJoin('stocks', function ($join) use ($branchId) {
                $join->on('products.id', '=', 'stocks.product_id')
                    ->where('stocks.branch_id', '=', $branchId);
            })->where(function($q){
                $q->where('model', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('sku_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('description', 'LIKE', '%'.$this->search.'%');
            })->select('products.*', 'suppliers.code', DB::raw('COALESCE(stocks.available, 0) as quantity'))
            ->whereNull('products.deleted_at')
            ->orderByDesc('quantity');

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|ViewAlias|ApplicationAlias
    {
        return view('livewire.admin.stock.stock-management', [
            'products' => $this->getProducts(),
            'branches' => Branch::all()
        ]);
    }
}

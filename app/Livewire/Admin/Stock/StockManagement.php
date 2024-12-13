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
use Livewire\Component;
use Livewire\WithPagination;
use StockManagementContracts\IStockManagementService;
use Throwable;
use SupplierManagement\Models\Supplier;

#[Title('Stock Management')]
class StockManagement extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public ?string $search = null;

    public ?string $branch = null;
    public int $quantity;
    public ?int $available;
    public ?int $damaged;

    public function mount(): void
    {
        if(auth()->user()) $this->branch = auth()->user()->branch_id;
    }

    /**
     * @throws Throwable
     */
    public function adjust(IStockManagementService $stockManagementService, string $productId): void
    {
        if(!$this->branch) return;
        if(!auth()->user()) return;

        if(is_null($this->available) && is_null($this->damaged)){
            session()->flash('alert_adjust', 'Quantity is required.');
        }

        DB::beginTransaction();

        $result = $stockManagementService->adjust($productId, $this->available, $this->damaged, $this->branch, auth()->user()->id);
        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $this->reset('available', 'damaged');

        DB::commit();
        session()->flash('success', 'Product adjusted successfully');
        $this->dispatch('close-modal');
    }

    /**
     * @throws Throwable
     */
    public function receive(IStockManagementService $stockManagementService, string $productId): void
    {
            $this->validate([
                'quantity' => 'required',
                'branch'=> 'required'
            ]);

            if(!$this->branch) return;
            if(!auth()->user()) return;

            DB::beginTransaction();

            $result = $stockManagementService->receive($productId, $this->quantity, $this->branch, auth()->user()->id);
            if($result->isFailure()){
                DB::rollBack();
                session()->flash('alert_adjust', ErrorHandler::getErrorMessage($result->getError()));
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
    {   $this->validate([
            'quantity' => 'required',
            'branch'=> 'required'
        ]);

        if(!$this->branch) return;
        if(!auth()->user()) return;

        DB::beginTransaction();

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
    {   $this->validate([
            'quantity' => 'required',
            'branch'=> 'required'
        ]);

        if(!$this->branch) return;
        if(!auth()->user()) return;

        DB::beginTransaction();

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
        $this->reset('quantity', 'available', 'damaged');
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
                    ->orWhere('description', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('suppliers.code', 'LIKE', '%'.$this->search.'%');
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
            'branches' => Branch::all(),
            'suppliers' => Supplier::all()

        ]);
    }
}

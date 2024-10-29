<?php

namespace App\Livewire\Admin\Stock;

use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use ProductManagement\Models\Product;
use StockManagementContracts\IStockManagementService;

#[Title('ReceiveProducts')]

class ReceiveProducts extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;

    public array $selected = [];
    public array $quantities = [];

    #[Validate('required')]
    public ?string $branch;

    #[Validate('required')]
    public string $requestedBy = '';
    public string $notes = '';

    public function mount()
    {
        $this->branch = auth()->user()->branch_id;
    }

    public function select($productId){
        if(in_array($productId, $this->selected)) return;

        $this->selected[] = $productId;
    }

    public function remove($productId){
        if(!in_array($productId, $this->selected)) return;

        if (($key = array_search($productId, $this->selected)) !== false) {
            unset($this->selected[$key]);
        }
    }

    /**
     * @throws \Throwable
     */
    public function submit(IStockManagementService $stockManagementService): void
    {
        if(is_null(auth()->user())) return;
        $this->validate();

        DB::beginTransaction();

        $batchId = \Str::uuid()->toString();
        $result = $stockManagementService->batchReceive($batchId, $this->quantities, $this->branch, auth()->user()->id, $this->requestedBy, $this->notes);
        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash('success', 'Products received');
        $this->redirect(route('admin.receive.history.details', ['batch' => $batchId]));
    }

    private function getSelectedProducts()
    {
        $branchId = auth()->user()->branch_id;
        $query = DB::table('products')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->leftJoin('stocks', function ($join) use ($branchId) {
                $join->on('products.id', '=', 'stocks.product_id')
                    ->where('stocks.branch_id', '=', $branchId);
            })->select('products.*', 'suppliers.code', DB::raw('COALESCE(stocks.available, 0) as quantity'))
            ->whereIn('products.id', $this->selected)
            ->whereNull('products.deleted_at');

        return $query->get();
    }

    private function getProducts()
    {
        $branchId = auth()->user()->branch_id;
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
            ->whereNotIn('products.id', $this->selected)
            ->whereNull('products.deleted_at')
            ->orderByDesc('quantity');

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.receive-products', [
            'products' => $this->getProducts(),
            'selectedProducts' => $this->getSelectedProducts(),
            'branches' => Branch::all()
        ]);
    }
}

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
use Str;
use TransferContracts\ITransferService;

#[Title('Request Transfer')]
class RequestTransfer extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;

    public array $selected = [];
    public array $quantities = [];
    public array $stocks = [];

    public array $quantityToRequest = [];

    #[Validate('required')]
    public ?string $branch = null;

    public function mount()
    {
        $this->branch = auth()->user()->branch_id;
    }

    public function updatedBranch()
    {
        $this->dispatch('$refresh');
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

    public function getStocks($productId)
    {
        $branches = Branch::all();

        $html = '';
        foreach($branches as $branch){
            $stock = DB::table('stocks')
                ->where('branch_id', $branch->id)
                ->where('product_id', $productId)
                ->first();

            if(!$stock){
                $html .= "{$branch->name}: 0<br>";
                continue;
            }

            $html .= "{$branch->name}: {$stock->available}<br>";
        }

        $this->stocks[$productId] = $html;
    }

    public function submit(ITransferService $transferService, $productId)
    {
        $this->validate();

        $result = $transferService->request($productId, $this->branch, $this->quantityToRequest[$productId], auth()->user()->id);

        if($result->isFailure()){
            $this->addError("quantityToRequest.$productId", ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        // session()->flash('success', 'Transfer requested successfully.');
        //TODO() redirect to request single page

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
            ->whereIn('products.id', $this->selected);

        return $query->get();
    }

    private function getProducts()
    {
        $branchId = auth()->user()->branch_id;
        $query = DB::table('products')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->leftJoin('product_requests', function ($join) use ($branchId) {
                $join->on('products.id', '=', 'product_requests.product_id')
                    ->where('product_requests.receiver', '=', $this->branch);
            })
            ->leftJoin('stocks', function ($join) use ($branchId) {
                $join->on('products.id', '=', 'stocks.product_id')
                    ->where('stocks.branch_id', '=', $this->branch);
            })->where(function($q){
                $q->where('model', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('sku_number', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('description', 'LIKE', '%'.$this->search.'%')
                    ->orWhere('suppliers.code', 'LIKE', '%'.$this->search.'%');
            })->select('products.*', 'suppliers.code', 'product_requests.quantity as requested_quantity', DB::raw('COALESCE(stocks.available, 0) as quantity'))
            ->whereNotIn('products.id', $this->selected)
            ->whereNull('products.deleted_at')
            // ->where('product_requests.receiver', $branchId)
            ->orderBy('quantity');

        return $query->paginate(10);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.request-transfer', [
            'products' => $this->getProducts(),
            'selectedProducts' => $this->getSelectedProducts(),
            'branches' => Branch::all()
        ]);
    }
}

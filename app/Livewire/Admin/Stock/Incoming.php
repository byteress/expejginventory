<?php

namespace App\Livewire\Admin\Stock;

use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use StockManagementContracts\IStockManagementService;
use TransferContracts\ITransferService;

#[Title('Incoming')]

class Incoming extends Component
{
    public $requests = [];

    #[Validate('required')]
    public $branch = null;

    public array $quantityToReceive = [];

    public function mount()
    {
        $this->branch = auth()->user()->branch_id;
        $this->getRequests();
    }

    public function updatedBranch()
    {
        $this->getRequests();
    }

    public function submit(
        IStockManagementService $stockManagementService,
        ITransferService $transferService,
        $productId
    )
    {
        DB::beginTransaction();
        $stockResult = $stockManagementService->receive(
            $productId,
            $this->quantityToReceive[$productId],
            $this->branch,
            auth()->user()->id
        );

        if($stockResult->isFailure()){
            DB::rollBack();
            $this->addError("quantityToReceive.$productId", ErrorHandler::getErrorMessage($stockResult->getError()));
            return;
        }

        $transferResult = $transferService->receive(
            $productId,
            $this->branch,
            $this->quantityToReceive[$productId],
            auth()->user()->id
        );

        if($transferResult->isFailure()){
            DB::rollBack();
            $this->addError("quantityToReceive.$productId", ErrorHandler::getErrorMessage($transferResult->getError()));
            return;
        }

        session()->flash('success', 'Product received successfully').
        $this->resetExcept('branch');
        $this->getRequests();

        DB::commit();
    }

    public function getRequests()
    {
        $this->requests = DB::table('product_requests')
            ->join('branches', 'branches.id', '=', 'product_requests.receiver')
            ->join('products', 'product_requests.product_id', '=', 'products.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select('product_requests.*', 'branches.name', 'products.*', 'suppliers.code')
            ->where('receiver', $this->branch)
            ->where('incoming', '>', 0)
            ->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.incoming', [
            'branches' => Branch::all()
        ]);
    }
}

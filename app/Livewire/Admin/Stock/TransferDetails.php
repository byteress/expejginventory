<?php

namespace App\Livewire\Admin\Stock;

use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use ProductManagement\Models\Product;
use StockManagementContracts\IStockManagementService;
use Str;
use TransferContracts\ITransferService;

class TransferDetails extends Component
{
    public string $transferId;
    public array $products = [];
    public $details;
    public array $quantityToTransfer = [];

    #[Validate('required')]
    public ?string $branch;

    public function mount($transferId)
    {
        $this->transferId = $transferId;
        $this->branch = auth()->user()->branch_id;

        $this->getDetails();
    }

    public function updatedBranch()
    {
        $this->getDetails();
    }

    public function transfer(IStockManagementService $stockManagementService, ITransferService $transferService, $productId)
    {
        $this->validate();

        DB::beginTransaction();
        $reservationId = Str::uuid()->toString();
        $reserveResult = $stockManagementService->reserve(
            $productId,
            $reservationId,
            $this->quantityToTransfer[$productId],
            $this->branch,
            auth()->user()->id
        );

        if($reserveResult->isFailure()){
            DB::rollBack();
            $this->addError("quantityToTransfer.$productId", ErrorHandler::getErrorMessage($reserveResult->getError()));
            return;
        }

        $contributeResult = $transferService->contribute(
            $this->transferId,
            $productId,
            $this->quantityToTransfer[$productId],
            $reservationId,
            $this->branch,
            auth()->user()->id
        );

        if($contributeResult->isFailure()){
            DB::rollBack();
            $this->addError("quantityToTransfer.$productId", ErrorHandler::getErrorMessage($contributeResult->getError()));
            return;
        }

        $this->reset('quantityToTransfer');
        $this->getDetails();

        DB::commit();
    }

    private function getDetails()
    {
        $details = DB::table('request_details')
            ->join('transfer_requests', 'request_details.transfer_id', '=', 'transfer_requests.transfer_id')
            ->select('request_details.*', 'transfer_requests.id', 'transfer_requests.data->requested_by as requested_by')
            ->where('request_details.transfer_id', $this->transferId)
            ->first();
        
        $data = json_decode($details->data);
        foreach($data->products as $key => $value){
            $this->products[$key] = Product::withTrashed()
                ->where('id', $key)
                ->first();
        }

        $this->details = $details;
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        $details = $this->details;
        $title = "Request #{$details->id}";
        return view('livewire.admin.stock.transfer-details', [
            'title' => $title,
            'branches' => Branch::all()
        ])->title($title);
    }
}

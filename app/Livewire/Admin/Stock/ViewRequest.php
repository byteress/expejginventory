<?php

namespace App\Livewire\Admin\Stock;

use App\Exceptions\ErrorHandler;
use App\Models\User;
use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use StockManagementContracts\IStockManagementService;
use Str;
use TransferContracts\Exceptions\ErrorCode;
use TransferContracts\Exceptions\InvalidDomainException;
use TransferContracts\ITransferService;

#[Title('ViewRequest')]
class ViewRequest extends Component
{
    public $requests = [];
    public $selected = [];

    #[Validate('required')]
    public $branch = null;

    public $quantities = [];

    #[Validate('required')]
    public $driver = null;

    #[Validate('required')]
    public $truck = null;
    public string $notes;

    public function mount()
    {
        $this->branch = auth()->user()->branch_id;
        $this->getRequests();
    }

    public function select($key)
    {
        if(in_array($key, $this->selected)) return;
        $this->selected[] = $key;

        $explode = explode('.', $key);
        $row = DB::table('product_requests')
            ->where('product_id', $explode[0])
            ->where('receiver', $explode[1])
            ->first();

        if(!$row) return;

        $this->quantities[$explode[0]][$explode[1]] = $row->quantity - $row->incoming;
    }

    public function remove($key)
    {
        if(!in_array($key, $this->selected)) return;

        if (($index = array_search($key, $this->selected)) !== false) {
            unset($this->selected[$index]);
        }

        $explode = explode('.', $key);
        unset($this->quantities[$explode[0]][$explode[1]]);
    }

    public function getRequests()
    {
        $this->requests = DB::table('product_requests')
            ->join('branches', 'branches.id', '=', 'product_requests.receiver')
            ->join('products', 'product_requests.product_id', '=', 'products.id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select('product_requests.*', 'branches.name', 'products.*', 'suppliers.code')
            ->where('product_requests.quantity', '>', 0)
            ->get();
    }

    /**
     * @throws \Throwable
     */
    public function transfer(IStockManagementService $stockManagementService, ITransferService $transferService): void
    {
        $this->validate();

        $user = auth()->user();
        if(!$user) return;

        if(empty($this->quantities)){
            $this->addError('quantities', 'No request selected');
            return;
        }

        DB::beginTransaction();

        $transferId = Str::uuid()->toString();
        $receiverBranch = '';
        $transferProducts = [];
        foreach($this->quantities as $productId => $value){
            foreach($value as $branchId => $quantity){
                $releaseResult = $stockManagementService->release(
                    $productId,
                    $quantity,
                    $this->branch,
                    $user->id
                );

                if($releaseResult->isFailure()){
                    DB::rollBack();
                    $this->addError("request.$productId.$branchId", ErrorHandler::getErrorMessage($releaseResult->getError()));
                    return;
                }

                $receiverBranch = $branchId;
                $transferProducts[$productId] = $quantity;
            }
        }

        $transferResult = $transferService->transfer(
            $transferId,
            $transferProducts,
            $receiverBranch,
            $this->branch,
            $this->driver,
            $this->truck,
            $user->id,
            $this->notes
        );

        if($transferResult->isFailure()){
            DB::rollBack();
            $error = $transferResult->getError();
            if($error instanceof InvalidDomainException && in_array($error->getCode(), [ErrorCode::CANNOT_TRANSFER_TO_SELF->value, ErrorCode::EXCEEDED_REQUESTED_QUANTITY->value])){
                $errorData = $error->getData();
                $errorProductId = $errorData['product_id'] ?? null;
                if(is_string($errorProductId)){
                    $this->addError("request.$errorProductId.$receiverBranch", ErrorHandler::getErrorMessage($transferResult->getError()));
                    return;
                }
            }

            session()->flash('alert', ErrorHandler::getErrorMessage($transferResult->getError()));
            return;
        }

        DB::commit();
        $this->getRequests();
        session()->flash('success', 'Product/s transferred successfully');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.view-request', [
            'branches' => Branch::all(),
            'drivers' => User::role('delivery')->where('branch_id', $this->branch)->get()
        ]);
    }
}

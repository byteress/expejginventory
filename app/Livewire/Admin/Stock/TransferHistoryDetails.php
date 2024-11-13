<?php

namespace App\Livewire\Admin\Stock;

use App\Exceptions\ErrorHandler;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use StockManagementContracts\IStockManagementService;
use Transfer\Models\Transfer\Transfer;
use TransferContracts\Exceptions\ErrorCode;
use TransferContracts\Exceptions\InvalidDomainException;
use TransferContracts\ITransferService;

#[Title('Transfer History History')]
class TransferHistoryDetails extends Component
{
    public Transfer $transfer;

    /**
     * @var array<string, array{'received': int, 'damaged': int, 'lacking': int}>
     */
    public array $quantities;

    public function mount(Transfer $transfer): void
    {
        $this->transfer = $transfer;
    }

    /**
     * @throws \Throwable
     */
    public function submit(IStockManagementService $stockManagementService, ITransferService $transferService): void
    {
        $this->clearValidation();
        $user = auth()->user();
        if(!$user) return;
        DB::beginTransaction();

        $transferResult = $transferService->receive($this->transfer->id, $this->quantities, $user->id);
        if($transferResult->isFailure()){
            DB::rollBack();
            $error = $transferResult->getError();
            if($error instanceof InvalidDomainException && $error->getCode() == ErrorCode::EXCEEDED_TRANSFERRED_QUANTITY->value){
                $errorData = $error->getData();
                $errorProductId = $errorData['product_id'] ?? null;
                if(is_string($errorProductId)){
                    $this->addError("quantities.$errorProductId.received", ErrorHandler::getErrorMessage($transferResult->getError()));
                    return;
                }
            }

            session()->flash('alert', ErrorHandler::getErrorMessage($transferResult->getError()));
            return;
        }

        foreach ($this->quantities as $productId => $quantities){
            if($quantities['received'] > 0){
                $receiveResult = $stockManagementService->receive(
                    $productId,
                    $quantities['received'],
                    $this->transfer->receiver_branch,
                    $user->id
                );

                if($receiveResult->isFailure()){
                    DB::rollBack();
                    session()->flash('alert', ErrorHandler::getErrorMessage($transferResult->getError()));
                    return;
                }
            }

            if($quantities['damaged'] > 0){
                $receiveDamagedResult = $stockManagementService->receiveDamaged(
                    $productId,
                    $quantities['damaged'],
                    $this->transfer->receiver_branch,
                    $user->id
                );

                if($receiveDamagedResult->isFailure()){
                    DB::rollBack();
                    session()->flash('alert', ErrorHandler::getErrorMessage($transferResult->getError()));
                    return;
                }
            }
        }

        DB::commit();
        session()->flash('success', 'Transfer completed successfully');
    }

    public function getItems()
    {
        return DB::table('transfer_items')
            ->join('products', 'products.id', '=', 'transfer_items.product_id')
            ->where('transfer_items.transfer_id', $this->transfer->id)
            ->get();
    }


    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.transfer-history-details', [
            'items' => $this->getItems(),
        ]);
    }
}

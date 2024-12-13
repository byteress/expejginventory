<?php

namespace App\Livewire\Admin\Stock;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Transfer\Models\Transfer\Transfer;

#[Title('Transfer History')]
class TransferHistory extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;

    public function getTransfers()
    {
        return Transfer::where('transfer_number', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.transfer-history', [
            'transfers' => $this->getTransfers()
        ]);
    }
}

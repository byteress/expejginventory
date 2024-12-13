<?php

namespace App\Livewire\Admin\Stock;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use StockManagement\Models\Batch\Batch;

#[Title('History')]
class BatchHistory extends Component
{
    use WithPagination;

    #[Url(nullable:true)]
    public $search;

    public function getBatches()
    {
        return Batch::where('batch_number', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.batch-history', [
            'batches' => $this->getBatches()
        ]);
    }
}

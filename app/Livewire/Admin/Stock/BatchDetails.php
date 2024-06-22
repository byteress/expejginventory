<?php

namespace App\Livewire\Admin\Stock;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use StockManagement\Models\Batch\Batch;

#[Title('Batch Details')]
class BatchDetails extends Component
{
    public Batch $batch;

    public function mount(Batch $batch): void
    {
        $this->batch = $batch;
    }

    public function getItems()
    {
        return DB::table('batch_items')
            ->join('products', 'products.id', '=', 'batch_items.product_id')
            ->where('batch_items.batch_id', $this->batch->id)
            ->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.stock.batch-details', [
            'items' => $this->getItems()
        ]);
    }
}

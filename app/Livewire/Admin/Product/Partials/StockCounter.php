<?php

namespace App\Livewire\Admin\Product\Partials;

use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockCounter extends Component
{

    private ?string $branchId;
    private string $productId;
    private ?string $type = null;

    public function mount(string $productId, ?string $branchId = null, ?string $type = null): void
    {
        $this->branchId = $branchId;
        $this->productId = $productId;
        $this->type = $type;
    }

    public function getStocks()
    {

    }

    public function render() {
        return view('livewire.admin.product.partials.stock-counter',[
            'stocks' => $this->getStocks()
        ]);
    }

}

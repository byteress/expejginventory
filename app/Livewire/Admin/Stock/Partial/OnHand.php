<?php

namespace App\Livewire\Admin\Stock\Partial;

use BranchManagement\Models\Branch;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OnHand extends Component
{
    private $productId;
    private $branchId;

    public function mount($productId, $branchId = null)
    {
        $this->productId = $productId;
        $this->branchId = $branchId;
    }

    private function getOutput()
    {
        if($this->branchId){
            $stock = DB::table('stocks')
                ->where('product_id', $this->productId)
                ->where('branch_id', $this->branchId)
                ->first();

            if(!$stock) return "<span>0</span>";

            return "<span>{$stock->available}</span>";
        }

        $branches = Branch::all();

        $html = '';
        foreach($branches as $branch){
            $stock = DB::table('stocks')
                ->where('branch_id', $branch->id)
                ->where('product_id', $this->productId)
                ->first();

            if(!$stock){
                $html .= "{$branch->name}: 0<br>";
                continue;
            }

            $html .= "{$branch->name}: {$stock->available}<br>";
        }

        return $html;
    }

    public function render()
    {
        return view('livewire.admin.stock.partial.on-hand', [
            'output' => $this->getOutput()
        ]);
    }
}

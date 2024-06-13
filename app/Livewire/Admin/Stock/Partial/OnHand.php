<?php

namespace App\Livewire\Admin\Stock\Partial;

use BranchManagement\Models\Branch;
use Illuminate\Contracts\Foundation\Application as ApplicationAlias;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View as ViewAlias;
use Livewire\Component;

class OnHand extends Component
{
    private string $productId;
    private ?string $branchId;
    private string $type;

    public function mount(string $productId, ?string $branchId = null, ?string $type = null): void
    {
        $this->productId = $productId;
        $this->branchId = $branchId;
        $this->type = $type ?? 'available';
    }

    private function getOutput(): string
    {
        if($this->branchId){
            $stock = DB::table('stocks')
                ->select('*')
                ->selectRaw('available + reserved + damaged as total')
                ->where('product_id', $this->productId)
                ->where('branch_id', $this->branchId)
                ->first();

            if(!$stock || !isset($stock->{$this->type})) return "<span>0</span>";

            return "<span>{$stock->{$this->type}}</span>";
        }

        $branches = Branch::all();

        $html = '';
        foreach($branches as $branch){
            $stock = DB::table('stocks')
                ->select('*')
                ->selectRaw('available + reserved + damaged as total')
                ->where('branch_id', $branch->id)
                ->where('product_id', $this->productId)
                ->first();

            if(!$stock || !isset($stock->{$this->type})){
                $html .= "$branch->name: 0<br>";
                continue;
            }

            $html .= "$branch->name: {$stock->{$this->type}}<br>";
        }

        return $html;
    }


    public function render(): Factory|Application|View|ViewAlias|ApplicationAlias
    {
        return view('livewire.admin.stock.partial.on-hand', [
            'output' => $this->getOutput()
        ]);
    }
}

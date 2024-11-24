<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Daily Item Print')]
class DailyItemPrint extends Component
{
    public $branch;
    public $itemId;

    #[Layout('livewire.admin.base_layout')]
    public function mount($branch, $item_id)
    {
        $this->branch = $branch;
        $this->itemId = $item_id;
    }

    public function render(): View|Factory|Application
    {
        $data = DB::table('stock_history')
            ->join('products', 'products.id', '=', 'stock_history.product_id')
            ->join('branches', 'stock_history.branch_id', '=', 'branches.id')
            ->leftJoin('users', 'users.id', '=', 'stock_history.user_id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->select(
                'stock_history.*',
                'products.description as product_name',
                'products.regular_price as product_price',
                'branches.name as branch_name',
                'suppliers.name as supplier_name'
            )
            ->where('stock_history.product_id', $this->itemId)
            ->where('stock_history.branch_id', $this->branch)
            ->get();

        $branchName = $data->first()?->branch_name;
        $productName = $data->first()?->product_name;

        return view('livewire.admin.reports.print-daily-items', [
            'branch_name' => $branchName,
            'item_name' => $productName,
        ]);
    }

}

<?php

namespace App\Livewire\Admin\Reports;

use DateTime;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Reports')]
class Reports extends Component
{
    #[Url]
    public ?string $date = null;

    /**
     * @throws Exception
     */
    public function changeDate(string $action): void
    {
        $date = $this->date ?? now()->format('Y-m-d');

        if($action == 'increment'){
            $this->date = (new DateTime($date))->modify('+1 day')->format('Y-m-d');
        }else{
            $this->date = (new DateTime($date))->modify('-1 day')->format('Y-m-d');
        }

        $this->dispatch('date-changed');
    }

    /**
     * @return Collection<int, object>
     */
    public function getTransactions(): Collection
    {
        $date = $this->date ?? now()->format('Y-m-d');

        return DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(['transactions.id as transaction_id', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*'])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['full', 'down'])
            ->get();
    }

    /**
     * @param string $orderId
     * @return Collection<int, object>
     */
    public function getItems(string $orderId): Collection
    {
        return DB::table('line_items')
            ->where('order_id', $orderId)
            ->get();
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.reports.reports', [
            'transactions' => $this->getTransactions()
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Delivery;

use Delivery\Models\Delivery\Enums\DeliveryStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Delivery History')]
class History extends Component
{
    use WithPagination;

    /**
     * @return Collection<int, object>
     */
    public function getDeliveries()
    {
        return DB::table('deliveries')
            ->join('branches', 'deliveries.branch_id', '=', 'branches.id')
            ->join('users', 'deliveries.driver', '=', 'users.id')
            ->select(['deliveries.*', 'branches.name as branch_name', 'users.first_name as driver_first_name', 'users.last_name as driver_last_name'])
            ->where('status', '!=', DeliveryStatus::OUT_FOR_DELIVERY->value)
            ->orderByDesc('deliveries.completed_at')
            ->paginate(20);
    }

    #[Layout('livewire.admin.base_layout')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.delivery.history', [
            'deliveries' => $this->getDeliveries(),
        ]);
    }
}

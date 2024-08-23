<?php

namespace App\Livewire\Admin\Expense;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Order History')]
class DailyExpense extends Component
{
    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.expense.daily-expense');
    }
}

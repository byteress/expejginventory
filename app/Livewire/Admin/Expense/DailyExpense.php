<?php

namespace App\Livewire\Admin\Expense;

use App\Exceptions\ErrorHandler;
use BranchManagement\Models\Branch;
use DateTime;
use Exception;
use ExpenseManagement\Models\Expense as ExpenseModel;
use ExpenseManagementContracts\Enums\Expense;
use ExpenseManagementContracts\IExpenseManagementService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Daily Expense')]
class DailyExpense extends Component
{
    #[Url]
    public ?string $date = null;

    #[Validate('required')]
    public string $expense = '';

    #[Validate('required|numeric|min:0.01')]
    public float $amount;
    public ?string $description = null;

    #[Validate('required')]
    public string $pvc = '';

    #[Validate('required')]
    public string $branch = '';

    public function mount(): void
    {
        $user = auth()->user();
        if(!$user) return;

        $this->branch = $user->branch_id ?? '';
    }

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
     * @throws Exception
     */
    public function submit(IExpenseManagementService $expenseManagementService): void
    {
        $this->validate();
        if(!$this->date) $this->date = now()->format('Y-m-d');
        $user = auth()->user();
        if(!$user) return;

        $result = $expenseManagementService->create(
            Str::uuid()->toString(),
            $this->date,
            Expense::from($this->expense),
            $this->amount * 100,
            $this->pvc,
            $this->description,
            $user->id,
            $this->branch
        );

        if($result->isFailure()){
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        $this->reset('expense', 'amount', 'description');
        session()->flash('success', 'Expense added successfully');
    }

    /**
     * @return Collection<int, ExpenseModel>
     */
    public function getExpenses(): Collection
    {
        $date = $this->date ?? now()->format('Y-m-d');

        $query =  ExpenseModel::where('date', $date);

        if(!empty($this->branch)) $query->where('branch_id', $this->branch);

        return $query->orderBy('created_at', 'DESC')->get();
    }

    #[Layout('livewire.admin.base_layout')]
    #[On('expense_item_deleted')]
    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.expense.daily-expense', [
            'expenses' => Expense::cases(),
            'branches' => Branch::all(),
            'items' => $this->getExpenses()
        ]);
    }
}

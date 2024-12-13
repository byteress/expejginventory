<?php

namespace App\Livewire\Admin\Expense\Partials;

use BranchManagement\Models\Branch;
use ExpenseManagementContracts\Enums\Expense;
use ExpenseManagementContracts\IExpenseManagementService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ExpenseItem extends Component
{
    public string $expenseId;

    #[Validate('required')]
    public int $expense;

    #[Validate('required')]
    public string $pvc;

    #[Validate('required|numeric|min:1')]
    public float $amount;
    public string $description;
    public string $branchId;

    public function mount(string $expenseId, int $expense, int $amount, string $pvc, string $description, string $branchId): void
    {
        $this->expenseId = $expenseId;
        $this->expense = $expense;
        $this->amount = $amount / 100;
        $this->pvc = $pvc;
        $this->description = $description;
        $this->branchId = $branchId;
    }

    public function getExpenseName(int $value): string
    {
        return Expense::from($value)->displayName();
    }

    public function getBranchName(string $branchId): string
    {
        $branch = Branch::find($branchId);
        if(!$branch) return '';

        return $branch->name;
    }

    public function submit(IExpenseManagementService $expenseManagementService): void
    {
        $result = $expenseManagementService->update(
            $this->expenseId,
            Expense::from($this->expense),
            (int) round($this->amount * 100),
            $this->pvc,
            $this->description
        );
    }

    public function delete(IExpenseManagementService $expenseManagementService): void
    {
        $expenseManagementService->delete($this->expenseId);

        $this->dispatch('expense_item_deleted');
    }

    public function render(): Factory|Application|View|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.expense.partials.expense-item', [
            'expenses' => Expense::cases(),
        ]);
    }
}

<?php

namespace ExpenseManagementContracts;

use ExpenseManagementContracts\Enums\Expense;
use ExpenseManagementContracts\Utils\Result;

interface IExpenseManagementService
{
    public function create(
        string  $expenseId,
        string  $date,
        Expense $expense,
        int     $amount,
        ?string $description,
        string  $actor,
        string $branch
    ): Result;

    public function update(
        string $expenseId,
        Expense $expense,
        int $amount,
        string $description
    ): Result;

    public function delete(
        string $expenseId
    ): Result;
}

<?php

namespace ExpenseManagement;

use Exception;
use ExpenseManagement\Models\Expense as ExpenseModel;
use ExpenseManagementContracts\Enums\Expense;
use ExpenseManagementContracts\Exceptions\InvalidDomainException;
use ExpenseManagementContracts\IExpenseManagementService;
use ExpenseManagementContracts\Utils\Result;

class ExpenseManagementService implements IExpenseManagementService
{

    public function create(
        string $expenseId,
        string $date,
        Expense $expense,
        int $amount,
        ?string $description,
        string $actor,
        string $branch
    ): Result
    {
        try{
            $expenseModel = new ExpenseModel();
            $expenseModel->id = $expenseId;
            $expenseModel->date = $date;
            $expenseModel->amount = $amount;
            $expenseModel->expense = $expense->value;
            $expenseModel->actor = $actor;
            $expenseModel->description = $description;
            $expenseModel->branch_id = $branch;
            $expenseModel->save();

            return Result::success(null);
        }catch (Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function update(string $expenseId, Expense $expense, int $amount, string $description): Result
    {
        try{
            $expenseModel = ExpenseModel::find($expenseId);

            if(!$expenseModel) throw new InvalidDomainException('Expense not found', [
                'expense' => 'Expense not found'
            ]);

            $expenseModel->amount = $amount;
            $expenseModel->expense = $expense->value;
            $expenseModel->description = $description;
            $expenseModel->save();

            return Result::success(null);
        }catch (Exception $e){
            report($e);
            return Result::failure($e);
        }
    }

    public function delete(string $expenseId): Result
    {
        try{
            ExpenseModel::where('id', $expenseId)->delete();

            return Result::success(null);
        }catch (Exception $e){
            report($e);
            return Result::failure($e);
        }
    }
}

<?php

namespace ExpenseManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;
}

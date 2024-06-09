<?php

namespace CustomerManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
<?php

namespace StockManagement\Models\Batch;

use App\Models\User;
use BranchManagement\Models\Branch;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function generateBatchNumber(): string
    {
        $date = now()->format('Ymd');
        $latestBatch = self::whereDate('created_at', now()->format('Y-m-d'))
            ->orderBy('batch_number', 'desc')
            ->first();

        if ($latestBatch) {
            $lastNumber = substr($latestBatch->batch_number, -2);
            $nextNumber = intval($lastNumber) + 1;
        } else {
            $nextNumber = 1;
        }

        return $date . str_pad(''. $nextNumber, 2, '0', STR_PAD_LEFT);
    }
}

<?php

namespace Transfer\Models\Transfer;

use App\Models\User;
use BranchManagement\Models\Branch;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function source()
    {
        return $this->belongsTo(Branch::class, 'sender_branch');
    }

    public function receiving()
    {
        return $this->belongsTo(Branch::class, 'receiver_branch');
    }

    public function truckDriver()
    {
        return $this->belongsTo(User::class, 'driver');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested');
    }

    public static function generateTransferNumber(): string
    {
        $latestBatch = self::orderBy('transfer_number', 'desc')
            ->first();

        if ($latestBatch) {
            $lastNumber = $latestBatch->transfer_number;
            $nextNumber = intval($lastNumber) + 1;
        } else {
            $nextNumber = 1;
        }

        return str_pad(''. $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}

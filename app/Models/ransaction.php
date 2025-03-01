<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transactionable_id',
        'transactionable_type',
        'transaction_type',
        'amount',
        'status',
        'reference_id'
    ];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
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

    protected $casts = [
        'transaction_type' => TransactionTypeEnum::class,
        'status' => TransactionStatusEnum::class,
    ];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}

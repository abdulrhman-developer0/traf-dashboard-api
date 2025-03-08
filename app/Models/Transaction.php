<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
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
        'reference_id',
        'description'
    ];

    protected $casts = [
        'transaction_type' => TransactionType::class,
        'status' => TransactionStatus::class,
    ];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}

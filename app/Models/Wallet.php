<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'account_holder_name',
        'account_number',
        'iban',
        'bank_name',
    ];

    /** Start Of Logic functions */

    public function deposit(float $amount, string $description, ?string $refId = null)
    {
        $transaction = $this->transactions()->create([
            'transaction_type'  => TransactionType::DEPOSIT,
            'status'            => TransactionStatus::COMPLETED,
            'description'       => $description,
            'amount'            => $amount,
            'reference_id'      => $refId
        ]);

        $this->increment('balance', $amount);

        return $transaction;
    }

    public function withdraw(float $amount, string $description, ?string $refId = null)
    {
        $transaction = $this->transactions()->create([
            'transaction_type'  => TransactionType::WITHDRAW,
            'status'            => TransactionStatus::PENDING,
            'description'       => $description,
            'amount'            => $amount,
            'reference_id'      => $refId
        ]);

        $this->decrement('balance', $amount);

        return $transaction;
    }

    /** End Of Logic functions */

    /** Start Of Relationships */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}

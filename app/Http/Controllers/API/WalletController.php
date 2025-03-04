<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    use APIResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $wallet = $user->wallet()->firstOrCreate();

        $balance = $wallet->balance;

        $totalDeposit = where('transaction_type', TransactionType::DEPOSIT)->sum('amount');

        $totalWithdraw = where('transaction_type', TransactionType::WITHDRAW)->sum('amount');

        $totalPayment = where('transaction_type', TransactionType::PAYMENT)->sum('amount');

        $totalRefund = where('transaction_type', TransactionType::REFUND)->sum('amount');

        $totalTransfer = where('transaction_type', TransactionType::TRANSFER)->sum('amount');

        $transactionPaginator = $wallet->transactions()
            ->latest()
            ->paginate($request->input('page_size', 20));

        return $this->okResponse(
            [
                'balance'           => $balance,
                'total_deposit'     => $totalDeposit,
                'total_withdraw'    => $totalWithdraw,
                'total_payment'     => $totalPayment,
                'total_transfer'    => $totalTransfer,
                'transactions'      => TransactionResource::collection($transactionPaginator->items())
            ],
            __('wallet_retrieved_successfuly')
        );
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount'        => ['required', 'numeric', 'min:0.00'],
            'reference_id'  => ['required', 'string', 'max:255']
        ]);

        $user = $request->user();

        $wallet = $user->wallet()->firstOrCreate();

        $transaction = $wallet->transactions()->create([
            'transaction_type'  => TransactionType::DEPOSIT,
            'status'            => TransactionStatus::COMPLETED,
            'amount'            => $request->amount,
            'reference_id'      => $request->reference_id
        ]);

        // ..
        $wallet->increment('balance', $request->amount);

        return $this->okResponse(
            [
                'balance'       => $wallet->balance,
                'transaction'  => TransactionResource::make($transaction)
            ],
            __('transaction_created_successful')
        );
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount'        => ['required', 'numeric', 'min:0.00'],
        ]);


        $user = $request->user();

        $wallet = $user->wallet()->firstOrCreate();

        if ($request->amount > $wallet->balance) {
            return $this->badResponse(
                [
                    'balance'   => $wallet->balance,
                    'amount'    => $request->amount,
                ],
                __('هnsufficient_balance')
            );
        }


        $transaction = $wallet->transactions()->create([
            'transaction_type'  => TransactionType::WITHDRAW,
            'status'            => TransactionStatus::PENDING,
            'amount'            => $request->amount,
        ]);

        return $this->okResponse(
            [
                'balance'       => $wallet->balance,
                'transaction'  => TransactionResource::make($transaction)
            ],
            __('transaction_created_successful')
        );
    }
}

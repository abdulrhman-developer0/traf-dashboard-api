<?php

namespace App\Http\Controllers\API;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
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

        $transactionPaginator = $wallet->transactions()
            ->latest()
            ->paginate($request->input('page_size', 20));

        return $this->okResponse(
            [
                'balance'       => $wallet->balance,
                'transactions'  => TransactionResource::collection($transactionPaginator->items())
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

                if ( $request->amount > $wallet->balance ) {
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
